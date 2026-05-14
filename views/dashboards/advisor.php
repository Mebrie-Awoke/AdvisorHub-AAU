<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h1>Advisor Dashboard</h1>
    <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>! Manage your students here.</p>
</div>

<div class="content-grid">

    <div class="feature-card">
        <div class="feature-card-icon">🎓</div>
        <h3>My Students</h3>
        <p>View and manage the full list of students assigned to you by the registrar.</p>
        <span class="badge-coming">⏳ Coming in Phase 3</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">💬</div>
        <h3>Student Messages</h3>
        <p>Read and respond to incoming messages from your assigned students.</p>
        <span class="badge-coming">⏳ Coming in Phase 4</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">📅</div>
        <h3>Appointments</h3>
        <p>Review and approve appointment requests submitted by your students.</p>
        <span class="badge-coming">⏳ Coming in Phase 4</span>
    </div>

    <div class="feature-card">
        <div class="feature-card-icon">📢</div>
        <h3>Broadcast Notifications</h3>
        <p>Send announcements and important updates to all your assigned students.</p>
        <span class="badge-coming">⏳ Coming in Phase 5</span>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
