-- SQLite schema for AdvisorHub
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT,
    role TEXT NOT NULL,
    status TEXT DEFAULT 'pending',
    is_approved INTEGER DEFAULT 0,
    approval_token TEXT,
    force_password_change INTEGER DEFAULT 0,
    last_login TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE,
    student_id TEXT NOT NULL UNIQUE,
    full_name TEXT NOT NULL,
    program TEXT,
    year INTEGER,
    phone TEXT,
    university_email TEXT NOT NULL UNIQUE,
    approved_by_registrar_id INTEGER,
    approved_at TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_registrar_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS advisors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER UNIQUE,
    full_name TEXT NOT NULL,
    department TEXT,
    office_location TEXT,
    phone TEXT,
    assigned_by_registrar_id INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    accountability_notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by_registrar_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS assignments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    advisor_id INTEGER NOT NULL,
    assigned_by_registrar_id INTEGER NOT NULL,
    assigned_at TEXT DEFAULT CURRENT_TIMESTAMP,
    is_active INTEGER DEFAULT 1,
    reassigned_from INTEGER,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (advisor_id) REFERENCES advisors(id),
    FOREIGN KEY (assigned_by_registrar_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    advisor_id INTEGER NOT NULL,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    is_urgent INTEGER DEFAULT 0,
    sent_to_all INTEGER DEFAULT 0,
    recipient_ids TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (advisor_id) REFERENCES advisors(id)
);

CREATE TABLE IF NOT EXISTS student_notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    notification_id INTEGER NOT NULL,
    student_id INTEGER NOT NULL,
    is_read INTEGER DEFAULT 0,
    read_at TEXT,
    email_sent INTEGER DEFAULT 0,
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

CREATE TABLE IF NOT EXISTS questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    advisor_id INTEGER NOT NULL,
    subject TEXT,
    question_text TEXT NOT NULL,
    answer_text TEXT,
    status TEXT DEFAULT 'open',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    answered_at TEXT,
    resolved_at TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (advisor_id) REFERENCES advisors(id)
);

CREATE TABLE IF NOT EXISTS activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    user_role TEXT,
    action TEXT NOT NULL,
    details TEXT,
    ip_address TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS password_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token TEXT NOT NULL UNIQUE,
    type TEXT DEFAULT 'setup',
    expires_at TEXT NOT NULL,
    used_at TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
