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

    public function deletePost($postId, $userId) {
        try {
            $query = "DELETE FROM posts WHERE id = :post_id AND user_id = :user_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':post_id', $postId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error deleting post: " . $e->getMessage());
        }
    }
}
?>