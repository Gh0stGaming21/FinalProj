<?php
class DashboardController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }

        $dashboardPath = './app/views/auth/dashboard.php';
        if (file_exists($dashboardPath)) {
            include $dashboardPath;
        } else {
            echo "Dashboard file not found.";
        }
    }
}
?>


