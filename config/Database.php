<?php

class Database {
    private $driver;
    private $host;
    private $db_name;
    private $db_path;
    private $username;
    private $password;
    private $port;
    private $conn;

    public function __construct() {
        $this->driver = getenv('DB_DRIVER') ?: null;
        $this->host = getenv('DB_HOST') ?: '127.0.0.1';
        $this->db_name = getenv('DB_NAME') ?: 'advisorhub';
        $this->db_path = getenv('DB_PATH') ?: __DIR__ . '/../' . $this->db_name . '.db';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: 3306;

        if (!$this->driver) {
            if (extension_loaded('pdo_mysql')) {
                $this->driver = 'mysql';
            } elseif (extension_loaded('pdo_sqlite')) {
                $this->driver = 'sqlite';
            } else {
                throw new RuntimeException('No PDO driver available. Install pdo_mysql or pdo_sqlite.');
            }
        }
    }

    public function connect() {
        $this->conn = null;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if ($this->driver === 'sqlite') {
            if (!extension_loaded('pdo_sqlite')) {
                throw new RuntimeException('SQLite driver is not enabled. Enable pdo_sqlite or set DB_DRIVER=mysql.');
            }

            $dsn = sprintf('sqlite:%s', $this->db_path);
            $this->conn = new PDO($dsn, null, null, $options);
            $this->conn->exec('PRAGMA foreign_keys = ON');
            $this->initializeSqlite();
            return $this->conn;
        }

        if ($this->driver === 'mysql') {
            if (!extension_loaded('pdo_mysql')) {
                if (extension_loaded('pdo_sqlite')) {
                    $this->driver = 'sqlite';
                    return $this->connect();
                }
                throw new RuntimeException('MySQL driver is not enabled. Enable pdo_mysql or set DB_DRIVER=sqlite.');
            }

            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $this->host, $this->port, $this->db_name);

            try {
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch(PDOException $e) {
                $msg = $e->getMessage();
                error_log('Database connection error: ' . $msg);

                if (strpos($msg, 'Unknown database') !== false || strpos($msg, '1049') !== false) {
                    try {
                        $this->initializeDatabase();
                        $this->conn = new PDO($dsn, $this->username, $this->password, $options);
                        return $this->conn;
                    } catch (Exception $e2) {
                        error_log('Failed to initialize database: ' . $e2->getMessage());
                        throw $e2;
                    }
                }

                throw $e;
            }

            return $this->conn;
        }

        throw new RuntimeException('Unsupported DB_DRIVER: ' . $this->driver);
    }

    private function initializeDatabase() {
        $serverDsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $this->host, $this->port);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $serverPdo = new PDO($serverDsn, $this->username, $this->password, $options);

        // Create database if not exists
        $createSql = sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $this->db_name);
        $serverPdo->exec($createSql);

        // Read setup file and execute statements (skip DROP/CREATE DATABASE and USE lines)
        $setupPath = __DIR__ . '/setup.sql';
        if (!file_exists($setupPath)) {
            return;
        }

        $sql = file_get_contents($setupPath);
        // Split statements by semicolon
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        // Connect to the newly created database
        $dbDsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $this->host, $this->port, $this->db_name);
        $dbPdo = new PDO($dbDsn, $this->username, $this->password, $options);

        foreach ($statements as $stmt) {
            $lower = strtolower($stmt);
            // skip statements that drop or create database or use database
            if (strpos($lower, 'drop database') === 0 || strpos($lower, 'create database') === 0 || strpos($lower, 'use ') === 0) {
                continue;
            }
            // execute the statement
            try {
                $dbPdo->exec($stmt);
            } catch (PDOException $ex) {
                // log and continue
                error_log('DB init statement failed: ' . $ex->getMessage() . ' -- SQL: ' . substr($stmt,0,200));
            }
        }
    }

    private function initializeSqlite() {
        $stmt = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if ($stmt && $stmt->fetch()) {
            return;
        }

        $setupPath = __DIR__ . '/setup_sqlite.sql';
        if (!file_exists($setupPath)) {
            throw new RuntimeException('SQLite setup file not found: ' . $setupPath);
        }

        $sql = file_get_contents($setupPath);
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $statement) {
            if ($statement === '') {
                continue;
            }
            try {
                $this->conn->exec($statement);
            } catch (PDOException $ex) {
                error_log('SQLite init failed: ' . $ex->getMessage() . ' -- SQL: ' . substr($statement, 0, 200));
            }
        }
    }
}
