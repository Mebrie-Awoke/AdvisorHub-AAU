<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Assignment.php';

class RegistrarController {
    private $db;
    private $assignment;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'registrar') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->assignment = new Assignment($this->db);
        $this->userModel = new User($this->db);
    }

    public function assignStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->assignment->student_id = $_POST['student_id'];
            $this->assignment->advisor_id = $_POST['advisor_id'];

            if ($this->assignment->assignStudent()) {
                $_SESSION['success'] = 'Student successfully assigned to advisor.';
            } else {
                $_SESSION['error'] = 'Failed to assign student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function getDashboardData() {
        // Get all unassigned and assigned students
        $studentsQuery = $this->db->query("SELECT id, name FROM users WHERE role = 'student'");
        $advisorsQuery = $this->db->query("SELECT id, name FROM users WHERE role = 'advisor'");
        
        $assignmentsQuery = $this->assignment->getAllAssignments();

        return [
            'students' => $studentsQuery->fetchAll(PDO::FETCH_ASSOC),
            'advisors' => $advisorsQuery->fetchAll(PDO::FETCH_ASSOC),
            'assignments' => $assignmentsQuery->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
}
