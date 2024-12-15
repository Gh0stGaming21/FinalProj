<?php
ob_start();
session_start();

define('BASE_PATH', __DIR__); 

require_once './config/database.php';
require_once './app/controllers/AuthController.php';
require_once './app/controllers/DashboardController.php';
require_once './app/core/Router.php'; 

$router = new Router();
$router->route();

ob_end_flush();
?>