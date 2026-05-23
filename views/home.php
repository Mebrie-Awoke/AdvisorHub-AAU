<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="home-hero">
    <div class="hero-panel glass-panel">
        <div class="hero-tag">AAU Advisory System</div>
        <h1 class="hero-title">AdvisorHub</h1>
        <p class="hero-copy">A modern student-advisor support portal designed for easy registration, responsive advising, and campus-wide collaboration.</p>

        <div class="home-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=dashboard" class="btn btn-primary">Go to Dashboard</a>
            <?php else: ?>
                <a href="index.php?action=login" class="btn btn-primary">Login</a>
                <a href="index.php?action=register" class="btn btn-secondary">Register</a>
            <?php endif; ?>
        </div>

        <div class="hero-stats">
            <div class="stat-card">
                <span class="stat-value">3</span>
                <span class="stat-label">User Roles</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">100%</span>
                <span class="stat-label">Mobile Friendly</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">24/7</span>
                <span class="stat-label">Support Ready</span>
            </div>
        </div>
    </div>

    <div class="home-grid">
        <article class="feature-card">
            <h3>Students</h3>
            <p>Ask your advisor, view assignments, and receive university notifications in a modern dashboard.</p>
        </article>
        <article class="feature-card">
            <h3>Advisors</h3>
            <p>Manage student requests, send updates, and resolve questions with an elegant workflow.</p>
        </article>
        <article class="feature-card">
            <h3>Registrar</h3>
            <p>Approve accounts, assign advisors, and review activity using a polished control center.</p>
        </article>
    </div>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
