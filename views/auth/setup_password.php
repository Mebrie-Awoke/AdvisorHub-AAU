<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">🔐</div>
        <span class="brand-name">AdvisorHub</span>
    </div>

    <h2 class="auth-title">Set your password</h2>
    <p class="auth-subtitle">Enter a secure password to activate your account.</p>

    <form action="index.php?action=setup_password" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
        <div class="form-group form-floating password-field">
            <input type="password" name="password" id="password" placeholder="New Password" required>
            <label for="password">New Password</label>
            <button type="button" class="password-toggle" data-password-target="#password">Show</button>
        </div>
        <div class="form-group form-floating password-field">
            <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirm Password" required>
            <label for="password_confirm">Confirm Password</label>
            <button type="button" class="password-toggle" data-password-target="#password_confirm">Show</button>
        </div>
        <button class="btn btn-primary" type="submit">Set password</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
