<?php
require_once './config/Database.php';  // Ensure Database class is included
require_once './app/models/User.php';  // Ensure User model is included

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPendingRequests()
    {
        try {
            $stmt = $this->pdo->query("SELECT 
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
                                    ORDER BY hr.created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching pending requests: " . $e->getMessage()); // Log the error
            return [];
        }
    }

    public function getUserDashboardData()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }

        $user = $_SESSION['user'];
        $database = new Database();
        $db = $database->connect(); 

        $userModel = new User($db);
        $totalUsers = $userModel->getTotalUsers();

        $recentActivities = [
            ['activity' => 'Logged in', 'created_at' => '2024-12-09'],
            ['activity' => 'Updated profile', 'created_at' => '2024-12-08'],
        ];

        return [
            'totalUsers' => $totalUsers,
            'recentActivities' => $recentActivities,
            'user' => $user
        ];
    }
}
?>
