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
            $stmt = $this->pdo->query("SELECT * FROM help_requests ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching help requests: " . $e->getMessage();
            return [];
        }
    }
    public function handleFormSubmission() {
        // Assuming you have a form that POSTs to this method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = $_POST['category'];
            $description = $_POST['description'];

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Ensure user is logged in
            if (isset($_SESSION['user'])) {
                $userId = $_SESSION['user']['id']; // Assuming 'id' is stored in the session

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO help_requests (user_id, category, description, status, created_at)
                        VALUES (:user_id, :category, :description, 'open', NOW())
                    ");
                    $stmt->execute([
                        ':user_id' => $userId,
                        ':category' => $category,
                        ':description' => $description,
                    ]);

                    // Redirect to success or back to help requests list
                    header("Location: ?page=help_requests");
                    exit;
                } catch (PDOException $e) {
                    echo "Error submitting the help request: " . $e->getMessage();
                }
            } else {
                echo "User is not logged in.";
            }
        }

         function getPendingRequests() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM help_requests WHERE status = 'Pending' ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching pending requests: " . $e->getMessage();
            return [];
        }
    }
}
?>
