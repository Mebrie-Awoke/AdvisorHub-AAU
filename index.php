<?php 
session_start();    
       
$action = isset($_GET['action']) ? $_GET['action'] : 'home';    
   
// Auth actions
if (in_array($action, ['login', 'register', 'logout', 'force_change_password', 'setup_password'])) {  
    require_once __DIR__ . '/controllers/AuthController.php';
    $authController = new AuthController();

    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->login();
    } elseif ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } elseif ($action === 'logout') {
        $authController->logout();
    } elseif ($action === 'force_change_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // process forced password change
        require_once __DIR__ . '/models/User.php';
        $db = (new Database())->connect();
        $userModel = new User($db);
        $user_id = $_SESSION['user_id'] ?? $_SESSION['user_id_temp'] ?? null;
        $password = $_POST['password'] ?? null;
        if ($user_id && $password) {
            $userModel->setPasswordHash($user_id, $password);
            // clear temp session key if present
            if (isset($_SESSION['user_id_temp'])) unset($_SESSION['user_id_temp']);
            $_SESSION['success'] = 'Password changed. Continue to dashboard.';
            header('Location: index.php?action=dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Unable to change password.';
            header('Location: index.php?action=force_change_password');
            exit;
        }
    } elseif ($action === 'setup_password') {
        // handled below in router (GET shows form, POST handled by controller endpoint created separately)
    }
}

// Registrar actions
if ($action === 'approve_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->approveStudent();
}

if ($action === 'reject_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->rejectStudent();
}

if ($action === 'create_advisor' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->createAdvisor();
}

if ($action === 'assign_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->assignStudent();
}

if ($action === 'delete_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->deleteUser();
} elseif ($action === 'approve_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->approveStudent();
} elseif ($action === 'reject_student' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->rejectStudent();
} elseif ($action === 'create_advisor' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->createAdvisor();
} elseif ($action === 'send_message' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controllers/RegistrarController.php';
    $registrarController = new RegistrarController();
    $registrarController->sendMessage();
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

    case 'setup_password':
        // If token in GET, show setup form; if POST, process setup
        require_once __DIR__ . '/models/PasswordToken.php';
        require_once __DIR__ . '/models/User.php';
        $token = $_GET['token'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? null;
            $password = $_POST['password'] ?? null;
            $db = (new Database())->connect();
            $pt = new PasswordToken($db);
            $t = $pt->findByToken($token);
            if (!$t || strtotime($t['expires_at']) < time() || $t['used_at']) {
                $_SESSION['error'] = 'Invalid or expired token.';
                header('Location: index.php?action=login');
                exit;
            }
            $userModel = new User($db);
            $userModel->setPasswordHash($t['user_id'], $password);
            $pt->markUsed($t['id']);
            $_SESSION['success'] = 'Password set. You can now login.';
            header('Location: index.php?action=login');
            exit;
        }
        include __DIR__ . '/views/auth/setup_password.php';
        break;

    case 'force_change_password':
        include __DIR__ . '/views/auth/force_change_password.php';
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
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        include __DIR__ . '/views/landing.php';
        break;

    default:
        header('Location: index.php?action=home');
        break;
}
