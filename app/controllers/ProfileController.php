<?php

class ProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProfile($userId) {
        $query = "SELECT id, username, email, FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profile;
    }

}

?>