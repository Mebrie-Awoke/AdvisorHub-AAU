<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="dashboard-container">
    <h2>Student Dashboard</h2>
    <div class="dashboard-content">
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        
        <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>My Advisor</h3>
                <p>View your assigned advisor and contact information.</p>
                <button class="btn btn-primary" style="margin-top: 10px;" disabled>Coming in Phase 3</button>
            </div>
            
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Messages</h3>
                <p>Communicate directly with your assigned advisor.</p>
                <button class="btn btn-primary" style="margin-top: 10px;" disabled>Coming in Phase 4</button>
            </div>

            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Notifications</h3>
                <p>View announcements and notifications from your advisor.</p>
                <button class="btn btn-primary" style="margin-top: 10px;" disabled>Coming in Phase 5</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
