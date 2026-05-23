<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card" style="position: relative; overflow: hidden; border-radius: var(--radius-lg); padding: 3rem 2.5rem; background: var(--bg-surface); backdrop-filter: blur(20px); border: 1px solid var(--border-soft); box-shadow: var(--shadow-md);">
    <div style="position: absolute; top: -10%; right: -10%; width: 140px; height: 140px; background: rgba(99, 102, 241, 0.15); filter: blur(40px); border-radius: 50%; pointer-events: none;"></div>

    <div class="auth-brand" style="margin-bottom: 2rem;">
        <div class="brand-icon" style="background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);">🎓</div>
        <span class="brand-name" style="font-family: var(--font-heading); font-weight: 800; font-size: 1.5rem; background: linear-gradient(135deg, #ffffff 40%, #a5b4fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AdvisorHub</span>
    </div>

    <h2 class="auth-title" style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 0.5rem; color: #fff;">Welcome back</h2>
    <p class="auth-subtitle" style="color: var(--text-secondary); margin-bottom: 2.5rem; font-size: 0.95rem;">Sign in to your Addis Ababa University account</p>

    <form action="index.php?action=login" method="POST" class="auth-form" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="form-group form-floating">
            <input type="email" name="email" id="email" placeholder="Email Address" required style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
            <label for="email" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Email Address</label>
        </div>

        <div class="form-group form-floating password-field" style="position: relative;">
            <input type="password" name="password" id="password" placeholder="Password" required style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
            <label for="password" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Password</label>
            <button type="button" class="password-toggle" data-password-target="#password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--primary-light); font-weight: 700; font-size: 0.8rem; cursor: pointer;">Show</button>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 1rem; font-size: 1.05rem; font-weight: 700; border-radius: var(--radius-sm); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-end) 100%); color: #fff; box-shadow: 0 10px 25px rgba(102, 126, 234, 0.35); transition: all 0.25s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: none; cursor: pointer;">
            Sign In <span class="btn-icon">&rarr;</span>
        </button>

        <p class="auth-link" style="text-align: center; color: var(--text-secondary); margin-top: 1rem; font-size: 0.9rem;">
            Don't have an account? <a href="index.php?action=register" style="color: var(--primary-light); font-weight: 700; text-decoration: none;">Register here</a>
        </p>
    </form>

    <!-- Hardcoded credentials callout block -->
    <div style="margin-top: 2.5rem; text-align: center; padding: 1.25rem; background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: var(--radius-sm);">
        <span style="font-size: 0.75rem; font-weight: 700; color: #9ca5ff; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem; font-family: var(--font-heading);">🔑 HARDCODED REGISTRAR ACCESS</span>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">
            Email: <code style="color: #ffffff; background: rgba(0,0,0,0.4); padding: 0.25rem 0.5rem; border-radius: 6px; font-family: monospace; font-size: 0.9em;">registrar@aau.edu.et</code><br>
            Password: <code style="color: #ffffff; background: rgba(0,0,0,0.4); padding: 0.25rem 0.5rem; border-radius: 6px; font-family: monospace; font-size: 0.9em;">password</code>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Label shift mechanics for floating labels
    const inputs = document.querySelectorAll('.form-floating input');
    inputs.forEach(input => {
        const checkValue = () => {
            const label = input.nextElementSibling;
            if (input.value.trim() !== '') {
                label.style.transform = 'translateY(-10px)';
                label.style.fontSize = '0.78rem';
                label.style.color = 'var(--primary-light)';
            } else {
                label.style.transform = 'none';
                label.style.fontSize = '0.95rem';
                label.style.color = 'var(--text-secondary)';
            }
        };

        input.addEventListener('focus', () => {
            const label = input.nextElementSibling;
            label.style.transform = 'translateY(-10px)';
            label.style.fontSize = '0.78rem';
            label.style.color = 'var(--primary-light)';
        });

        input.addEventListener('blur', checkValue);
        input.addEventListener('input', checkValue);
        
        // Initial run
        checkValue();
    });

    // Password toggle toggle behavior
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', () => {
            const targetId = passwordToggle.getAttribute('data-password-target');
            const target = document.querySelector(targetId);
            if (target.type === 'password') {
                target.type = 'text';
                passwordToggle.textContent = 'Hide';
            } else {
                target.type = 'password';
                passwordToggle.textContent = 'Show';
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
