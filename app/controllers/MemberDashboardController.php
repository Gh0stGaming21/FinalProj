<?php
class MemberDashboardController {
        private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRecentActivities($memberId) {
        $stmt = $this->pdo->prepare("SELECT * FROM activities WHERE member_id = :id ORDER BY created_at DESC LIMIT 10");
        $stmt->bindParam(':id', $memberId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>