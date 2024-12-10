<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class DashboardController
{
    public function index()
    {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=home");
            exit;
        }

        
        if ($_SESSION['user']['role'] !== 'member') {
            header("Location: ?page=dashboard");
            exit;
        }

    
        $database = new Database();
        $db = $database->connect();

    
        $userModel = new User($db);

      
        $totalUsers = $userModel->getTotalUsers();
        $recentActivities = [
            ['date' => '2024-12-09', 'activity' => 'Logged in'],
            ['date' => '2024-12-08', 'activity' => 'Updated profile'],
        ];

        
        include __DIR__ . '/../views/auth/dashboard.php';
    }
}
?>
