<?php

class HomeController
{
    private $viewBasePath;

    public function __construct()
    {
        $this->viewBasePath = __DIR__ . '/../Views/pages/';
    }



    public function index()
    {
        $this->render('home.php');
    }

    public function menu()
    {
        $this->render('menu-restaurant.php');
    }

    public function about()
    {
        $this->render('about.php');
    }

    public function portfolio()
    {
        $this->render('portfolio.php');
    }

    public function teams()
    {
        $this->render('teams.php');
    }

    public function test()
    {
        $this->render('test.php');
    }

    public function bookTable()
    {
        $this->render('book-table.php');
    }

    public function recruitment()
    {
        $this->render('recruitment.php');
    }

    public function locations()
    {
        $this->render('locations.php');
    }

    public function faq()
    {
        $this->render('faq.php');
    }

    public function offers()
    {
        $this->render('offers.php');
    }

    public function blog()
    {
        $this->render('blog.php');
    }

    public function blogDetail()
    {
        $this->render('blog-details.php');
    }

    public function contact()
    {
        $this->render('contact.php');
    }

    public function register()
    {
        $this->render('register.php');
    }

    public function forgotPassword()
    {
        $this->render('forgot-password.php');
    }

    public function error()
    {
        $this->render('404-error.php', 404);
    }

    public function productDetail()
    {
        $this->render('product_detail.php');
    }

    public function profile()
    {
        require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!AuthMiddleware::check()) {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        require_once __DIR__ . '/../Views/pages/profile.php';
    }


    private function render(string $viewFile, int $statusCode = 200)
    {
        $fullPath = $this->viewBasePath . $viewFile;

        if (file_exists($fullPath)) {
            http_response_code($statusCode);
            include $fullPath;
        } else {
            http_response_code(404);
            echo "Lỗi 404 - Không tìm thấy view: <code>$viewFile</code>";
        }
    }
}
