<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Advisor.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class StudentController {
    private $db;
    private $studentModel;
    private $advisorModel;
    private $questionModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->studentModel = new Student($this->db);
        $this->advisorModel = new Advisor($this->db);
        $this->questionModel = new Question($this->db);
    }

    public function dashboard() {
        $user = (new User($this->db))->getById($_SESSION['user_id']);
        $student = $this->studentModel->findByUserId($_SESSION['user_id']);
        $advisor = $this->studentModel->getAssignedAdvisor($student['id']);

        // fetch notifications
        $stmt = $this->db->prepare("SELECT sn.*, n.subject, n.message, n.is_urgent FROM student_notifications sn JOIN notifications n ON sn.notification_id = n.id WHERE sn.student_id = :sid ORDER BY n.created_at DESC");
        $stmt->bindParam(':sid', $student['id']);
        $stmt->execute();
        $notifications = $stmt->fetchAll();

        // fetch questions
        $stmt2 = $this->db->prepare("SELECT q.*, a.full_name as advisor_name FROM questions q JOIN advisors a ON q.advisor_id = a.id WHERE q.student_id = :sid ORDER BY q.created_at DESC");
        $stmt2->bindParam(':sid', $student['id']);
        $stmt2->execute();
        $questions = $stmt2->fetchAll();

        return ['student' => $student, 'advisor' => $advisor, 'notifications' => $notifications, 'questions' => $questions];
    }

    public function askQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $student = $this->studentModel->findByUserId($_SESSION['user_id']);
            $advisor = $this->studentModel->getAssignedAdvisor($student['id']);
            if (!$advisor) {
                $_SESSION['error'] = 'No advisor assigned.';
                header('Location: index.php?action=student_dashboard');
                exit;
            }
            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);
            if ($this->questionModel->create($student['id'], $advisor['id'], $subject, $message)) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'student', 'asked_question', json_encode(['advisor_id' => $advisor['id']]), $_SERVER['REMOTE_ADDR'] ?? null);
                $_SESSION['success'] = 'Question sent to advisor.';
            } else {
                $_SESSION['error'] = 'Failed to send question.';
            }
            header('Location: index.php?action=student_dashboard');
            exit;
        }
    }

    public function markNotificationRead() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['notification_id'])) {
            $nid = intval($_POST['notification_id']);
            $stmt = $this->db->prepare("UPDATE student_notifications SET is_read = 1, read_at = NOW() WHERE id = :id");
            $stmt->bindParam(':id', $nid);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Notification marked read.';
            } else {
                $_SESSION['error'] = 'Failed to mark as read.';
            }
            header('Location: index.php?action=student_dashboard');
            exit;
        }
    }
}
