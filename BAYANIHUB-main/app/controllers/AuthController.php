<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();  
        session_start();  
    }


    public function login($username, $password) {
        $user = $this->userModel->findUserByUsername($username);  
        if ($user && password_verify($password, $user['password'])) {  
            $_SESSION['user_id'] = $user['user_id'];  
            $_SESSION['username'] = $user['username'];  
            session_regenerate_id(true);  
            return true;
        }
        return false;  
    }

 
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_unset();  
        session_destroy();  
    }
}
?>

