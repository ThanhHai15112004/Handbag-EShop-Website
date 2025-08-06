<?php
require_once __DIR__ . '/../Models/Promotion/PromotionApi.php';
require_once __DIR__ . '/../configs/config.php';

class PromotionController
{
    public function apply(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $code = $_GET['code'] ?? '';
        $orderId = $_GET['order_id'] ?? null;

        $promotionApi = new PromotionApi();
        $promotion = $promotionApi->getPromotionByCode($code);

        if (!$promotion) {
            unset($_SESSION['promotion_code']);
            header("Location: " . BASE_URL . "order?promo=invalid_input");
            exit;
        }

        // ✅ Lưu vào session trước, sẽ áp dụng vào DB khi tạo order
        $_SESSION['promotion_code'] = $code;

        // ✅ Nếu có order_id thực sự (là số) thì mới ghi vào DB
        if ($orderId !== null && is_numeric($orderId) && $orderId != session_id()) {
            $orderId = (int)$orderId;
            $promotionApi->applyPromotionToOrder($code, $orderId);
        }

        header("Location: " . BASE_URL . "order?promo=applied");
        exit;
    }

    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $orderId = $_GET['order_id'] ?? null;

        // ✅ Xóa khỏi session
        unset($_SESSION['promotion_code']);

        // ✅ Nếu có order_id thực sự thì xóa khỏi DB
        if ($orderId && is_numeric($orderId) && $orderId != session_id()) {
            $promotionApi = new PromotionApi();
            // TODO: Thêm method removePromotionFromOrder($orderId)
            // $promotionApi->removePromotionFromOrder((int)$orderId);
        }

        header("Location: " . BASE_URL . "order?promo=removed");
        exit;
    }


    
}