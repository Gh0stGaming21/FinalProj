<?php
require_once __DIR__ . '/../controllers/HelpRequestController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class Router
{
    public function route()
    {
        $page = strtolower($_GET['page'] ?? 'login');
        $routeHandlers = [
            'help_requests' => 'handleHelpRequests',
            'dashboard' => 'handleDashboard',
            'register' => 'handleRegister',
            'forgotpassword' => 'handleForgotPassword',
            'user' => 'handleUserView',
            'profile' => 'handleProfileView',
            'reports' => 'handleReportsView',
            'settings' => 'handleSettingsView',
            'logout' => 'handleLogout',
            'login' => 'handleLogin',
        ];

        if (isset($routeHandlers[$page])) {
            $this->{$routeHandlers[$page]}();
        } else {
            $this->show404();
        }
    }

    private function handleReportsView()
    {
        $this->loadView(__DIR__ . '/../views/resource_sharing.php');
    }

    private function handleSettingsView()
    {
        $this->loadView(__DIR__ . '/../views/events.php');
    }

    private function handleHelpRequests()
    {
        $controller = new HelpRequestController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handleFormSubmission();
        }

        $requests = $controller->fetchRequests();
        $this->loadView(__DIR__ . '/../views/help_requests_form.php');
        $this->loadView(__DIR__ . '/../views/help_requests_list.php');
    }

    private function handleDashboard()
    {
        $controller = new DashboardController();
        $controller->index();
    }

    private function handleRegister()
    {
        $controller = new AuthController();
        $controller->register();
    }

    private function handleForgotPassword()
    {
        $controller = new AuthController();
        $controller->forgotPassword();
    }

    private function handleUserView()
    {
        $this->loadView(__DIR__ . '/../views/user.php');
    }

    private function handleProfileView()
    {
        $this->loadView(__DIR__ . '/../views/profile.php');
    }

    private function handleLogout()
    {
        $controller = new AuthController();
        $controller->logout();
    }

    private function handleLogin()
    {
        $controller = new AuthController();
        $controller->login();
    }

    private function loadView($viewPath)
    {
        echo "Attempting to load view: $viewPath <br>"; // Debugging
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            $this->show404();
        }
    }

    private function show404()
    {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 - Page Not Found</h1>";
    }
}
?>
