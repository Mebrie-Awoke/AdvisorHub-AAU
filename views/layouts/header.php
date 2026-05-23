<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdvisorHub – AAU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
session_start();
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'home';
$isAuthPage = in_array($currentAction, ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php if ($isAuthPage): ?>
<!-- ======= AUTH LAYOUT ======= -->
<div class="auth-page">

<?php else: ?>
<!-- ======= DASHBOARD LAYOUT ======= -->
<div class="layout">

    <nav class="navbar glass-panel">
        <div class="navbar-brand">
            <a href="index.php" class="navbar-logo">
                <div class="navbar-logo-icon">🎓</div>
                <div>
                    <div class="navbar-logo-text">AdvisorHub</div>
                    <div class="navbar-logo-sub">AAU University</div>
                </div>
            </a>
        </div>

        <button class="navbar-burger" id="mobileMenuToggle" aria-label="Open navigation menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar-menu" id="navbarMenu">
            <div class="navbar-links">
                <?php if(isset($_SESSION['user_role'])): ?>
                    <?php if($_SESSION['user_role'] === 'registrar'): ?>
                        <a href="index.php?action=registrar_dashboard" class="nav-item <?php echo ($currentAction==='registrar_dashboard')?'active':''; ?>">Dashboard</a>
                        <a href="index.php?action=registrar_dashboard" class="nav-item">Users</a>
                        <a href="index.php?action=registrar_dashboard" class="nav-item">Assignments</a>
                    <?php elseif($_SESSION['user_role'] === 'advisor'): ?>
                        <a href="index.php?action=advisor_dashboard" class="nav-item <?php echo ($currentAction==='advisor_dashboard')?'active':''; ?>">Dashboard</a>
                        <a href="#" class="nav-item">My Students</a>
                        <a href="#" class="nav-item">Messages</a>
                        <a href="#" class="nav-item">Notifications</a>
                    <?php elseif($_SESSION['user_role'] === 'student'): ?>
                        <a href="index.php?action=student_dashboard" class="nav-item <?php echo ($currentAction==='student_dashboard')?'active':''; ?>">Dashboard</a>
                        <a href="#" class="nav-item">My Advisor</a>
                        <a href="#" class="nav-item">Messages</a>
                        <a href="#" class="nav-item">Appointments</a>
                        <a href="#" class="nav-item">Notifications</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="navbar-actions">
                <button class="icon-btn" type="button" id="themeToggle" aria-label="Toggle dark mode">
                    <span class="icon">🌙</span>
                </button>
                <button class="icon-btn" type="button" aria-label="View notifications">
                    <span class="icon">🔔</span>
                    <span class="badge">3</span>
                </button>

                <?php if(isset($_SESSION['user_id'])):
                    $initials = strtoupper(substr($_SESSION['user_name'] ?? '', 0, 1));
                ?>
                    <div class="user-dropdown">
                        <button class="avatar-btn" type="button" id="userDropdownToggle" aria-label="Open user menu">
                            <?php echo $initials ?: 'A'; ?>
                        </button>
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <div class="dropdown-item dropdown-header">
                                <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'AdvisorHub User'); ?></span>
                                <small><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'guest'); ?></small>
                            </div>
                            <a href="index.php?action=dashboard" class="dropdown-item">Dashboard</a>
                            <a href="index.php?action=logout" class="dropdown-item danger">Sign Out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="icon-btn nav-link">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="page-content">

<?php endif; ?>

<!-- Flash Messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <span class="alert-icon">✅</span>
        <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <span class="alert-icon">❌</span>
        <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    </div>
<?php endif; ?>
