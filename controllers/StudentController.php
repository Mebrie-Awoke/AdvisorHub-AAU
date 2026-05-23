<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Message.php';

class StudentController {
    private $db;
    private $assignment;
    private $messageModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->assignment = new Assignment($this->db);
        $this->messageModel = new Message($this->db);
    }

    public function getDashboardData() {
        $student_id = $_SESSION['user_id'];
        
        $assignedAdvisor = $this->assignment->getAssignedAdvisor($student_id);
        $messagesQuery = $this->messageModel->getMessagesForUser($student_id, 'student');

        return [
            'advisor' => $assignedAdvisor, // might be false if none assigned
            'messages' => $messagesQuery->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
}
