<?php

class ActivityLog {
    private $conn;
    private $table = 'activity_logs';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function log($user_id, $user_role, $action, $details = null, $ip_address = null) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (user_id, user_role, action, details, ip_address) VALUES (:user_id, :user_role, :action, :details, :ip)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_role', $user_role);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':ip', $ip_address);
        return $stmt->execute();
    }

    public function fetchAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
