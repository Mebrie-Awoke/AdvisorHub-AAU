<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card">
    <h2>Change temporary password</h2>
    <p>You must change your temporary password before continuing.</p>
    <form action="index.php?action=force_change_password" method="POST">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </div>
        <button class="btn btn-primary" type="submit">Change password</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
