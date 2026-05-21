<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Mailer.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Advisor.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/PasswordToken.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class RegistrarController {
    private $db;
    private $assignment;
    private $userModel;
    private $studentModel;
    private $advisorModel;
    private $tokenModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'registrar') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->assignment = new Assignment($this->db);
        $this->userModel = new User($this->db);
        $this->studentModel = new Student($this->db);
        $this->advisorModel = new Advisor($this->db);
        $this->tokenModel = new PasswordToken($this->db);
    }

    public function approveStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['user_id'])) {
            $user_id = intval($_POST['user_id']);
            // approve user
            if ($this->userModel->approveUser($user_id)) {
                // create password setup token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 48 * 3600);
                $this->tokenModel->create($user_id, $token, 'setup', $expires);

                // send setup email
                $user = $this->userModel->getById($user_id);
                $setupLink = $this->makeUrl("index.php?action=setup_password&token={$token}");
                $mailer = new Mailer();
                $subject = 'AdvisorHub — Set up your password';
                $body = "Hello,\n\nYour AdvisorHub account has been approved. Set your password using the link below (expires in 48 hours):\n\n{$setupLink}\n\nIf you did not request this, ignore this email.";
                $mailer->send($user['email'], $subject, $body);

                // log
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'registrar', 'approved_student', json_encode(['user_id' => $user_id]), $_SERVER['REMOTE_ADDR'] ?? null);

                $_SESSION['success'] = 'Student approved and setup email sent.';
            } else {
                $_SESSION['error'] = 'Failed to approve student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function rejectStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['user_id'])) {
            $user_id = intval($_POST['user_id']);
            $stmt = $this->db->prepare("UPDATE users SET status = 'rejected' WHERE id = :id");
            $stmt->bindParam(':id', $user_id);
            if ($stmt->execute()) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'registrar', 'rejected_student', json_encode(['user_id' => $user_id]), $_SERVER['REMOTE_ADDR'] ?? null);
                $_SESSION['success'] = 'Student rejected.';
            } else {
                $_SESSION['error'] = 'Failed to reject student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function createAdvisor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim(strtolower($_POST['email']));
            $full_name = trim($_POST['full_name']);
            $department = trim($_POST['department'] ?? '');
            $office = trim($_POST['office_location'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email already exists.';
                header('Location: index.php?action=registrar_dashboard');
                exit;
            }

            $tempPassword = bin2hex(random_bytes(4));
            $hash = password_hash($tempPassword, PASSWORD_BCRYPT);
            $user_id = $this->userModel->createUser($email, 'advisor', $hash, true);
            if ($user_id) {
                $this->advisorModel->createProfile($user_id, $full_name, $department, $office, $phone, $_SESSION['user_id']);

                // email temp password
                $mailer = new Mailer();
                $subject = 'AdvisorHub — Your advisor account';
                $body = "Hello {$full_name},\n\nAn advisor account was created for you. Use the temporary password below to log in and change it immediately:\n\nEmail: {$email}\nPassword: {$tempPassword}\n\nPlease change your password on first login.";
                $mailer->send($email, $subject, $body);

                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'registrar', 'created_advisor', json_encode(['user_id' => $user_id, 'email' => $email]), $_SERVER['REMOTE_ADDR'] ?? null);

                $_SESSION['success'] = 'Advisor account created and emailed.';
            } else {
                $_SESSION['error'] = 'Failed to create advisor.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function assignStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $student_id = intval($_POST['student_id']);
            $advisor_id = intval($_POST['advisor_id']);
            if ($this->assignment->assignStudent($student_id, $advisor_id, $_SESSION['user_id'])) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'registrar', 'assigned_student', json_encode(['student_id' => $student_id, 'advisor_id' => $advisor_id]), $_SERVER['REMOTE_ADDR'] ?? null);
                $_SESSION['success'] = 'Student assigned.';
            } else {
                $_SESSION['error'] = 'Failed to assign student.';
            }
            header('Location: index.php?action=registrar_dashboard');
            exit;
        }
    }

    public function getDashboardData() {
        $pending = $this->userModel->getPendingStudents();
        $metrics = [
            'total_students' => $this->db->query("SELECT COUNT(id) FROM students")->fetchColumn(),
            'total_advisors' => $this->db->query("SELECT COUNT(id) FROM advisors")->fetchColumn(),
            'total_questions' => $this->db->query("SELECT COUNT(id) FROM questions")->fetchColumn(),
            'total_notifications' => $this->db->query("SELECT COUNT(id) FROM notifications")->fetchColumn(),
        ];

        $assignments = $this->assignment->getAllAssignments();
        $advisors = $this->db->query("SELECT * FROM advisors")->fetchAll();
        $students = $this->db->query("SELECT * FROM students")->fetchAll();

        return [
            'pending_students' => $pending,
            'metrics' => $metrics,
            'assignments' => $assignments,
            'advisors' => $advisors,
            'students' => $students
        ];
    }

    private function makeUrl($path) {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        return "{$proto}://{$host}{$base}/{$path}";
    }
}

