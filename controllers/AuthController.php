<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

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

            if ($this->user->emailExists()) {
                $_SESSION['error'] = 'Email already exists.';
                header('Location: index.php?action=register');
                exit;
            }

            if ($this->user->register()) {
                $_SESSION['success'] = 'Registration successful. Please log in.';
                header('Location: index.php?action=login');
                exit;
            } else {
                $_SESSION['error'] = 'Something went wrong during registration.';
                header('Location: index.php?action=register');
                exit;
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

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
                exit;
            } else {
                $_SESSION['error'] = 'Invalid email or password.';
                header('Location: index.php?action=login');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
