<?php

require_once __DIR__ . '/../Models/Auth/AccountApi.php';
require_once __DIR__ . '/../Models/Order/OrderApi.php';
require_once __DIR__ . '/../configs/config.php';

class ProfileController
{
    private AccountApi $accountApi;
    private OrderApi   $orderApi;

    public function __construct()
    {
        $this->accountApi = new AccountApi();
        $this->orderApi   = new OrderApi();     
    }

    public function showProfile(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $accountId = (int) $_SESSION['user']['id'];

        $accountProfile = $this->accountApi->getAccountProfile($accountId);

        $rawOrders = $this->orderApi->getOrdersByAccount($accountId);

        require_once __DIR__ . '/../Models/Delivery/DeliveryApi.php';
        $deliveryApi = new DeliveryApi();

        $orderHistory = [];
        foreach ($rawOrders as $ord) {
            $orderId = (int)$ord['id_orders'];

            // 5.1 lấy items
            $items    = $this->orderApi->getOrderItems($orderId);
            $itemList = array_map(fn($i) => "{$i['name']} x{$i['quantity']}", $items);

            // 5.2 lấy delivery_status
            $del             = $deliveryApi->getDeliveryByOrderId($orderId);
            $deliveryStatus  = $del['delivery_status'] ?? 'pending';

            // 5.3 thêm vào history
            $orderHistory[] = [
                'id_orders'       => $orderId,
                'delivery_status' => $deliveryStatus,
                'items'           => implode(', ', $itemList),
                'order_date'      => $ord['created_at'],
                'total_price'     => $ord['total_price'],
            ];
        }

        $perPage     = 10;
        $totalOrders = count($orderHistory);
        $totalPages  = $totalOrders > 0 ? (int) ceil($totalOrders / $perPage) : 1;
        $currentPage = isset($_GET['page']) 
            ? max(1, min($totalPages, (int) $_GET['page'])) 
            : 1;
        $offset      = ($currentPage - 1) * $perPage;
        $pagedOrders = array_slice($orderHistory, $offset, $perPage);

        require_once __DIR__ . '/../views/pages/profile.php';
    }


    public function updateProfile(): void
    {
        require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
        if (!AuthMiddleware::check()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $id = $_SESSION['user']['id'];
        $data = [
            'id_accounts' => $id,
            'full_name'   => $_POST['full_name'] ?? '',
            'email'       => $_POST['email'] ?? '',
            'username'    => $_POST['username'] ?? '',
            'birthday'    => $_POST['birthday'] ?? null,
            'gender'      => $_POST['gender'] ?? null,
            'city'        => $_POST['city'] ?? null,
            'address'     => $_POST['address'] ?? null,
        ];

        $this->accountApi->updateProfileInfo($data);

        header('Location: ' . BASE_URL . 'profile?updated=1');
        exit;
    }
}
