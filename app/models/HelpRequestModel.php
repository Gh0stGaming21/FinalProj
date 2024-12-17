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

    public function updateRequestStatus($requestId, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE help_requests
            SET status = :status
            WHERE id = :request_id
        ");
        $stmt->execute([
            ':status' => $status,
            ':request_id' => $requestId,
        ]);
    }

    public function getRequestsByStatus($status) {
        $stmt = $this->pdo->prepare("
            SELECT hr.id, hr.category, hr.description, hr.status, hr.created_at, u.name AS user_name
            FROM help_requests hr
            JOIN users u ON hr.user_id = u.id
            WHERE hr.status = :status
            ORDER BY hr.created_at DESC
        ");
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}