<?php
class Router {
    private $viewsBase;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->viewsBase = __DIR__ . '/../../views';
    }

    public function route() {
        $page = strtolower($_GET['page'] ?? 'login');
        $routeHandlers = [
            'login' => 'handleLogin',
            'help_requests' => 'handleHelpRequests',
            'dashboard' => 'handleDashboard',
            'register' => 'handleRegister',
            'forgotpassword' => 'handleForgotPassword',
            'profile' => 'handleProfileView',
            'logout' => 'handleLogout',
            'events' => 'handleEvents',
            'resource_sharing' => 'handleResourceSharing',
        ];

        if (isset($routeHandlers[$page])) {
            $this->{$routeHandlers[$page]}();
        } else {
            $this->show404();
        }
    }

    private function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->login();
        } else {
           
            $this->loadView('auth/login.php');
        }
    }

    private function handleLogout() {
        $controller = new AuthController();
        $controller->logout();
    }

    private function handleHelpRequests() {
        if (!class_exists('HelpRequestController')) {
            echo "HelpRequestController class not found!";
            exit;
        }
    
        $controller = new HelpRequestController();
        $controller->handleFormSubmission();
        $helpRequests = $controller->getHelpRequests();
        $this->loadView('help_requests_list.php', ['helpRequests' => $helpRequests]);
    }
    
    

private function handleDashboard() {
    var_dump($_SESSION['user']);  
    if (!isset($_SESSION['user'])) {
        echo "No user session found, redirecting to login.";  
        header("Location: ?page=login");
        exit;
    }

    $user = $_SESSION['user'];
    if ($user['role'] === 'admin') {
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

    private function show404() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 - Page Not Found</h1>";
    }
}
?>
