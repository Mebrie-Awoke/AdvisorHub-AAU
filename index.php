<?php
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Handle specific actions that require the AuthController
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
        
    case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        // Placeholder for dashboard views - Phase 2 will expand this
        include __DIR__ . '/views/layouts/header.php';
        echo "<div class='container' style='margin-top: 30px;'><h2>Welcome, " . htmlspecialchars($_SESSION['user_name']) . "</h2>";
        echo "<p>Your role is: " . htmlspecialchars($_SESSION['user_role']) . "</p>";
        echo "<p><em>Phase 2 will implement role-specific dashboards.</em></p></div>";
        include __DIR__ . '/views/layouts/footer.php';
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
