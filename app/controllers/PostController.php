<?php
class PostController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTextPost($userId, $postText) {
        try {
            $query = "INSERT INTO posts (post_text, post_type, user_id, created_at) VALUES (:post_text, 'text', :user_id, NOW())";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':post_text', $postText);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error creating text post: " . $e->getMessage());
        }
    }
}
?>