<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">🎓</div>
        <span class="brand-name">AdvisorHub</span>
    </div>

    <h2 class="auth-title">Welcome back</h2>
    <p class="auth-subtitle">Sign in to your university account</p>

    <form action="index.php?action=login" method="POST" class="auth-form">
        <div class="form-group form-floating">
            <input type="email" name="email" id="email" placeholder="Email Address" required>
            <label for="email">Email Address</label>
            <span class="field-status"></span>
        </div>

        <div class="form-group form-floating password-field">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
            <button type="button" class="password-toggle" data-password-target="#password">Show</button>
            <span class="field-status"></span>
        </div>

        <button type="submit" class="btn btn-primary">Sign In <span class="btn-icon">→</span></button>

        <p class="auth-link">Don't have an account? <a href="index.php?action=register">Register here</a></p>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
