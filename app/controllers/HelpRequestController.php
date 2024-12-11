<?php
require_once __DIR__ . '/../../config/Database.php';

class HelpRequestController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function getHelpRequests() {
        try {
            $stmt = $this->pdo->query("
                SELECT hr.id, hr.category, hr.description, hr.status, hr.created_at, u.name AS user_name
                FROM help_requests hr
                JOIN users u ON hr.user_id = u.id
                ORDER BY hr.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching help requests: " . $e->getMessage());
            return [];
        }
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user']) || intval($_SESSION['user']['id']) !== intval($_POST['user_id'])) {
                error_log("Invalid user session or mismatched user ID.");
                echo "Error: Invalid user.";
                exit;
            }

            // Validate form fields
            if (empty($_POST['category']) || empty($_POST['description'])) {
                echo "Error: All fields are required.";
                exit;
            }

            $userId = intval($_SESSION['user']['id']);
            $category = trim($_POST['category']);
            $description = trim($_POST['description']);

            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO help_requests (user_id, category, description, status, created_at)
                    VALUES (:user_id, :category, :description, 'open', NOW())
                ");
                $stmt->execute([
                    ':user_id' => $userId,
                    ':category' => $category,
                    ':description' => $description,
                ]);

                header("Location: ?page=help_requests");
                exit;
            } catch (PDOException $e) {
                error_log("Error submitting help request: " . $e->getMessage());
                echo "Error submitting the help request.";
            }
        }
    }
}
