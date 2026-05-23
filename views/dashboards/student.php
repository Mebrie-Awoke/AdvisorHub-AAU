<?php 
require_once __DIR__ . '/../../controllers/StudentController.php';
$controller = new StudentController();
$data = $controller->getDashboardData();

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Samuel';
$firstName = explode(' ', trim($userName))[0];

// Handle case where advisor is not yet assigned
$advisor = $data['advisor'] ? $data['advisor'] : null;

// Messages logic
$unreadMessages = count($data['messages']); // Mocking unread as all messages for UI
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - AdvisorHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base & Reset */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        button, input { font-family: inherit; }

        /* Layout */
        .sidebar {
            width: 250px;
            background: #0d47a1; /* Dark blue matching image */
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
        }
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: white;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .brand-subtitle {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Sidebar Nav */
        .sidebar-nav {
            padding: 0 16px;
            margin-top: 12px;
            flex: 1;
            overflow-y: auto;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            position: relative;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        .nav-item.active {
            background: #1565c0; /* Active state */
        }
        .nav-icon {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
        }
        .badge {
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            height: 20px;
            min-width: 20px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 6px;
            margin-left: auto;
        }

        /* Need Help Card */
        .help-card {
            margin: 24px 16px;
            background: #0a3579;
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
        }
        .help-card-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }
        .help-card-title {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }
        .help-card-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .btn-support {
            background: #1e88e5;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-support:hover {
            background: #1976d2;
        }

        /* Top Header */
        .topbar {
            height: 72px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 32px;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .menu-toggle {
            font-size: 1.25rem;
            color: #64748b;
            cursor: pointer;
        }
        .search-container {
            position: relative;
            width: 320px;
        }
        .search-container input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            font-size: 0.9rem;
            color: #334155;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-container input:focus {
            border-color: #0d47a1;
            background: white;
        }
        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .notification-bell {
            position: relative;
            color: #64748b;
            font-size: 1.25rem;
            cursor: pointer;
        }
        .notification-bell .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            border: 2px solid white;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
        }
        .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }
        .user-role {
            font-size: 0.8rem;
            color: #64748b;
        }
        .dropdown-icon {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Page Content */
        .page-content {
            padding: 32px;
        }
        
        /* Welcome Section */
        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex; align-items: center; gap: 8px;
        }
        .welcome-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }
        .date-picker {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #475569;
        }
        .date-picker-icon {
            color: #64748b;
        }

        /* Top Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        .metric-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .metric-icon-box {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .metric-icon-box.blue { background: #eff6ff; color: #3b82f6; }
        .metric-icon-box.green { background: #f0fdf4; color: #22c55e; }
        .metric-icon-box.orange { background: #fff7ed; color: #f97316; }
        .metric-icon-box.purple { background: #faf5ff; color: #a855f7; }
        
        .metric-info {
            flex: 1;
        }
        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .metric-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .metric-link {
            font-size: 0.8rem;
            color: #3b82f6;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .metric-link:hover {
            text-decoration: underline;
        }

        /* 2x2 Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
        }
        .card-action {
            color: #3b82f6;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Advisor Profile */
        .advisor-profile {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        .advisor-avatar {
            width: 80px; height: 80px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
        }
        .advisor-details {
            flex: 1;
        }
        .advisor-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .advisor-dept {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 12px;
        }
        .advisor-contact {
            font-size: 0.85rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .advisor-contact-icon {
            color: #94a3b8;
            font-size: 1rem;
        }
        .btn-outline {
            display: inline-flex;
            margin-top: 16px;
            padding: 8px 16px;
            border: 1px solid #3b82f6;
            color: #3b82f6;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-outline:hover {
            background: #eff6ff;
        }

        /* Lists (Messages / Notifications) */
        .list-item {
            display: flex;
            align-items: flex-start;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
            gap: 16px;
        }
        .list-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .list-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .list-icon-box {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        .list-icon-box.purple { background: #f3e8ff; color: #a855f7; }
        .list-icon-box.green { background: #dcfce7; color: #22c55e; }
        .list-icon-box.orange { background: #ffedd5; color: #f97316; }
        .list-content {
            flex: 1;
        }
        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .list-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #0f172a;
        }
        .list-time {
            font-size: 0.8rem;
            color: #94a3b8;
        }
        .list-desc {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.4;
        }
        
        /* Appointment Card */
        .appointment-box {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }
        .appointment-icon {
            width: 56px; height: 56px;
            border-radius: 12px;
            background: #f0fdf4;
            color: #22c55e;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .appointment-info h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .appointment-info p {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 12px;
        }
        .appointment-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 6px;
        }
        
        /* Quick Access */
        .quick-access {
            display: flex;
            gap: 16px;
        }
        .quick-pill {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s;
            cursor: pointer;
        }
        .quick-pill:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-color: #e2e8f0;
        }
        .quick-pill-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .quick-pill-icon {
            color: #3b82f6;
            font-size: 1.1rem;
        }
        .quick-pill-arrow {
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-icon">🎓</div>
            <div class="brand-text">
                <span class="brand-title">AdvisorHub</span>
                <span class="brand-subtitle">Student Portal</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <span class="nav-icon">🏠</span>
                Dashboard
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">👤</span>
                My Advisor
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">✉️</span>
                Messages
                <span class="badge">3</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">🔔</span>
                Notifications
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📅</span>
                Appointments
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">👤</span>
                My Profile
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📄</span>
                Documents
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📚</span>
                Resources
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">🗓️</span>
                Calendar
            </a>
            <a href="#" class="nav-item" style="margin-top: 12px;">
                <span class="nav-icon">❓</span>
                Help & Support
            </a>
            <a href="index.php?action=logout" class="nav-item">
                <span class="nav-icon">↪️</span>
                Logout
            </a>
        </nav>

        <div class="help-card">
            <div class="help-card-icon">👨‍🎓</div>
            <div class="help-card-title">Need Help?</div>
            <div class="help-card-text">Contact your advisor or registrar for any support.</div>
            <button class="btn-support">Get Support</button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="topbar">
            <div class="topbar-left">
                <span class="menu-toggle">☰</span>
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search anything...">
                </div>
            </div>
            <div class="topbar-right">
                <div class="notification-bell">
                    🔔
                    <span class="badge">5</span>
                </div>
                <div class="user-profile">
                    <!-- Placeholder avatar -->
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=e2e8f0&color=334155" alt="Avatar" class="avatar">
                    <div class="user-info">
                        <span class="user-name">Hi, <?php echo htmlspecialchars($firstName); ?></span>
                        <span class="user-role">Student</span>
                    </div>
                    <span class="dropdown-icon">⌄</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <!-- Welcome -->
            <div class="welcome-section">
                <div>
                    <h1 class="welcome-title">Welcome back, <?php echo htmlspecialchars($firstName); ?>! 👋</h1>
                    <p class="welcome-subtitle">Here's what's happening with your academic journey.</p>
                </div>
                <div class="date-picker">
                    <span class="date-picker-icon">📅</span>
                    <?php echo date('M d, Y | l'); ?>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon-box blue">💬</div>
                    <div class="metric-info">
                        <div class="metric-value">3</div>
                        <div class="metric-label">Unread Messages</div>
                        <a href="#" class="metric-link">View Messages →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box green">🔔</div>
                    <div class="metric-info">
                        <div class="metric-value">5</div>
                        <div class="metric-label">Notifications</div>
                        <a href="#" class="metric-link">View All →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box orange">📅</div>
                    <div class="metric-info">
                        <div class="metric-value">1</div>
                        <div class="metric-label">Upcoming Appointment</div>
                        <a href="#" class="metric-link">View Appointment →</a>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon-box purple">📁</div>
                    <div class="metric-info">
                        <div class="metric-value">2</div>
                        <div class="metric-label">Documents</div>
                        <a href="#" class="metric-link">View Documents →</a>
                    </div>
                </div>
            </div>

            <!-- Content Grid 2x2 -->
            <div class="content-grid">
                <!-- My Advisor -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Advisor</h3>
                        <span class="card-action">•••</span>
                    </div>
                    <?php if ($advisor): ?>
                    <div class="advisor-profile">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($advisor['advisor_name']); ?>&background=1e293b&color=ffffff" alt="Advisor" class="advisor-avatar">
                        <div class="advisor-details">
                            <div class="advisor-name">Dr. <?php echo htmlspecialchars($advisor['advisor_name']); ?></div>
                            <div class="advisor-dept">Computer Science Department</div>
                            <div class="advisor-contact">
                                <span class="advisor-contact-icon">✉️</span> <?php echo htmlspecialchars($advisor['advisor_email']); ?>
                            </div>
                            <div class="advisor-contact">
                                <span class="advisor-contact-icon">📞</span> +251 91 234 5678
                            </div>
                            <button class="btn-outline">Send Message</button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="padding: 24px; text-align: center; color: #64748b;">
                        You have not been assigned an advisor yet.
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Messages -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Messages</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    
                    <?php 
                    // Mock some recent messages if database is empty for visual matching, 
                    // otherwise use real ones but format them like the mockup.
                    $messagesToShow = array_slice($data['messages'], 0, 3);
                    if (empty($messagesToShow)): 
                    ?>
                    <!-- Mock Data for visual matching -->
                    <div class="list-item">
                        <img src="https://ui-avatars.com/api/?name=Michael+Tesfaye&background=1e293b&color=ffffff" class="list-avatar">
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Dr. Michael Tesfaye</span>
                                <span class="list-time">10:30 AM</span>
                            </div>
                            <div class="list-desc">Please review the attached document and let me know...</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box purple">R</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Registrar Office</span>
                                <span class="list-time">Yesterday</span>
                            </div>
                            <div class="list-desc">Your course registration for the next semester is now open.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box green">AO</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Academic Office</span>
                                <span class="list-time">May 23</span>
                            </div>
                            <div class="list-desc">Important notice: Final exam schedule has been published.</div>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php foreach($messagesToShow as $msg): 
                            $senderInitial = strtoupper(substr($msg['sender_name'], 0, 1));
                        ?>
                        <div class="list-item">
                            <div class="list-icon-box" style="background:#e2e8f0; color:#334155;"><?php echo $senderInitial; ?></div>
                            <div class="list-content">
                                <div class="list-header">
                                    <span class="list-title"><?php echo htmlspecialchars($msg['sender_name']); ?></span>
                                    <span class="list-time"><?php echo date('M d', strtotime($msg['sent_at'])); ?></span>
                                </div>
                                <div class="list-desc"><?php echo htmlspecialchars(substr($msg['message'], 0, 60)) . '...'; ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Upcoming Appointment -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Appointment</h3>
                    </div>
                    <div class="appointment-box">
                        <div class="appointment-icon">📅</div>
                        <div class="appointment-info">
                            <h4>Meeting with <?php echo $advisor ? 'Dr. ' . htmlspecialchars($advisor['advisor_name']) : 'Dr. Michael Tesfaye'; ?></h4>
                            <p>Academic advising session</p>
                            <div class="appointment-detail">
                                <span>📅</span> May 28, 2025 (Wednesday)
                            </div>
                            <div class="appointment-detail">
                                <span>🕒</span> 10:00 AM
                            </div>
                            <div class="appointment-detail">
                                <span>📍</span> Online (Google Meet)
                            </div>
                        </div>
                    </div>
                    <button class="btn-outline" style="width: 100%; margin-top:0;">View All Appointments</button>
                </div>

                <!-- Important Notifications -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Important Notifications</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box purple">📢</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Course registration is now open</span>
                                <span class="list-time">May 24, 2025</span>
                            </div>
                            <div class="list-desc">Register your courses for the upcoming semester.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box green">🏛️</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Library maintenance</span>
                                <span class="list-time">May 22, 2025</span>
                            </div>
                            <div class="list-desc">The central library will be closed on May 30 for maintenance.</div>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="list-icon-box orange">📄</div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title">Scholarship application</span>
                                <span class="list-time">May 21, 2025</span>
                            </div>
                            <div class="list-desc">Apply for the merit-based scholarship before June 15.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access -->
            <div>
                <h4 style="font-size:0.95rem; font-weight:700; margin-bottom:16px; color:#0f172a;">Quick Access</h4>
                <div class="quick-access">
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon">📖</span> My Courses
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon">📋</span> Assignments
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon">📊</span> Grades
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon">📅</span> Academic Calendar
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                    <div class="quick-pill">
                        <div class="quick-pill-left">
                            <span class="quick-pill-icon">📁</span> Resources
                        </div>
                        <span class="quick-pill-arrow">›</span>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

</body>
</html>
