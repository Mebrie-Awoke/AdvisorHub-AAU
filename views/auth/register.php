<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <h2>Register for AdvisorHub</h2>
    <form action="index.php?action=register" method="POST" class="auth-form">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="role">Select Role</label>
            <select name="role" id="role" required>
                <option value="student">Student</option>
                <option value="advisor">Advisor</option>
                <option value="registrar">Registrar</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Register</button>
        <p class="auth-link">Already have an account? <a href="index.php?action=login">Login here</a>.</p>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
