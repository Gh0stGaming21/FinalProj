<?php
session_start();

define('BASE_PATH', __DIR__);

require_once './config/database.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/DashboardController.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

        case 'register':
            $controller = new AuthController();
            $controller->register();
            break;
        

    case 'forgotpassword':
        $controller = new AuthController();
        $controller->forgotPassword();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        $controller = new AuthController();
        $controller->login();
        break;
}
?>
