<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="dashboard-container">
    <h2>Registrar Dashboard</h2>
    <div class="dashboard-content">
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        
        <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Student-Advisor Assignment</h3>
                <p>Assign students to their respective academic advisors.</p>
                <button class="btn btn-primary" style="margin-top: 10px;" disabled>Coming in Phase 3</button>
            </div>
            
            <div class="card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <h3>Manage Users</h3>
                <p>View all registered students and advisors.</p>
                <button class="btn btn-primary" style="margin-top: 10px;" disabled>Coming in Phase 3</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
