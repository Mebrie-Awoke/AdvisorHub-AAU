<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Message.php';

class RegistrarController {
    private $db;
    private $assignment;
    private $userModel;
    private $messageModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'registrar') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->assignment = new Assignment($this->db);
        $this->userModel = new User($this->db);
        $this->messageModel = new Message($this->db);
    }

    public function assignStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->assignment->student_id = $_POST['student_id'];
            $this->assignment->advisor_id = $_POST['advisor_id'];
            $this->assignment->assigned_by = $_SESSION['user_id'];

            if ($this->assignment->assignStudent()) {
                $_SESSION['success'] = 'Student successfully assigned to advisor.';
            } else {
                $_SESSION['error'] = 'Failed to assign student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
            $userId = $_POST['user_id'];
            if ($this->userModel->deleteUser($userId)) {
                $_SESSION['success'] = 'User successfully deleted.';
            } else {
                $_SESSION['error'] = 'Failed to delete user.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function approveStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
            $studentId = $_POST['student_id'];
            $student = $this->userModel->getUserById($studentId);
            if ($this->userModel->approveStudent($studentId)) {
                // Send email
                $to = $student['email'];
                $subject = "AdvisorHub Account Approved";
                $msg = "Your AdvisorHub student account has been approved. You may now log in.";
                mail($to, $subject, $msg);
                $_SESSION['success'] = 'Student approved successfully.';
            } else {
                $_SESSION['error'] = 'Failed to approve student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function rejectStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
            $studentId = $_POST['student_id'];
            $student = $this->userModel->getUserById($studentId);
            if ($this->userModel->rejectStudent($studentId)) {
                // Send email
                $to = $student['email'];
                $subject = "AdvisorHub Account Rejected";
                $msg = "Your AdvisorHub registration was not approved. Please contact the registrar.";
                mail($to, $subject, $msg);
                $_SESSION['success'] = 'Student rejected successfully.';
            } else {
                $_SESSION['error'] = 'Failed to reject student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function createAdvisor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userModel->name = $_POST['name'];
            $this->userModel->email = $_POST['email'];
            $this->userModel->password = $_POST['password'];

            if ($this->userModel->emailExists()) {
                $_SESSION['error'] = 'Email already exists for advisor.';
            } else {
                if ($this->userModel->createAdvisor()) {
                    // Send email
                    $to = $this->userModel->email;
                    $subject = "AdvisorHub Account Created";
                    $msg = "Your AdvisorHub advisor account has been created. Please log in with the temporary password and change it.";
                    mail($to, $subject, $msg);
                    $_SESSION['success'] = 'Advisor account created successfully.';
                } else {
                    $_SESSION['error'] = 'Failed to create advisor.';
                }
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->messageModel->sender_id = $_SESSION['user_id'];
            $this->messageModel->title = $_POST['title'];
            $this->messageModel->message = $_POST['message'];
            
            $recipientMode = $_POST['recipient_mode']; // 'all' or 'specific'
            if ($recipientMode === 'all') {
                $this->messageModel->message_type = 'broadcast';
                $this->messageModel->audience_type = 'advisor';
                $this->messageModel->receiver_id = null;
            } else {
                $this->messageModel->message_type = 'individual';
                $this->messageModel->audience_type = null;
                $this->messageModel->receiver_id = $_POST['advisor_id'];
            }

            if ($this->messageModel->sendMessage()) {
                $_SESSION['success'] = 'Message sent successfully.';
            } else {
                $_SESSION['error'] = 'Failed to send message.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function getDashboardData() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $role_filter = isset($_GET['role']) ? $_GET['role'] : '';

        // Get all unassigned and assigned students
        $studentsQuery = $this->db->query("SELECT id, name FROM users WHERE role = 'student' AND status = 'approved'");
        $pendingStudentsQuery = $this->db->query("SELECT id, name, email, student_number FROM users WHERE role = 'student' AND status = 'pending'");
        $advisorsQuery = $this->db->query("SELECT id, name FROM users WHERE role = 'advisor'");
        
        $assignmentsQuery = $this->assignment->getAllAssignments();
        $allUsersQuery = $this->userModel->getAllUsers($search, $role_filter);

        // Calculate metrics
        $metrics = [
            'total_students' => $this->db->query("SELECT COUNT(id) FROM users WHERE role='student'")->fetchColumn(),
            'total_advisors' => $this->db->query("SELECT COUNT(id) FROM users WHERE role='advisor'")->fetchColumn(),
        ];

        return [
            'students' => $studentsQuery->fetchAll(PDO::FETCH_ASSOC),
            'pending_students' => $pendingStudentsQuery->fetchAll(PDO::FETCH_ASSOC),
            'advisors' => $advisorsQuery->fetchAll(PDO::FETCH_ASSOC),
            'assignments' => $assignmentsQuery->fetchAll(PDO::FETCH_ASSOC),
            'users' => $allUsersQuery->fetchAll(PDO::FETCH_ASSOC),
            'metrics' => $metrics,
            'search' => $search,
            'role_filter' => $role_filter
        ];
    }
}
