<?php
class ResourceSharingController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function getResourceSharingData() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM resources ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching resource sharing data: " . $e->getMessage());
            return [];
        }
    }
}
?>