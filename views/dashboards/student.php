<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h1>Student Dashboard</h1>
    <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>! Here's your overview.</p>
</div>

<div class="content-grid">

    <div class="feature-card">
        <div class="feature-card-icon">👤</div>
        <h3>My Advisor</h3>
        <p>View your assigned academic advisor and their contact information.</p>
        <span class="badge-coming">⏳ Coming in Phase 3</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">💬</div>
        <h3>Messages</h3>
        <p>Send and receive messages directly with your assigned advisor.</p>
        <span class="badge-coming">⏳ Coming in Phase 4</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">📅</div>
        <h3>Appointments</h3>
        <p>Schedule and manage meetings with your academic advisor.</p>
        <span class="badge-coming">⏳ Coming in Phase 4</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">🔔</div>
        <h3>Notifications</h3>
        <p>Stay informed with announcements and updates from your advisor.</p>
        <span class="badge-coming">⏳ Coming in Phase 5</span>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
