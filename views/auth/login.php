<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <h2>Login to AdvisorHub</h2>
    <form action="index.php?action=login" method="POST" class="auth-form">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
        <p class="auth-link">Don't have an account? <a href="index.php?action=register">Register here</a>.</p>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
