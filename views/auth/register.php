<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">🎓</div>
        <span class="brand-name">AdvisorHub</span>
    </div>

    <h2 class="auth-title">Create an account</h2>
    <p class="auth-subtitle">Join the AAU university advisory system</p>

    <form action="index.php?action=register" method="POST" class="auth-form">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" placeholder="Your full name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="you@university.edu" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Create a secure password" required>
        </div>

        <div class="form-group">
            <label for="role">Select Role</label>
            <select name="role" id="role" required>
                <option value="student">🎓 Student</option>
                <option value="advisor">📋 Advisor</option>
                <option value="registrar">🏛️ Registrar</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Account →</button>

        <p class="auth-link">Already have an account? <a href="index.php?action=login">Login here</a></p>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
