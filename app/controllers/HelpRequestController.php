<?php
require_once __DIR__ . '/../../config/Database.php';

class HelpRequestController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function fetchRequests() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM help_requests ORDER BY created_at DESC");
            $helpRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Pass the data to the view
            $viewPath = __DIR__ . '/../../views/help_requests_list.php';
            
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "View file not found: $viewPath";
            }
        } catch (PDOException $e) {
            echo "Error fetching help requests: " . $e->getMessage();
        }
    }
}
