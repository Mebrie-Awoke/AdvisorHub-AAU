<?php
require_once __DIR__ . '/User.php';

class Student extends User {
    private $studentTable = 'students'; 

    public function __construct($db) {
        parent::__construct($db);
    }
  
    public function findByUserId($user_id) {
        $query = "SELECT * FROM {$this->studentTable} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createProfile($user_id, $student_id, $full_name, $program = null, $year = null, $phone = null, $university_email = null) {
        $query = "INSERT INTO {$this->studentTable} (user_id, student_id, full_name, program, year, phone, university_email) VALUES (:user_id, :student_id, :full_name, :program, :year, :phone, :university_email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':program', $program);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':university_email', $university_email);
        return $stmt->execute();
    }

    public function getAssignedAdvisor($student_id) {
        $query = "SELECT a.* FROM assignments ass JOIN advisors a ON ass.advisor_id = a.id WHERE ass.student_id = :student_id AND ass.is_active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}

