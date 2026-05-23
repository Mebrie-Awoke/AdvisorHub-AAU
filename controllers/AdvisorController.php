<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Message.php';

class AdvisorController {
    private $db;
    private $assignment;
    private $messageModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'advisor') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->assignment = new Assignment($this->db);
        $this->messageModel = new Message($this->db);
    }

    public function getDashboardData() {
        $advisor_id = $_SESSION['user_id'];
        
        $assignedStudents = $this->assignment->getAssignedStudents($advisor_id);
        $messagesQuery = $this->messageModel->getMessagesForUser($advisor_id, 'advisor');

        return [
            'students' => $assignedStudents,
            'messages' => $messagesQuery->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
}
