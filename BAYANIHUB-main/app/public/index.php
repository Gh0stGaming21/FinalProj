<?php
session_start();  

require_once '../core/DB.php';
require_once '../models/User.php';
require_once '../controllers/AuthController.php';

use App\Controllers\AuthController;

$auth = new AuthController();

$route = $_GET['route'] ?? '';

if ($route === 'auth/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($auth->login($username, $password)) {
            header('Location: /index.php?route=home');
            exit;
        } else {
            $error_message = "Invalid username or password";  
            include '../views/login.php'; 
            exit;
        }
    } else {
        include '../views/login.php';  
    }
} elseif ($route === 'home') {
    if (!$auth->isLoggedIn()) {
        header('Location: /index.php?route=auth/login');  
        exit;
    }
    echo "<h1>Welcome, " . $_SESSION['username'] . "!</h1>"; 
    echo '<a href="/index.php?route=auth/logout">Logout</a>';
} elseif ($route === 'auth/logout') {
    $auth->logout(); 
    header('Location: /index.php?route=auth/login'); 
    exit;
} else {
    echo "Page not found!";
}
?>


