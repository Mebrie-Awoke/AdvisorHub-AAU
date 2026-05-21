-- AdvisorHub production-ready schema
DROP DATABASE IF EXISTS advisorhub;
CREATE DATABASE advisorhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE advisorhub;

-- Users table (authentication for all roles)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),
    role ENUM('student', 'advisor', 'registrar') NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_approved BOOLEAN DEFAULT FALSE,
    approval_token VARCHAR(64),
    force_password_change BOOLEAN DEFAULT FALSE,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    program VARCHAR(100),
    year INT,
    phone VARCHAR(20),
    university_email VARCHAR(255) UNIQUE NOT NULL,
    approved_by_registrar_id INT,
    approved_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_registrar_id) REFERENCES users(id)
);

-- Advisors table
CREATE TABLE IF NOT EXISTS advisors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    office_location VARCHAR(100),
    phone VARCHAR(20),
    assigned_by_registrar_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accountability_notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by_registrar_id) REFERENCES users(id)
);

-- Assignments table
CREATE TABLE IF NOT EXISTS assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    advisor_id INT NOT NULL,
    assigned_by_registrar_id INT NOT NULL,
    assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    reassigned_from INT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (advisor_id) REFERENCES advisors(id),
    FOREIGN KEY (assigned_by_registrar_id) REFERENCES users(id)
);

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    advisor_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_urgent BOOLEAN DEFAULT FALSE,
    sent_to_all BOOLEAN DEFAULT FALSE,
    recipient_ids TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (advisor_id) REFERENCES advisors(id)
);

-- Student notifications (read tracking)
CREATE TABLE IF NOT EXISTS student_notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    notification_id INT NOT NULL,
    student_id INT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at DATETIME,
    email_sent BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Questions
CREATE TABLE IF NOT EXISTS questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    advisor_id INT NOT NULL,
    subject VARCHAR(200),
    question_text TEXT NOT NULL,
    answer_text TEXT,
    status ENUM('open', 'answered', 'resolved') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    answered_at DATETIME,
    resolved_at DATETIME,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (advisor_id) REFERENCES advisors(id)
);

-- Activity logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    user_role VARCHAR(20),
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Password tokens
CREATE TABLE IF NOT EXISTS password_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    type ENUM('setup', 'reset') DEFAULT 'setup',
    expires_at DATETIME NOT NULL,
    used_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
