<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card">
    <div class="auth-brand">
        <div class="brand-icon">🎓</div>
        <span class="brand-name">AdvisorHub</span>
    </div>

    <h2 class="auth-title">Create an account</h2>
    <p class="auth-subtitle">Join the AAU university advisory system</p>

    <form action="index.php?action=register" method="POST" class="auth-form">
        <div class="form-group form-floating">
            <input type="text" name="name" id="name" placeholder="Full Name" required>
            <label for="name">Full Name</label>
            <span class="field-status"></span>
        </div>

        <div class="form-group form-floating">
            <input type="email" name="email" id="email" placeholder="University Email" required>
            <label for="email">University Email</label>
            <span class="field-status"></span>
        </div>

        <div class="form-row">
            <div class="form-group form-floating">
                <input type="text" name="student_id" id="student_id" placeholder="Student ID" required>
                <label for="student_id">Student ID</label>
                <span class="field-status"></span>
            </div>
            <div class="form-group form-floating">
                <input type="text" name="program" id="program" placeholder="Program or Department">
                <label for="program">Program</label>
                <span class="field-status"></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-floating">
                <input type="number" name="year" id="year" placeholder="Year" min="1" max="8">
                <label for="year">Year</label>
                <span class="field-status"></span>
            </div>
            <div class="form-group form-floating">
                <input type="text" name="phone" id="phone" placeholder="Phone Number">
                <label for="phone">Phone</label>
                <span class="field-status"></span>
            </div>
        </div>

        <input type="hidden" name="role" value="student">

        <button type="submit" class="btn btn-primary">Create Account <span class="btn-icon">→</span></button>

        <p class="auth-link">Already have an account? <a href="index.php?action=login">Login here</a></p>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
