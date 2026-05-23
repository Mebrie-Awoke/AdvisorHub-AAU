const root = document.documentElement;
const body = document.body;
const themeToggle = document.getElementById('themeToggle');
const mobileToggle = document.getElementById('mobileMenuToggle');
const navbarMenu = document.getElementById('navbarMenu');
const userDropdownToggle = document.getElementById('userDropdownToggle');
const userDropdownMenu = document.getElementById('userDropdownMenu');

function setTheme(theme) {
    if (theme === 'light') {
        body.classList.add('light-theme');
        body.classList.remove('dark-theme');
        localStorage.setItem('advisorhub-theme', 'light');
        if (themeToggle) themeToggle.innerHTML = '<span class="icon">☀️</span>';
    } else {
        body.classList.remove('light-theme');
        body.classList.add('dark-theme');
        localStorage.setItem('advisorhub-theme', 'dark');
        if (themeToggle) themeToggle.innerHTML = '<span class="icon">🌙</span>';
    }
}

const savedTheme = localStorage.getItem('advisorhub-theme') || 'dark';
setTheme(savedTheme);

themeToggle?.addEventListener('click', () => {
    const next = body.classList.contains('light-theme') ? 'dark' : 'light';
    setTheme(next);
});

mobileToggle?.addEventListener('click', () => {
    navbarMenu?.classList.toggle('open');
});

userDropdownToggle?.addEventListener('click', () => {
    const parent = userDropdownToggle.closest('.user-dropdown');
    parent?.classList.toggle('open');
});

window.addEventListener('click', (event) => {
    if (!event.target.closest('.user-dropdown')) {
        document.querySelectorAll('.user-dropdown.open').forEach(drop => drop.classList.remove('open'));
    }
});

document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', () => {
        const targetSelector = button.dataset.passwordTarget;
        const target = document.querySelector(targetSelector);
        if (!target) return;
        const isPassword = target.getAttribute('type') === 'password';
        target.setAttribute('type', isPassword ? 'text' : 'password');
        button.textContent = isPassword ? 'Hide' : 'Show';
    });
});

document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function (event) {
        const circle = document.createElement('span');
        const diameter = Math.max(this.clientWidth, this.clientHeight);
        const radius = diameter / 2;
        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - this.getBoundingClientRect().left - radius}px`;
        circle.style.top = `${event.clientY - this.getBoundingClientRect().top - radius}px`;
        circle.classList.add('ripple');
        const ripple = this.getElementsByClassName('ripple')[0];
        if (ripple) ripple.remove();
        this.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
    });
});

// Optional toast auto-dismiss for server alerts
setTimeout(() => {
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
}, 5000);
