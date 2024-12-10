<?php
require_once __DIR__ . '/../controllers/HelpRequestController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class Router {
    private $viewsBase;

    public function __construct() {
        $this->viewsBase = __DIR__ . '/../../views';
    }

    public function route() {
        $page = strtolower($_GET['page'] ?? 'login');
        $routeHandlers = [
            'help_requests' => 'handleHelpRequests',
            'dashboard' => 'handleDashboard',
            'register' => 'handleRegister',
            'forgotpassword' => 'handleForgotPassword',
            'profile' => 'handleProfileView',
            'logout' => 'handleLogout',
            'login' => 'handleLogin',
        ];

        if (isset($routeHandlers[$page])) {
            $this->{$routeHandlers[$page]}();
        } else {
            $this->show404();
        }
    }

    private function handleLogin() {
        $controller = new AuthController();
        $controller->login();
    }

    private function handleHelpRequests() {
        $controller = new HelpRequestController();
        $controller->handleFormSubmission(); 
        $helpRequests = $controller->getHelpRequests();
        $this->loadView('help_requests_list.php', ['helpRequests' => $helpRequests]);
    }

    private function handleDashboard() {
        if ($_SESSION['user']['role'] === 'admin') {
            $controller = new DashboardController();
            $pendingRequests = $controller->getPendingRequests();
            $this->loadView('auth/adminDashboard.php', ['pendingRequests' => $pendingRequests]);
        } else {
            $this->loadView('auth/dashboard.php');
        }
    }
    

    private function handleRegister() {
        $controller = new AuthController();
        $controller->register();
    }

    private function handleForgotPassword() {
        $controller = new AuthController();
        $controller->forgotPassword();
    }

    private function loadView($viewFile, $data = []) {
        extract($data);
        $rootPath = dirname(__DIR__, 2);
        $fullPath = $rootPath . '/app/views/' . $viewFile;

        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            echo "View file not found or path is incorrect: $fullPath<br>";
            $this->show404();
        }
    }

    private function validateRole($role) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            header("Location: ?page=login");
            exit;
        }
    }

    private function show404() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 - Page Not Found</h1>";
    }
}
?>
