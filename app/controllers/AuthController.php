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
            }
    
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user; 
                $_SESSION['role'] = $user['role'];
    
                if ($user['role'] === 'admin') {
                    header("Location: ?page=adminDashboard");
                } elseif ($user['role'] === 'member') {
                    header("Location: ?page=dashboard"); 
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid credentials.";
                header("Location: ?page=login");
                exit;
            }
        }
    
        include './app/views/auth/login.php';
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
  
            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "All fields are required.";
                header("Location: ?page=register");
                exit;
            }
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format.";
                header("Location: ?page=register");
                exit;
            }
   
            if (strlen($password) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters long.";
                header("Location: ?page=register");
                exit;
            }

            if ($this->userModel->register($name, $email, $password)) {
                $_SESSION['success'] = "Registration successful! You can now log in.";
                header("Location: ?page=login");
                exit;
            } else {
                $_SESSION['error'] = "Registration failed. Email might already be registered.";
                header("Location: ?page=register");
                exit;
            }
        }
    
        include './app/views/auth/register.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: ?page=login");
        exit;
    }
}
