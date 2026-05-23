<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdvisorHub – Connect. Communicate. Succeed.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/landing.css">
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container navbar-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🎓</span>
                <div class="logo-text">
                    <strong>AdvisorHub</strong>
                    <span>Connect. Communicate. Succeed.</span>
                </div>
            </a>
            <nav class="nav-links">
                <a href="#home" class="active">Home</a>
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="nav-auth">
                <a href="index.php?action=login" class="btn btn-outline">Login</a>
                <a href="index.php?action=register" class="btn btn-primary">Register</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1>Welcome to<br><span>AdvisorHub</span></h1>
                <h2>Your academic journey, guided by the right advisor.</h2>
                <p>AdvisorHub is a smart university support system that connects students, advisors, and registrars in one seamless platform.</p>
                <div class="hero-actions">
                    <a href="index.php?action=register" class="btn btn-primary">Get Started &rarr;</a>
                    <a href="#features" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="public/img/hero.png" alt="Students and advisor discussing around a laptop">
            </div>
        </div>
    </section>

    <!-- Floating Highlights Bar -->
    <div class="container highlights-container">
        <div class="highlights-bar">
            <div class="highlight">
                <div class="h-icon shield">🛡️</div>
                <div class="h-text">
                    <strong>Secure & Reliable</strong>
                    <span>Your data is safe with us.</span>
                </div>
            </div>
            <div class="highlight">
                <div class="h-icon chat">💬</div>
                <div class="h-text">
                    <strong>Easy Communication</strong>
                    <span>Connect with your advisor in real time.</span>
                </div>
            </div>
            <div class="highlight">
                <div class="h-icon bell">🔔</div>
                <div class="h-text">
                    <strong>Instant Notifications</strong>
                    <span>Never miss important updates.</span>
                </div>
            </div>
            <div class="highlight">
                <div class="h-icon chart">📊</div>
                <div class="h-text">
                    <strong>Track & Manage</strong>
                    <span>Stay on top of your academic progress.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="features section">
        <div class="container">
            <div class="section-header text-center">
                <span class="eyebrow">FEATURES</span>
                <h2>Everything you need in one place</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="f-icon users">👥</div>
                    <h3>Advisor Assignment</h3>
                    <p>Students are assigned the right advisor by the registrar.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon message">💬</div>
                    <h3>Messaging System</h3>
                    <p>Chat securely with your advisor anytime, anywhere.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon bell">🔔</div>
                    <h3>Notifications</h3>
                    <p>Receive important announcements and updates.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon calendar">📅</div>
                    <h3>Appointments</h3>
                    <p>Book appointments and manage your meetings.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon dashboard">📱</div>
                    <h3>Dashboard</h3>
                    <p>Role-based dashboards for students, advisors and registrars.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon check">🛡️</div>
                    <h3>Accountability</h3>
                    <p>Clear communication and activity tracking for transparency.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works section">
        <div class="container">
            <div class="section-header text-center">
                <span class="eyebrow">HOW IT WORKS</span>
                <h2>Simple steps to get started</h2>
            </div>
            <div class="steps-container">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-icon">👤+</div>
                    <h3>Register / Login</h3>
                    <p>Create your account or login with your credentials.</p>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-icon">👥</div>
                    <h3>Connect with Advisor</h3>
                    <p>Get assigned to an advisor and start communicating.</p>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-icon">✅</div>
                    <h3>Track & Stay Updated</h3>
                    <p>Manage appointments, messages and important updates.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section id="about" class="roles section bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="eyebrow">WHO USES ADVISORHUB</span>
                <h2>Built for everyone in the academic community</h2>
            </div>
            <div class="roles-grid">
                <div class="role-card role-student">
                    <div class="role-icon">👨‍🎓</div>
                    <div class="role-content">
                        <h3>Students</h3>
                        <p>Connect with advisors, ask questions, receive guidance and track your academic journey.</p>
                        <a href="index.php?action=register" class="learn-more">Learn more &rarr;</a>
                    </div>
                </div>
                <div class="role-card role-advisor">
                    <div class="role-icon">👩‍🏫</div>
                    <div class="role-content">
                        <h3>Advisors</h3>
                        <p>Guide students, send announcements, reply to queries and manage appointments.</p>
                        <a href="index.php?action=register" class="learn-more">Learn more &rarr;</a>
                    </div>
                </div>
                <div class="role-card role-registrar">
                    <div class="role-icon">👨‍💼</div>
                    <div class="role-content">
                        <h3>Registrar</h3>
                        <p>Assign advisors, manage users, monitor activities and ensure smooth workflow.</p>
                        <a href="index.php?action=register" class="learn-more">Learn more &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="container stats-container">
            <div class="stat-item">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <strong>500+</strong>
                    <span>Students</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">👩‍🏫</div>
                <div class="stat-info">
                    <strong>50+</strong>
                    <span>Advisors</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📄</div>
                <div class="stat-info">
                    <strong>1000+</strong>
                    <span>Messages</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <strong>200+</strong>
                    <span>Appointments</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <span class="logo-icon">🎓</span>
                        <strong>AdvisorHub</strong>
                    </a>
                    <p>Empowering students and educators through smart communication and efficient academic support.</p>
                    <div class="socials">
                        <a href="#">📘</a>
                        <a href="#">🐦</a>
                        <a href="#">💼</a>
                        <a href="#">📸</a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <a href="#home">Home</a>
                    <a href="#features">Features</a>
                    <a href="#about">About</a>
                    <a href="#how-it-works">How It Works</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="footer-links">
                    <h4>Resources</h4>
                    <a href="#">Help Center</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                    <a href="#">Guidelines</a>
                </div>
                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <p>✉️ support@advisorhub.edu</p>
                    <p>📞 +234 800 123 4567</p>
                    <p>📍 AAU, University Campus<br>Ekpoma, Edo State, Nigeria.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 AdvisorHub. All rights reserved.</p>
                <p>Made with ❤️ for academic excellence.</p>
            </div>
        </div>
    </footer>
</body>
</html>
