<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class DashboardController
{
    public function getPendingRequests()
    {
        $database = new Database();
        $pdo = $database->connect();

        try {
            $stmt = $pdo->query("
                SELECT 
                    hr.id, 
                    hr.user AS request_user, 
                    hr.category, 
                    hr.description, 
                    hr.status, 
                    hr.created_at,
                    u.name AS user_name, 
                    u.email AS user_email
                FROM help_requests hr
                INNER JOIN users u ON hr.user_id = u.id
                WHERE hr.status = 'open'
                ORDER BY hr.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching pending requests: " . $e->getMessage();
            return [];
        }
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: ?page=home");
            exit;
        }

   
        if ($_SESSION['user']['role'] === 'admin') {
            $pendingRequests = $this->getPendingRequests();
            include __DIR__ . '/../views/auth/admin_dashboard.php'; 
            return;
        }

        if ($_SESSION['user']['role'] === 'member') {
            $database = new Database();
            $db = $database->connect();
            
            $userModel = new User($db);
            $totalUsers = $userModel->getTotalUsers();
            $recentActivities = [
                ['date' => '2024-12-09', 'activity' => 'Logged in'],
                ['date' => '2024-12-08', 'activity' => 'Updated profile'],
            ];

            include __DIR__ . '/../views/auth/dashboard.php'; 
            return;
        }

        header("Location: ?page=home");
        exit;
    }
}
