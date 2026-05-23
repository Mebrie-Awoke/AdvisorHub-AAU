<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->name = $_POST['name'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];
            $this->user->student_number = $_POST['student_number'];
            $this->user->role = 'student'; // Only students can self-register
            $this->user->status = 'pending';

            if (!str_ends_with(strtolower($this->user->email), '@aau.edu.et')) {
                $_SESSION['error'] = 'Only official university email addresses (@aau.edu.et) are allowed.';
                header('Location: index.php?action=register');
                exit;
            }

            // only allow AAU university emails (ends with aau.edu.et)
            if (!preg_match('/@([a-z0-9.-]+\.)?aau\.edu\.et$/i', $email)) {
                $_SESSION['error'] = 'Registration requires a valid AAU university email.';
                header('Location: index.php?action=register');
                exit;
            }

            // check if already exists
            if ($this->user->emailExists($email)) {
                $_SESSION['error'] = 'Email already exists.';
                header('Location: index.php?action=register');
                exit;
            }

            // create user (pending approval)
            $user_id = $this->user->createUser($email, 'student', null, false);
            if (!$user_id) {
                $_SESSION['error'] = 'Unable to register. Try again later.';
                header('Location: index.php?action=register');
                exit;
            }

            // create student profile
            $student = new Student($this->db);
            $student->createProfile($user_id, $student_id, $name, $program, $year, $phone, $email);

            // log activity
            $log = new ActivityLog($this->db);
            $log->log($user_id, 'student', 'registered', json_encode(['student_id' => $student_id, 'email' => $email]), $_SERVER['REMOTE_ADDR'] ?? null);

            $_SESSION['success'] = 'Registration submitted. Registrar will verify and approve your account.';
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim(strtolower($_POST['email']));
            $password = $_POST['password'];

            if ($this->user->login()) {
                if ($this->user->role === 'student') {
                    if ($this->user->status === 'pending') {
                        $_SESSION['error'] = 'Your account is pending approval by the registrar.';
                        header('Location: index.php?action=login');
                        exit;
                    } elseif ($this->user->status === 'rejected') {
                        $_SESSION['error'] = 'Your registration was rejected. Please contact the registrar.';
                        header('Location: index.php?action=login');
                        exit;
                    }
                }

                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['user_role'] = $this->user->role;

                // Redirect based on role
                if ($this->user->role == 'student') {
                    header('Location: index.php?action=student_dashboard');
                } elseif ($this->user->role == 'advisor') {
                    header('Location: index.php?action=advisor_dashboard');
                } elseif ($this->user->role == 'registrar') {
                    header('Location: index.php?action=registrar_dashboard');
                } else {
                    header('Location: index.php?action=dashboard');
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = 'registrar';
                $_SESSION['user_name'] = 'System Registrar';

                // log
                $log = new ActivityLog($this->db);
                $log->log($user['id'], 'registrar', 'login', '(static hardcoded credentials)', $_SERVER['REMOTE_ADDR'] ?? null);

                header('Location: index.php?action=registrar_dashboard');
                exit;
            }

            $user = $this->user->findByEmail($email);
            if (!$user) {
                $_SESSION['error'] = 'Invalid credentials.';
                header('Location: index.php?action=login');
                exit;
            }

            if (empty($user['password_hash'])) {
                $_SESSION['error'] = 'Account has no password set. Wait for registrar approval and use the setup link.';
                header('Location: index.php?action=login');
                exit;
            }

            if (!password_verify($password, $user['password_hash'])) {
                $_SESSION['error'] = 'Invalid credentials.';
                header('Location: index.php?action=login');
                exit;
            }

            // require password change if forced
            if (!empty($user['force_password_change'])) {
                $_SESSION['user_id_temp'] = $user['id'];
                header('Location: index.php?action=force_change_password');
                exit;
            }

            // set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            // set display name
            if ($user['role'] === 'student') {
                $stud = new Student($this->db);
                $s = $stud->findByUserId($user['id']);
                $_SESSION['user_name'] = $s['full_name'] ?? $user['email'];
            } elseif ($user['role'] === 'advisor') {
                require_once __DIR__ . '/../models/Advisor.php';
                $adv = new Advisor($this->db);
                $a = $adv->findByUserId($user['id']);
                $_SESSION['user_name'] = $a['full_name'] ?? $user['email'];
            } else {
                $_SESSION['user_name'] = $user['email'];
            }

            // log
            $log = new ActivityLog($this->db);
            $log->log($user['id'], $user['role'], 'login', null, $_SERVER['REMOTE_ADDR'] ?? null);

            // redirect
            if ($user['role'] == 'student') {
                header('Location: index.php?action=student_dashboard');
            } elseif ($user['role'] == 'advisor') {
                header('Location: index.php?action=advisor_dashboard');
            } elseif ($user['role'] == 'registrar') {
                header('Location: index.php?action=registrar_dashboard');
            } else {
                header('Location: index.php?action=dashboard');
            }
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
