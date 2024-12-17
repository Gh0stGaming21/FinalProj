<?php
require_once __DIR__ . '/../controllers/HelpRequestController.php';
require_once __DIR__ . '/../controllers/ResourceSharingController.php';  
require_once __DIR__ . '/../controllers/EventsController.php';  
require_once __DIR__ . '/../controllers/PostController.php';

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
            'admindashboard' => 'handleAdminDashboard',
            'register' => 'handleRegister',
            'profile' => 'handleProfileView',
            'logout' => 'handleLogout',
            'events' => 'handleEvents',
            'create_event' => 'handleCreateEvent',
            'resource_sharing' => 'handleResourceSharing',
            'create_post' => 'handleCreatePost',
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
            if (isset($_SESSION['user'])) {
                $role = $_SESSION['user']['role'];
                if ($role == 'admin') {
                    header('Location: ?page=adminDashboard');
                    exit;
                } elseif ($role == 'member') {
                    header('Location: ?page=dashboard');
                    exit;
                } else {
                    header('Location: ?page=login');
                    exit;
                }
            } else {
                header('Location: ?page=login');
                exit;
            }
        } else {
            $this->loadView('auth/login.php');
        }
    }

    private function handleLogout() {
        $controller = new AuthController();
        $controller->logout();
    }

    public function handleProfileView() {
        require_once './app/views/profile.php';
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
        if (!isset($_SESSION['user'])) {
            echo "No user session found. Redirecting to login.";
            header("Location: ?page=login");
            exit;
        }
    
        $user = $_SESSION['user'];
    
        $database = new Database();
        $pdo = $database->connect();
    
        if ($user['role'] === 'admin') {
            $controller = new DashboardController($pdo);
            $pendingRequests = $controller->getPendingRequests();
            $this->loadView('auth/adminDashboard.php', ['pendingRequests' => $pendingRequests]);
        } elseif ($user['role'] === 'member') {
            $controller = new DashboardController($pdo);
            $recentActivities = $controller->getRecentActivities();
            $this->loadView('auth/dashboard.php', ['recentActivities' => $recentActivities]);
        }
    }
    
    private function handleCreatePost() {
        if (!isset($_SESSION['user'])) {
            echo "No user session found. Redirecting to login.";
            header("Location: ?page=login");
            exit;
        }

        $user = $_SESSION['user'];
        
        $database = new Database();
        $pdo = $database->connect();
        $controller = new PostController($pdo);

        $postType = $_POST['post_type'] ?? null;
        $postText = $_POST['post_text'] ?? null;

        try {
            if ($postType === 'text' && !empty($postText)) {
                $controller->createTextPost($user['id'], $postText);
                header("Location: ?page=dashboard&success=true");
                exit;
            } else {
                throw new Exception("Invalid post data.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    private function handleRegister() {
        $controller = new AuthController();
        $controller->register();
    }

    private function loadView($viewFile, $data = []) {
        extract($data);
        $rootPath = dirname(__DIR__, 2); 
        $fullPath = $rootPath . '/app/views/' . $viewFile; 
        
        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            echo "View file not found: $fullPath<br>"; 
            $this->show404();
        }
    }

    private function show404() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 - Page Not Found</h1>";
    }

    private function handleEvents($action = 'list') {
        $database = new Database();
        $pdo = $database->connect();
    
        $eventsController = new EventsController($pdo);
    
        if ($action === 'create') {
            $eventsController->create(); 
        }
    
        switch ($action) {
            case 'list':
                $filters = [
                    'location' => $_GET['location'] ?? null,
                    'date' => $_GET['date'] ?? null,
                ];
                $events = $eventsController->getEvents($filters); 
                $this->loadView('events_list.php', ['events' => $events]);
                break;
    
            case 'rsvp':
                $eventId = $_POST['id'] ?? null;
                $userId = $_POST['user_id'] ?? null;
                // RSVP handling logic here
                break;
        }
    }
}
?>