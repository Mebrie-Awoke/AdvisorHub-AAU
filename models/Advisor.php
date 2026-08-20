<?php
require_once __DIR__ . '/User.php';

class Advisor extends User {  
    private $advisorTable = 'advisors';

    public function __construct($db) {
        parent::__construct($db);
    }

    public function findByUserId($user_id) {
        $query = "SELECT * FROM {$this->advisorTable} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createProfile($user_id, $full_name, $department = null, $office_location = null, $phone = null, $assigned_by = null) {
        $query = "INSERT INTO {$this->advisorTable} (user_id, full_name, department, office_location, phone, assigned_by_registrar_id) VALUES (:user_id, :full_name, :department, :office_location, :phone, :assigned_by)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':office_location', $office_location);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':assigned_by', $assigned_by);
        return $stmt->execute();
    }

    public function getAssignedStudents($advisor_id) {
        $query = "SELECT s.* FROM assignments ass JOIN students s ON ass.student_id = s.id WHERE ass.advisor_id = :advisor_id AND ass.is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

