<?php
require_once __DIR__ . '/../controllers/HelpRequestController.php';
require_once __DIR__ . '/../controllers/ResourceSharingController.php';  
require_once __DIR__ . '/../controllers/EventsController.php';  

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
    
        if ($postType === 'text') {
            $controller->createTextPost($user['id'], $postText);
        } elseif ($postVideo) {
            $controller->createVideoPost($user['id'], $postVideo);
        } elseif ($postImage) {
            $controller->createImagePost($user['id'], $postImage);
        }
    
        header("Location: ?page=dashboard");
        exit;
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

    private function handleEvents() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['location']) && !isset($_GET['date'])) {
            $this->loadView('events.php');
        } else {
            $filters = [];
    
            if (isset($_GET['location']) && !empty($_GET['location'])) {
                $filters['location'] = $_GET['location'];
            }
            if (isset($_GET['date']) && !empty($_GET['date'])) {
                $filters['date'] = $_GET['date'];
            }
    
            $controller = new EventsController();
            $events = $controller->getEvents($filters);
            $this->loadView('events.php', ['events' => $events, 'filters' => $filters]);
        }
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

    private function handleProfileView() {
        if (!isset($_SESSION['user'])) {
            echo "No user session found. Redirecting to login.";
            header("Location: ?page=login");
            exit;
        }

        $user = $_SESSION['user']; 

        $database = new Database();
        $pdo = $database->connect();

        $controller = new ProfileController($pdo);
        $profile = $controller->getProfile($user['id']);
        $this->loadView('profile.php', ['profile' => $profile]);
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

class PostController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTextPost($userId, $postText) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, post_text, post_type) VALUES (:user_id, :post_text, 'text')");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_text', $postText);
        $stmt->execute();
    }

    public function createVideoPost($userId, $postVideo) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, post_video, post_type) VALUES (:user_id, :post_video, 'video')");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_video', $postVideo['name']);
        $stmt->execute();

        $uploadDir = './public/uploads/videos/';
        $uploadFile = $uploadDir . basename($postVideo['name']);
        move_uploaded_file($postVideo['tmp_name'], $uploadFile);
    }

    public function createImagePost($userId, $postImage) {
        $uploadDir = './public/uploads/images/';
        $uploadFile = $uploadDir . basename($postImage['name']);
        move_uploaded_file($postImage['tmp_name'], $uploadFile);
    
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, post_image, post_type) VALUES (:user_id, :post_image, 'image')");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_image', $uploadFile);
        $stmt->execute();
    }
}
?>
