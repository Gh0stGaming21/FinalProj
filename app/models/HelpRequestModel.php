<?php

class HelpRequestModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllHelpRequests() {
        $stmt = $this->pdo->query("
            SELECT hr.id, hr.category, hr.description, hr.status, hr.created_at, u.name AS user_name
            FROM help_requests hr
            JOIN users u ON hr.user_id = u.id
            ORDER BY hr.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createHelpRequest($userId, $category, $description) {
        $stmt = $this->pdo->prepare("
            INSERT INTO help_requests (user_id, category, description, status, created_at)
            VALUES (:user_id, :category, :description, 'open', NOW())
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':category' => $category,
            ':description' => $description,
        ]);
    }
}