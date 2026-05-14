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
            $this->user->role = $_POST['role'];

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
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['user_role'] = $this->user->role;

                // Redirect based on role
                header('Location: index.php?action=dashboard');
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
