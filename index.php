<?php
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Handle specific actions that require Controllers
if (in_array($action, ['login', 'register', 'logout'])) {
    require_once __DIR__ . '/controllers/AuthController.php';
    $authController = new AuthController();
    
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->login();
    } elseif ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } elseif ($action === 'logout') {
        $authController->logout();
    }
} elseif ($action === 'assign_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->assignStudent();
} elseif ($action === 'delete_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->deleteUser();
}

// Simple Router
switch ($action) {
    case 'login':
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        include __DIR__ . '/views/auth/login.php';
        break;
        
    case 'register':
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        include __DIR__ . '/views/auth/register.php';
        break;
        
    case 'student_dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/views/dashboards/student.php';
        break;

    case 'advisor_dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'advisor') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/views/dashboards/advisor.php';
        break;

    case 'registrar_dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'registrar') {
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/views/dashboards/registrar.php';
        break;

    case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        // Fallback for general dashboard access
        if ($_SESSION['user_role'] == 'student') header('Location: index.php?action=student_dashboard');
        elseif ($_SESSION['user_role'] == 'advisor') header('Location: index.php?action=advisor_dashboard');
        elseif ($_SESSION['user_role'] == 'registrar') header('Location: index.php?action=registrar_dashboard');
        exit;
        break;
        
    case 'home':
    default:
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        header('Location: index.php?action=login');
        break;
}
