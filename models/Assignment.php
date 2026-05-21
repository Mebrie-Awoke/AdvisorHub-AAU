<?php

class Assignment {
    private $conn;
    private $table = 'assignments';

    public $id;
    public $student_id;
    public $advisor_id;
    public $assigned_by_registrar_id;
    public $assigned_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function assignStudent($student_id, $advisor_id, $assigned_by) {
        // deactivate existing active assignment for this student
        $deact = $this->conn->prepare("UPDATE {$this->table} SET is_active = 0, reassigned_from = advisor_id WHERE student_id = :student_id AND is_active = 1");
        $deact->bindParam(':student_id', $student_id);
        $deact->execute();

        $query = "INSERT INTO {$this->table} (student_id, advisor_id, assigned_by_registrar_id) VALUES (:student_id, :advisor_id, :assigned_by)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->bindParam(':assigned_by', $assigned_by);
        return $stmt->execute();
    }

    public function getAllAssignments() {
        $query = "SELECT ass.id, s.full_name as student_name, adv.full_name as advisor_name, ass.assigned_at FROM {$this->table} ass JOIN students s ON ass.student_id = s.id JOIN advisors adv ON ass.advisor_id = adv.id WHERE ass.is_active = 1 ORDER BY ass.assigned_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

