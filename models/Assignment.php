<?php

class Assignment {
    private $conn;
    private $table = 'assignments';

    public $id;
    public $student_id;
    public $advisor_id;
    public $assigned_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function assignStudent() {
        $query = 'INSERT INTO ' . $this->table . ' SET student_id = :student_id, advisor_id = :advisor_id 
                  ON DUPLICATE KEY UPDATE advisor_id = :advisor_id';

        $stmt = $this->conn->prepare($query);

        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->advisor_id = htmlspecialchars(strip_tags($this->advisor_id));

        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':advisor_id', $this->advisor_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllAssignments() {
        $query = 'SELECT a.id, s.name as student_name, adv.name as advisor_name 
                  FROM ' . $this->table . ' a
                  JOIN users s ON a.student_id = s.id
                  JOIN users adv ON a.advisor_id = adv.id
                  ORDER BY a.assigned_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
