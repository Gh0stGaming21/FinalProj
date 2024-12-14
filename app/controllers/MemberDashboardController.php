<?php
class MemberDashboardController {
        private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRecentActivities() {
        $stmt = $this->pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>