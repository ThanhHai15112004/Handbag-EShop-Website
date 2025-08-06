<?php

require_once __DIR__ . '/../app/Controllers/HomeController.php';

require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUrl = parse_url($requestUri, PHP_URL_PATH);
$parsedUrl = rtrim($parsedUrl, '/');

$route = basename($parsedUrl);

$controller = new HomeController();

switch ($route) {
    case '':
    case 'public':
    case '/public/index.php':
    case 'home':
    case '/':
    case '/public/':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'menu-restaurant':
        $controller = new HomeController();
        $controller->menu();
        break;

    case 'about':
        $controller = new HomeController();
        $controller->about();
        break;

    case 'portfolio':
        $controller = new HomeController();
        $controller->portfolio();
        break;

    case 'teams':
        $controller = new HomeController();
        $controller->teams();
        break;

    case 'book-table':
        $controller = new HomeController();
        $controller->bookTable();
        break;

    case 'recruitment':
        $controller = new HomeController();
        $controller->recruitment();
        break;

    case 'locations':
        $controller = new HomeController();
        $controller->locations();
        break;

    case 'faq':
        $controller = new HomeController();
        $controller->faq();
        break;

    case 'offers':
        $controller = new HomeController();
        $controller->offers();
        break;

    case 'blog':
        $controller = new HomeController();
        $controller->blog();
        break;

    case 'blog-details':
        $controller = new HomeController();
        $controller->blogDetail();
        break;

    case 'contact':
        $controller = new HomeController();
        $controller->contact();
        break;


    case 'forgot-password':
        $controller = new HomeController();
        $controller->forgotPassword();
        break;



    case '404':
        $controller = new HomeController();
        $controller->error();
        break;


    case 'product-detail':
        $controller = new HomeController();
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $controller->productDetail($productId);
        break;

    case 'profile':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->showProfile();
        break;

    case 'test':
        $controller = new HomeController();
        $controller->test();
        break;

    case 'login':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->login();
        } else {
            require_once __DIR__ . '/../app/Views/pages/login.php';
        }
        break;


    case 'register':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->register();
        } else {
            require_once __DIR__ . '/../app/Views/pages/register.php';
        }
        break;
    
    case 'verify':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->verifyEmail();
        break;

    case 'order':
        require_once __DIR__ . '/../app/Controllers/OrderController.php';
        $controller = new OrderController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createOrder(); // 🟢 Gọi đúng!
        } else {
            $controller->showOrderPage();
        }
        break;



    case 'payment':
        require_once __DIR__ . '/../app/Controllers/PaymentController.php';
        $controller = new PaymentController();
        $controller->createInvoiceAndDelivery();
        break;

    case 'payment-qr':
        require_once __DIR__ . '/../app/Controllers/PaymentController.php';
        $controller = new PaymentController();
        $controller->showPaymentPage();
        break;

    case 'payment-success':
        require_once __DIR__ . '/../app/Controllers/PaymentController.php';
        $controller = new PaymentController();
        $controller->showPaymentSuccessPage();
        break;


    case 'apply-promotion':
        require_once __DIR__ . '/../app/Controllers/PromotionController.php';
        $controller = new PromotionController();
        $controller->apply();
        break;



    case 'remove-promotion':
        require_once __DIR__ . '/../app/Controllers/PromotionController.php';
        $controller = new PromotionController();
        $controller->remove();
        break;



    // *****************************************
    // test
    case 'mock-payment-success':
        require_once __DIR__ . '/../app/Controllers/PaymentController.php';
        $controller = new PaymentController();
        $controller->mockPaymentSuccess();
        break;
    // *****************************************

    case 'admin':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    case 'logout':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'admin-create-product':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->createProduct();
        break;
    
    case 'admin-create-category':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->createCategory();
        break;

    case 'admin-accounts':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    case 'admin-create-account':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->createAccount();
        break;

    case 'admin-update-product':
        require_once __DIR__ . '/../app/Controllers/Admin/AdminController.php';
        $controller = new AdminController();
        $controller->updateProduct();
        break;

    case 'admin-delete-product':
        require_once __DIR__ . '/../app/Controllers/Admin/AdminController.php';
        $controller = new AdminController();
        $controller->deleteProduct();
        break;

    case 'admin-update-category':
        require_once __DIR__ . '/../app/Controllers/Admin/AdminController.php';
        $controller = new AdminController();
        $controller->updateCategory();
        break;

    case 'admin-delete-category':
        require_once __DIR__ . '/../app/Controllers/Admin/AdminController.php';
        $controller = new AdminController();
        $controller->deleteCategory();
        break;

    case 'admin-update-account':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->updateAccount();
        break;

    case 'admin-delete-account':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->deleteAccount();
        break;

    case 'admin-deliveries':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->index(); 
        break;


    case 'admin-update-delivery':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->updateDelivery();
        break;

    case 'admin-create-delivery':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->createDelivery();
        break;

    case 'admin-delete-delivery':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->deleteDelivery();
        break;

    case 'admin-orders':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->index(); 
        break;

    case 'admin-create-order':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->createOrder();
        break;

    case 'admin-update-order':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->updateOrder();
        break;

    case 'admin-delete-order':
        require_once __DIR__ . '/../app/Controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->deleteOrder();
        break;



    case 'profile-update':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->updateProfile();
        break;

    case 'cart-add':
        require_once __DIR__ . '/../app/Controllers/CartController.php';
        $controller = new CartController();
        $controller->addToCart();
        break;
        
    case 'cart-remove':
        require_once __DIR__ . '/../app/Controllers/CartController.php';
        $controller = new CartController();
        $_POST['id'] = $_GET['id'] ?? 0;
        $controller->removeFromCart();
        break;


    case 'cart-increase':
        require_once __DIR__ . '/../app/Controllers/CartController.php';
        $_GET['route'] = 'cart-increase'; 
        $controller = new CartController();
        $controller->updateQuantity();
        break;

    case 'cart-decrease':
        require_once __DIR__ . '/../app/Controllers/CartController.php';
        $_GET['route'] = 'cart-decrease';
        $controller = new CartController();
        $controller->updateQuantity();
        break;




    default:
        $controller = new HomeController();
        $controller->error();
        break;
}
