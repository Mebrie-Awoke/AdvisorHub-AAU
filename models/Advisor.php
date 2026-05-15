<?php
require_once __DIR__ . '/User.php';

class Advisor extends User {
    private $advisorTable = 'advisors';
    public $advisor_id;

    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAdvisorData($user_id) {
        $query = "SELECT u.id, u.name, u.email, a.id as advisor_id 
                  FROM users u 
                  JOIN " . $this->advisorTable . " a ON u.id = a.user_id 
                  WHERE u.id = :user_id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
