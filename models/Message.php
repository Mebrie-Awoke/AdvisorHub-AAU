<?php

class Message {
    private $conn;
    private $table = 'messages';

    public $id;
    public $sender_id;
    public $receiver_id;
    public $audience_type;
    public $message_type;
    public $title;
    public $message;
    public $sent_at;
    public $is_read;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function sendMessage() {
        $query = 'INSERT INTO ' . $this->table . ' SET sender_id = :sender_id, receiver_id = :receiver_id, audience_type = :audience_type, message_type = :message_type, title = :title, message = :message';
        $stmt = $this->conn->prepare($query);

        $this->sender_id = htmlspecialchars(strip_tags($this->sender_id));
        if ($this->receiver_id !== null) {
            $this->receiver_id = htmlspecialchars(strip_tags($this->receiver_id));
        }
        if ($this->audience_type !== null) {
            $this->audience_type = htmlspecialchars(strip_tags($this->audience_type));
        }
        $this->message_type = htmlspecialchars(strip_tags($this->message_type));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->message = htmlspecialchars(strip_tags($this->message));

        $stmt->bindParam(':sender_id', $this->sender_id);
        $stmt->bindParam(':receiver_id', $this->receiver_id);
        $stmt->bindParam(':audience_type', $this->audience_type);
        $stmt->bindParam(':message_type', $this->message_type);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':message', $this->message);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getMessagesForUser($user_id, $role) {
        // Individual messages for this user
        $query = "SELECT m.*, u.name as sender_name 
                  FROM " . $this->table . " m
                  LEFT JOIN users u ON m.sender_id = u.id
                  WHERE m.receiver_id = :user_id ";

        // If the user is an advisor, also fetch mass broadcasts for advisors
        if ($role === 'advisor') {
            $query .= " OR (m.message_type = 'broadcast' AND m.audience_type = 'advisor') ";
        }

        $query .= " ORDER BY m.sent_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function markAsRead($message_id, $user_id) {
        // Note: For broadcast messages, marking as read requires a separate join table (e.g. message_reads),
        // but for simplicity here we just update it if it is an individual message.
        $query = 'UPDATE ' . $this->table . ' SET is_read = TRUE WHERE id = :message_id AND receiver_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':message_id', $message_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
