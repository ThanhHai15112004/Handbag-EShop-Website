<?php
class HomeController {
    public function index() {
        require_once '../app/Views/User/homepage.php';
    }
}

class ProductController {
    public function product() {
        require_once '../app/Views/User/productpage.php';
    }
}


