<?php

require_once __DIR__ . '/../Models/Carts/CartApi.php';
require_once __DIR__ . '/../Models/Order/OrderApi.php';
require_once __DIR__ . '/../configs/config.php'; 
require_once __DIR__ . '/../Models/Promotion/PromotionApi.php';

class OrderController
{
    public function showOrderPage(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cartApi = new CartApi();
        $cartItems = $cartApi->getCart();

        $orderApi = new OrderApi();
        $promotionApi = new PromotionApi();
        $validPromotions = $promotionApi->getAllValid(); // để hiển thị danh sách áp dụng

        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $totals = $orderApi->getTotalsFromDatabase($userId);
        } else {
            $totals = $cartApi->getTotals();
        }

        // ✅ Đọc order_id từ URL (nếu đã tạo đơn hàng)
        $orderId = $_GET['order_id'] ?? null;
        $totalDiscount = 0;
        $appliedPromotions = [];

        if ($orderId && is_numeric($orderId)) {
            $appliedPromotions = $promotionApi->getPromotionsByOrder((int)$orderId);

            foreach ($appliedPromotions as $promo) {
                if ($promo['discount_type'] === 'amount') {
                    $totalDiscount += (float)$promo['value'];
                } elseif ($promo['discount_type'] === 'percent') {
                    $subtotal = $totals['total_price'] ?? 0;
                    $totalDiscount += round($subtotal * $promo['value'] / 100);
                }
            }
        }

        // ✅ Gán giá trị giảm vào totals (để view dùng)
        $totals['discount'] = $totalDiscount;

        // ✅ View cần $orderId, $appliedPromotions nếu muốn hiển thị
        require_once __DIR__ . '/../Views/pages/order-item.php';
    }




    public function createOrder(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'banking';
        $promotionCode = $_POST['promotion_code'] ?? null;
        $_SESSION['promotion_code'] = $promotionCode;




        if (empty($email) || empty($phone) || empty($address)) {
            header("Location: " . BASE_URL . "order?error=missing_info");
            exit;
        }

        $cartApi = new CartApi();
        $cartItems = $cartApi->getCart();

        if (empty($cartItems)) {
            header("Location: " . BASE_URL . "cart?error=empty");
            exit;
        }

        $orderApi = new OrderApi();
        $orderId = $orderApi->createOrder($userId, $cartItems);

        if ($orderId) {
            $_SESSION['shipping_address'] = $address;
            $cartApi->clearCart();

            // ✅ Sửa ở đây
            header("Location: " . BASE_URL . "payment?order_id=$orderId&method=$paymentMethod");
        } else {
            header("Location: " . BASE_URL . "order?error=order_failed");
        }

        exit;
    }



    


    
}
