<?php

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        try {
            $this->conn->beginTransaction();

            $query = 'INSERT INTO ' . $this->table . ' SET name = :name, email = :email, password = :password, role = :role';
            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->role = htmlspecialchars(strip_tags($this->role));

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':role', $this->role);

            $stmt->execute();
            $newUserId = $this->conn->lastInsertId();

            if ($this->role === 'student') {
                $roleQuery = 'INSERT INTO students SET id = :id, user_id = :user_id';
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bindParam(':id', $newUserId);
                $roleStmt->bindParam(':user_id', $newUserId);
                $roleStmt->execute();
            } elseif ($this->role === 'advisor') {
                $roleQuery = 'INSERT INTO advisors SET id = :id, user_id = :user_id';
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bindParam(':id', $newUserId);
                $roleStmt->bindParam(':user_id', $newUserId);
                $roleStmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function login() {
        $query = 'SELECT id, name, email, password, role FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->role = $row['role'];
                return true;
            }
        }

        return false;
    }
    
    public function emailExists() {
        $query = 'SELECT id FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }
}
