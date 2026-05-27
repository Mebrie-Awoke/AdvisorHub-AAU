# AdvisorHub – AAU
AdvisorHub is a lightweight university advisor management system for Addis Ababa University (AAU). This repository contains a minimal but production-minded PHP implementation.

Quick setup 
 
1. Create a MySQL database and import schema:

```bash 
mysql -u root -p < config/setup.sql
```

2. Configure environment (e.g., in your webserver or a `.env` loader):

```
DB_HOST=127.0.0.1
DB_NAME=advisorhub
DB_USER=root
DB_PASS=
MAIL_FROM=no-reply@advisorhub.local
```

3. Serve the app (use PHP built-in server for testing):

```bash
php -S localhost:8000 -t .
```

Key features implemented
- Student self-registration (AAU email validated)
- Registrar approval workflow with 48-hour password setup links
- Registrar creates advisor accounts (temporary password emailed)
- Assignments, notifications, questions, activity logs

Next steps
- Replace `config/Mailer.php` with an SMTP-backed solution (PHPMailer)
- Harden CSRF protection and input validation
- Add pagination, searching, and export features

License: MIT
