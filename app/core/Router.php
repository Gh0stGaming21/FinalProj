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
            'forgotpassword' => 'handleForgotPassword',
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
        $postVideo = $_FILES['post_video'] ?? null;
        $postImage = $_FILES['post_image'] ?? null;

        echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    echo '</pre>';
    exit;

        try {
            if ($postType === 'text' && !empty($postText)) {
                $controller->createTextPost($user['id'], $postText);
            } elseif ($postType === 'video' && $postVideo) {
                $controller->createVideoPost($user['id'], $postVideo);
            } elseif ($postType === ' image' && $postImage) {
                $controller->createImagePost($user['id'], $postImage);
            } else {
                throw new Exception("Invalid post data.");
            }

            header("Location: ?page=dashboard");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
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
    
                if ($eventId && $userId) {
                    $eventsController->rsvpToEvent($eventId, $userId);
                } else {
                    echo "Missing event_id or user_id.";
                }
                break;
    
            default:
                $this->show404();
                break;
        }
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event_name = isset($_POST['event_name']) ? htmlspecialchars($_POST['event_name']) : '';
            $location = isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '';
            $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : '';

            if (empty($event_name) || empty($location) || empty($event_date)) {
                $_SESSION['error'] = 'All fields are required.';
                header('Location: ?page=events&action=create');
                exit();
            }

            $query = "INSERT INTO events (event_name, location, event_date) VALUES (:event_name, :location, :event_date)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':event_name', $event_name);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':event_date', $event_date);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Event created successfully!';
            } else {
                $_SESSION['error'] = 'Failed to create event. Please try again.';
            }
            header('Location: ?page=events');
            exit();
        }

        $this->loadView('create_event.php');
    }

    private function handleResourceSharing() {
        $filters = [];

        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $filters['location'] = $_GET['location'];
        }
        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }

        $controller = new ResourceSharingController();
        $resources = $controller->getResources($filters);
        $this->loadView('resource_sharing.php', ['resources' => $resources]);
    }

    private function handleAdminDashboard() {
        if (!isset($_SESSION['user'])) {
            echo "No user session found. Redirecting to login.";
            header("Location: ?page=login");
            exit;
        }

        $user = $_SESSION['user'];

        if ($user['role'] !== 'admin') {
            echo "Access Denied: You are not an admin.";
            header("Location: ?page=login");
            exit;
        }

        $database = new Database();
        $pdo = $database->connect();

        $controller = new DashboardController($pdo);
        $pendingRequests = $controller->getPendingRequests();
        $this->loadView('auth/adminDashboard.php', ['pendingRequests' => $pendingRequests]);
    }
}


?>