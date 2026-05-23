<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-card" style="position: relative; overflow: hidden; border-radius: var(--radius-lg); padding: 3rem 2.5rem; background: var(--bg-surface); backdrop-filter: blur(20px); border: 1px solid var(--border-soft); box-shadow: var(--shadow-md); max-width: 580px; width: 100%;">
    <div style="position: absolute; top: -10%; right: -10%; width: 140px; height: 140px; background: rgba(99, 102, 241, 0.15); filter: blur(40px); border-radius: 50%; pointer-events: none;"></div>

    <div class="auth-brand" style="margin-bottom: 2rem;">
        <div class="brand-icon" style="background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);">🎓</div>
        <span class="brand-name" style="font-family: var(--font-heading); font-weight: 800; font-size: 1.5rem; background: linear-gradient(135deg, #ffffff 40%, #a5b4fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AdvisorHub</span>
    </div>

    <h2 class="auth-title" style="font-size: 2.25rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 0.5rem; color: #fff;">Create an account</h2>
    <p class="auth-subtitle" style="color: var(--text-secondary); margin-bottom: 2.5rem; font-size: 0.95rem;">Join the AAU university advisory system</p>

    <form action="index.php?action=register" method="POST" class="auth-form" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="form-group form-floating">
            <input type="text" name="name" id="name" placeholder="Full Name" required style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
            <label for="name" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Full Name</label>
        </div>

        <div class="form-group form-floating">
            <input type="email" name="email" id="email" placeholder="University Email" required style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
            <label for="email" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">University Email (@aau.edu.et)</label>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group form-floating">
                <input type="text" name="student_id" id="student_id" placeholder="Student ID" required style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
                <label for="student_id" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Student ID</label>
            </div>
            <div class="form-group form-floating">
                <input type="text" name="program" id="program" placeholder="Program or Department" style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
                <label for="program" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Program / Major</label>
            </div>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group form-floating">
                <input type="number" name="year" id="year" placeholder="Year" min="1" max="8" style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
                <label for="year" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Academic Year</label>
            </div>
            <div class="form-group form-floating">
                <input type="text" name="phone" id="phone" placeholder="Phone Number" style="width: 100%; padding: 1.1rem 1rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border); background: rgba(0,0,0,0.25); color: #fff; outline: none; transition: border-color 0.25s;">
                <label for="phone" style="position: absolute; left: 1rem; top: 0.8rem; color: var(--text-secondary); font-size: 0.95rem; pointer-events: none; transition: all 0.2s;">Phone Number</label>
            </div>
        </div>

        <input type="hidden" name="role" value="student">

        <button type="submit" class="btn btn-primary" style="padding: 1rem; font-size: 1.05rem; font-weight: 700; border-radius: var(--radius-sm); background: linear-gradient(135deg, var(--primary) 0%, var(--primary-end) 100%); color: #fff; box-shadow: 0 10px 25px rgba(102, 126, 234, 0.35); transition: all 0.25s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: none; cursor: pointer; margin-top: 0.5rem;">
            Create Account <span class="btn-icon">&rarr;</span>
        </button>

        <p class="auth-link" style="text-align: center; color: var(--text-secondary); margin-top: 1rem; font-size: 0.9rem;">
            Already have an account? <a href="index.php?action=login" style="color: var(--primary-light); font-weight: 700; text-decoration: none;">Login here</a>
        </p>
    </form>
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
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
