<?php

class User {
    private $conn;
    private $table = 'users';

    public $id; 
    public $email;
    public $password; // plaintext for input purposes only
    public $password_hash;
    public $role;
    public $status;
    public $student_number;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        try {
            $this->conn->beginTransaction();

            $query = 'INSERT INTO ' . $this->table . ' SET name = :name, email = :email, password = :password, role = :role, status = :status, student_number = :student_number';
            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->role = htmlspecialchars(strip_tags($this->role));
            $this->status = htmlspecialchars(strip_tags($this->status));
            $this->student_number = htmlspecialchars(strip_tags($this->student_number));

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':student_number', $this->student_number);

            $stmt->execute();
            $newUserId = $this->conn->lastInsertId();

            if ($this->role === 'student') {
                $roleQuery = 'INSERT INTO students SET id = :id, user_id = :user_id';
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bindParam(':id', $newUserId);
                $roleStmt->bindParam(':user_id', $newUserId);
                $roleStmt->execute();
            } elseif ($this->role === 'advisor') {
                $roleQuery = 'INSERT INTO advisors SET id = :id, user_id = :user_id';
                $roleStmt = $this->conn->prepare($roleQuery);
                $roleStmt->bindParam(':id', $newUserId);
                $roleStmt->bindParam(':user_id', $newUserId);
                $roleStmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function login() {
        $query = 'SELECT id, name, email, password, role, status FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->role = $row['role'];
                $this->status = $row['status'];
                return true;
            }
        }

        return false;
    }
    
    public function emailExists() {
        $query = 'SELECT id FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    // --- CRUD Methods for Registrar ---

    public function approveStudent($id) {
        $query = 'UPDATE ' . $this->table . ' SET status = "approved" WHERE id = :id AND role = "student"';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function rejectStudent($id) {
        $query = 'UPDATE ' . $this->table . ' SET status = "rejected" WHERE id = :id AND role = "student"';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function createAdvisor() {
        try {
            $this->conn->beginTransaction();

            $query = 'INSERT INTO ' . $this->table . ' SET name = :name, email = :email, password = :password, role = "advisor", status = "approved"';
            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);

            $stmt->execute();
            $newUserId = $this->conn->lastInsertId();

            $roleQuery = 'INSERT INTO advisors SET id = :id, user_id = :user_id';
            $roleStmt = $this->conn->prepare($roleQuery);
            $roleStmt->bindParam(':id', $newUserId);
            $roleStmt->bindParam(':user_id', $newUserId);
            $roleStmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAllUsers($search = '', $role_filter = '') {
        $query = 'SELECT id, name, email, role, status, student_number, created_at FROM ' . $this->table . ' WHERE 1=1';

        if (!empty($search)) {
            $query .= ' AND (name LIKE :search OR email LIKE :search)';
        }

        if (!empty($role_filter)) {
            $query .= ' AND role = :role_filter';
        }

        $query .= ' ORDER BY created_at DESC';
        
        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }

        if (!empty($role_filter)) {
            $stmt->bindParam(':role_filter', $role_filter);
        }

        $stmt->execute();
        return $stmt;
    }

    public function deleteUser($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserById($id) {
        $query = 'SELECT id, name, email, role, status, student_number FROM ' . $this->table . ' WHERE id = :id LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user || empty($user['password_hash'])) return false;
        if (password_verify($password, $user['password_hash'])) {
            // update last_login
            $update = $this->conn->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id");
            $update->bindParam(':id', $user['id']);
            $update->execute();
            return $user;
        }
        return false;
    }

    public function setPasswordHash($user_id, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET password_hash = :hash, force_password_change = 0 WHERE id = :id");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function setApprovalToken($user_id, $token) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET approval_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function approveUser($user_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = 'approved', is_approved = 1, approval_token = NULL WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function getPendingStudents() {
        $query = "SELECT u.* , s.* FROM users u JOIN students s ON u.id = s.user_id WHERE u.status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}

