<?php

class PasswordToken {
    private $conn;
    private $table = 'password_tokens'; 

    public function __construct($db) {
        $this->conn = $db;
    }
  
    public function create($user_id, $token, $type, $expires_at) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (user_id, token, type, expires_at) VALUES (:user_id, :token, :type, :expires_at)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':expires_at', $expires_at);
        return $stmt->execute();
    }
   
    public function findByToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function markUsed($id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET used_at = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
