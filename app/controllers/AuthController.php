<?php
require_once './app/models/User.php';  
require_once './config/Database.php';  

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->userModel = new User($this->db);
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
    
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email and password are required.";
                header("Location: ?page=login");
                exit;
            }
    
            $user = $this->userModel->authenticateUser($email, $password);
    
            if ($user === 'inactive') {
                $_SESSION['error'] = "Your account is inactive. Please contact support.";
                header("Location: ?page=login");
                exit;
            } if ($user) {
                $_SESSION['user'] = $user;
                var_dump($_SESSION['user']); 
                header("Location: ?page=dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Invalid credentials.";
                header("Location: ?page=login");
                exit;
            }
        }
    
        include './app/views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);

            if ($this->userModel->register($name, $email, $password)) {
                $_SESSION['success'] = "Registration successful! You can now log in.";
                header("Location: ?page=login");
                exit;
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
        }

        include './app/views/auth/register.php';
    }

    public function forgotPassword() {
        
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ?page=login");
        exit;
    }
}
?>
