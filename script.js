const navToggle = document.getElementById('navToggle');
const siteNav = document.getElementById('siteNav');

navToggle.addEventListener('click', () => {
    siteNav.classList.toggle('open');
    navToggle.classList.toggle('active');
});

window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        siteNav.classList.remove('open');
        navToggle.classList.remove('active');
    }
});

const navLinks = document.querySelectorAll('.site-nav a[href^="#"]');
navLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
        if (link.hash) {
            event.preventDefault();
            const target = document.querySelector(link.hash);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            siteNav.classList.remove('open');
            navToggle.classList.remove('active');
        }
    });
});
