<?php

class Question {
    private $conn;
    private $table = 'questions';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($student_id, $advisor_id, $subject, $question_text) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (student_id, advisor_id, subject, question_text) VALUES (:student_id, :advisor_id, :subject, :question_text)");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':question_text', $question_text);
        return $stmt->execute();
    }

    public function getForAdvisor($advisor_id) {
        $stmt = $this->conn->prepare("SELECT q.*, s.full_name as student_name FROM {$this->table} q JOIN students s ON q.student_id = s.id WHERE q.advisor_id = :advisor_id ORDER BY q.created_at DESC");
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
  
    public function answer($question_id, $answer_text) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET answer_text = :answer_text, status = 'answered', answered_at = NOW() WHERE id = :id");
        $stmt->bindParam(':answer_text', $answer_text);
        $stmt->bindParam(':id', $question_id);
        return $stmt->execute();
    }

    public function resolve($question_id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = 'resolved', resolved_at = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $question_id);
        return $stmt->execute();
    }
}
