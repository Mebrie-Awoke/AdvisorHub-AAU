<?php

class Notification {
    private $conn;
    private $table = 'notifications';

    public function __construct($db) {
        $this->conn = $db;
    }
 
    public function create($advisor_id, $subject, $message, $is_urgent = false, $sent_to_all = false, $recipient_ids = null) {
        $query = "INSERT INTO {$this->table} (advisor_id, subject, message, is_urgent, sent_to_all, recipient_ids) VALUES (:advisor_id, :subject, :message, :is_urgent, :sent_to_all, :recipient_ids)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':is_urgent', $is_urgent, PDO::PARAM_BOOL);
        $stmt->bindParam(':sent_to_all', $sent_to_all, PDO::PARAM_BOOL);
        $stmt->bindParam(':recipient_ids', $recipient_ids);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getForAdvisor($advisor_id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE advisor_id = :advisor_id ORDER BY created_at DESC");
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
