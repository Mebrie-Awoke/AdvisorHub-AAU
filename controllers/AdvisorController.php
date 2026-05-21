<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Advisor.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/../config/Mailer.php';

class AdvisorController {
    private $db;
    private $advisorModel;
    private $studentModel;
    private $notificationModel;
    private $questionModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'advisor') {
            header('Location: index.php?action=login');
            exit;
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->advisorModel = new Advisor($this->db);
        $this->studentModel = new Student($this->db);
        $this->notificationModel = new Notification($this->db);
        $this->questionModel = new Question($this->db);
    }

    public function dashboard() {
        $advisor = $this->advisorModel->findByUserId($_SESSION['user_id']);
        $students = $this->advisorModel->getAssignedStudents($advisor['id']);
        $notifications = $this->notificationModel->getForAdvisor($advisor['id']);
        $questions = $this->questionModel->getForAdvisor($advisor['id']);
        return ['advisor' => $advisor, 'students' => $students, 'notifications' => $notifications, 'questions' => $questions];
    }

    public function sendNotification() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $advisor = $this->advisorModel->findByUserId($_SESSION['user_id']);
            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);
            $is_urgent = !empty($_POST['is_urgent']);
            $sent_to_all = !empty($_POST['sent_to_all']);
            $recipient_ids = null;
            if (!$sent_to_all && !empty($_POST['recipient_ids'])) {
                $recipient_ids = json_encode($_POST['recipient_ids']);
            }

            $nid = $this->notificationModel->create($advisor['id'], $subject, $message, $is_urgent, $sent_to_all, $recipient_ids);
            if ($nid) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'advisor', 'sent_notification', json_encode(['notification_id' => $nid]), $_SERVER['REMOTE_ADDR'] ?? null);

                // If urgent or sent_to_all, create student_notifications and send emails for urgent
                $mailer = new Mailer();
                if ($sent_to_all) {
                    $students = $this->advisorModel->getAssignedStudents($advisor['id']);
                    foreach ($students as $s) {
                        $stmt = $this->db->prepare("INSERT INTO student_notifications (notification_id, student_id, email_sent) VALUES (:nid, :sid, :es)");
                        $es = $is_urgent ? 1 : 0;
                        $stmt->bindParam(':nid', $nid);
                        $stmt->bindParam(':sid', $s['id']);
                        $stmt->bindParam(':es', $es);
                        $stmt->execute();
                        if ($is_urgent) {
                            $studentEmail = $s['university_email'];
                            $mailer->send($studentEmail, $subject, $message);
                        }
                    }
                } else {
                    $recips = json_decode($recipient_ids ?: '[]', true);
                    foreach ($recips as $sid) {
                        $stmt = $this->db->prepare("INSERT INTO student_notifications (notification_id, student_id, email_sent) VALUES (:nid, :sid, :es)");
                        $es = $is_urgent ? 1 : 0;
                        $stmt->bindParam(':nid', $nid);
                        $stmt->bindParam(':sid', $sid);
                        $stmt->bindParam(':es', $es);
                        $stmt->execute();
                        if ($is_urgent) {
                            $s = $this->studentModel->findByUserId($sid);
                            $mailer->send($s['university_email'], $subject, $message);
                        }
                    }
                }

                $_SESSION['success'] = 'Notification sent.';
            } else {
                $_SESSION['error'] = 'Failed to send notification.';
            }
            header('Location: index.php?action=advisor_dashboard');
            exit;
        }
    }

    public function answerQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['question_id'])) {
            $qid = intval($_POST['question_id']);
            $answer = trim($_POST['answer']);
            if ($this->questionModel->answer($qid, $answer)) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'advisor', 'answered_question', json_encode(['question_id' => $qid]), $_SERVER['REMOTE_ADDR'] ?? null);
                $_SESSION['success'] = 'Question answered.';
            } else {
                $_SESSION['error'] = 'Failed to save answer.';
            }
            header('Location: index.php?action=advisor_dashboard');
            exit;
        }
    }

    public function resolveQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['question_id'])) {
            $qid = intval($_POST['question_id']);
            if ($this->questionModel->resolve($qid)) {
                $log = new ActivityLog($this->db);
                $log->log($_SESSION['user_id'], 'advisor', 'resolved_question', json_encode(['question_id' => $qid]), $_SERVER['REMOTE_ADDR'] ?? null);
                $_SESSION['success'] = 'Question marked resolved.';
            } else {
                $_SESSION['error'] = 'Failed to mark resolved.';
            }
            header('Location: index.php?action=advisor_dashboard');
            exit;
        }
    }
}
