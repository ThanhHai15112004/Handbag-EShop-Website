<?php
$url = $_GET['url'] ?? '/';

switch ($url) {
    case '/':
        require_once '../app/Controllers/UserController.php';
        (new HomeController())->index();
        break;
    default:
        echo "404 - Page Not Found";
}
