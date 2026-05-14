<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdvisorHub – AAU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<?php
// Determine if we are on an auth page (login/register) or a dashboard page
$isAuthPage = !isset($_SESSION['user_id']);
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'home';
$isAuthPage = in_array($currentAction, ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php if ($isAuthPage): ?>
<!-- ======= AUTH LAYOUT ======= -->
<div class="auth-page">
<?php else: ?>
<!-- ======= DASHBOARD LAYOUT ======= -->
<div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo-icon">🎓</div>
            <div>
                <div class="sidebar-logo-text">AdvisorHub</div>
                <div class="sidebar-logo-sub">AAU University System</div>
            </div>
        </div>

        <?php if(isset($_SESSION['user_id'])): 
            $initials = strtoupper(substr($_SESSION['user_name'], 0, 1));
        ?>
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo $initials; ?></div>
            <div class="sidebar-user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($_SESSION['user_role']); ?></div>
            </div>
        </div>
        <?php endif; ?>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>

            <?php if(isset($_SESSION['user_role'])): ?>
                <?php if($_SESSION['user_role'] === 'registrar'): ?>
                    <a href="index.php?action=registrar_dashboard" class="nav-item <?php echo ($currentAction==='registrar_dashboard')?'active':''; ?>">
                        <span class="nav-icon">🏠</span> Dashboard
                    </a>
                    <a href="index.php?action=registrar_dashboard" class="nav-item">
                        <span class="nav-icon">👥</span> Manage Users
                    </a>
                    <a href="index.php?action=registrar_dashboard" class="nav-item">
                        <span class="nav-icon">🔗</span> Assignments
                    </a>

                <?php elseif($_SESSION['user_role'] === 'advisor'): ?>
                    <a href="index.php?action=advisor_dashboard" class="nav-item <?php echo ($currentAction==='advisor_dashboard')?'active':''; ?>">
                        <span class="nav-icon">🏠</span> Dashboard
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">🎓</span> My Students
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">💬</span> Messages
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">🔔</span> Notifications
                    </a>

                <?php elseif($_SESSION['user_role'] === 'student'): ?>
                    <a href="index.php?action=student_dashboard" class="nav-item <?php echo ($currentAction==='student_dashboard')?'active':''; ?>">
                        <span class="nav-icon">🏠</span> Dashboard
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">👤</span> My Advisor
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">💬</span> Messages
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">📅</span> Appointments
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">🔔</span> Notifications
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <div class="nav-section-label" style="margin-top: 16px;">Account</div>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=logout" class="nav-item nav-logout">
                    <span class="nav-icon">🚪</span> Logout
                </a>
            <?php else: ?>
                <a href="index.php?action=login" class="nav-item">
                    <span class="nav-icon">🔑</span> Login
                </a>
            <?php endif; ?>
        </nav>

        <div class="sidebar-footer">© <?php echo date('Y'); ?> AdvisorHub AAU</div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
        <div class="topbar">
            <span class="topbar-title">
                <?php
                $pageNames = [
                    'registrar_dashboard' => 'Registrar Dashboard',
                    'advisor_dashboard'   => 'Advisor Dashboard',
                    'student_dashboard'   => 'Student Dashboard',
                ];
                echo $pageNames[$currentAction] ?? 'Dashboard';
                ?>
            </span>
            <div class="topbar-right">
                <?php if(isset($_SESSION['user_role'])): ?>
                    <span class="topbar-badge"><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Page Content Start -->
        <div class="page-content">

<?php endif; ?>

<!-- Flash Messages (both layouts) -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
