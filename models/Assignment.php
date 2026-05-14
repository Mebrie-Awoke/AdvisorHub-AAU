<?php

class Assignment {
    private $conn;
    private $table = 'advisor_assignments';

    public $id;
    public $student_id;
    public $advisor_id;
    public $assigned_by;
    public $assigned_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function assignStudent() {
        $query = 'INSERT INTO ' . $this->table . ' (student_id, advisor_id, assigned_by) 
                  VALUES (:student_id, :advisor_id, :assigned_by)
                  ON DUPLICATE KEY UPDATE advisor_id = :advisor_id, assigned_by = :assigned_by';

        $stmt = $this->conn->prepare($query);

        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->advisor_id = htmlspecialchars(strip_tags($this->advisor_id));
        $this->assigned_by = htmlspecialchars(strip_tags($this->assigned_by));

        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':advisor_id', $this->advisor_id);
        $stmt->bindParam(':assigned_by', $this->assigned_by);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllAssignments() {
        $query = 'SELECT a.id, s.name as student_name, adv.name as advisor_name, a.assigned_at 
                  FROM ' . $this->table . ' a
                  JOIN users s ON a.student_id = s.id
                  JOIN users adv ON a.advisor_id = adv.id
                  WHERE a.is_active = 1
                  ORDER BY a.assigned_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
