<?php
require_once __DIR__ . '/User.php';

class Student extends User {
    private $studentTable = 'students';
    public $student_id;

    public function __construct($db) {
        parent::__construct($db);
    }

    public function getStudentData($user_id) {
        $query = "SELECT u.id, u.name, u.email, s.id as student_id 
                  FROM users u 
                  JOIN " . $this->studentTable . " s ON u.id = s.user_id 
                  WHERE u.id = :user_id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
