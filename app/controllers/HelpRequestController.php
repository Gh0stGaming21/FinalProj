<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/HelpRequestModel.php';

class HelpRequestController {
    private $helpRequestModel;

    public function __construct() {
        $database = new Database();
        $pdo = $database->connect();
        $this->helpRequestModel = new HelpRequestModel($pdo);
    }

    /**
     * Fetch and return all help requests.
     */
    public function getHelpRequests() {
        try {
            return $this->helpRequestModel->getAllHelpRequests();
        } catch (Exception $e) {
            error_log("Error fetching help requests: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Handle help request form submissions.
     */
    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->ensureSessionIsValid();

            $userId = $_SESSION['user']['id'] ?? null;
            $category = trim($_POST['category'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($category) || empty($description)) {
                $this->redirectWithError("All fields are required.", "?page=help_requests");
            }

            try {
                $this->helpRequestModel->createHelpRequest($userId, $category, $description);
                header("Location: ?page=help_requests");
                exit;
            } catch (Exception $e) {
                error_log("Error submitting help request: " . $e->getMessage());
                $this->redirectWithError("An error occurred. Please try again.", "?page=help_requests");
            }
        }
    }

    /**
     * Ensure the session is valid and the user is authenticated.
     */
    private function ensureSessionIsValid() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || intval($_SESSION['user']['id']) !== intval($_POST['user_id'] ?? 0)) {
            error_log("Invalid user session or mismatched user ID.");
            echo "Error: Invalid user.";
            exit;
        }
    }

    /**
     * Redirect with an error message.
     */
    private function redirectWithError($message, $redirectUrl) {
        $_SESSION['error'] = $message;
        header("Location: $redirectUrl");
        exit;
    }
}