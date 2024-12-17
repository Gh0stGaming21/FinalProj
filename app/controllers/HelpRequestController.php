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

    public function getHelpRequests() {
        try {
            return $this->helpRequestModel->getAllHelpRequests();
        } catch (Exception $e) {
            error_log("Error fetching help requests: " . $e->getMessage());
            return [];
        }
    }

    public function getApprovedRequests() {
        try {
            return $this->helpRequestModel->getRequestsByStatus('resolved');
        } catch (Exception $e) {
            error_log("Error fetching approved requests: " . $e->getMessage());
            return [];
        }
    }

    public function getRejectedRequests() {
        try {
            return $this->helpRequestModel->getRequestsByStatus('rejected');
        } catch (Exception $e) {
            error_log("Error fetching rejected requests: " . $e->getMessage());
            return [];
        }
    }

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

    public function approveRequest($requestId) {
        try {
            $this->helpRequestModel->updateRequestStatus($requestId, 'resolved');
            header('Location: ?page=admindashboard&success=approved');
        } catch (Exception $e) {
            error_log("Error approving request: " . $e->getMessage());
            header('Location: ?page=admindashboard&error=approve_failed');
        }
    }

    public function rejectRequest($requestId) {
        try {
            error_log("Rejecting request ID: " . $requestId); // Debugging line
            $this->helpRequestModel->updateRequestStatus($requestId, 'rejected');
            header('Location: ?page=admindashboard&success=rejected');
        } catch (Exception $e) {
            error_log("Error rejecting request: " . $e->getMessage());
            header('Location: ?page=admindashboard&error=reject_failed');
        }
    }

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

    private function redirectWithError($message, $redirectUrl) {
        $_SESSION['error'] = $message;
        header("Location: $redirectUrl");
        exit;
    }
}