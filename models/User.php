<?php

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $email;
    public $password; // plaintext for input purposes only
    public $password_hash;
    public $role;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createUser($email, $role = 'student', $password_hash = null, $force_password_change = false) {
        $query = "INSERT INTO {$this->table} (email, password_hash, role, force_password_change) VALUES (:email, :password_hash, :role, :fpc)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':fpc', $force_password_change, PDO::PARAM_BOOL);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user || empty($user['password_hash'])) return false;
        if (password_verify($password, $user['password_hash'])) {
            // update last_login
            $update = $this->conn->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id");
            $update->bindParam(':id', $user['id']);
            $update->execute();
            return $user;
        }
        return false;
    }

    public function setPasswordHash($user_id, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET password_hash = :hash, force_password_change = 0 WHERE id = :id");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function setApprovalToken($user_id, $token) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET approval_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function approveUser($user_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = 'approved', is_approved = 1, approval_token = NULL WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function getPendingStudents() {
        $query = "SELECT u.* , s.* FROM users u JOIN students s ON u.id = s.user_id WHERE u.status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}

