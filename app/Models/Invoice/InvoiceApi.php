<?php

require_once __DIR__ . '/InvoiceData.php';
require_once __DIR__ . '/../Promotion/PromotionApi.php';

class InvoiceApi
{
    private InvoiceData $invoiceData;

    public function __construct()
    {
        $this->invoiceData = new InvoiceData();
    }

    public function createInvoiceSimple(int $orderId, int $userId, string $paymentMethod): ?int
    {
        $orderData = new OrderData();
        $order = $orderData->getOrderById($orderId);

        if (!$order) return null;

        require_once __DIR__ . '/../Promotion/PromotionApi.php';
        $promotionCode = $_SESSION['promotion_code'] ?? null;

        $promotionApi = new PromotionApi();
        $discount = $promotionApi->calculateDiscount($promotionCode, (float)$order['total_price']);

        $amountPaid = max(0, $order['total_price'] - $discount);

        require_once __DIR__ . '/InvoiceData.php';
        $invoiceData = new InvoiceData();

        $params = [
            'id_orders'      => $orderId,
            'payment_method' => $paymentMethod,
            'amount_paid'    => $amountPaid,
            'is_paid'        => $paymentMethod === 'cash' ? 'unpaid' : 'paid',
            'earned_points'  => floor($amountPaid / 10000)
        ];

        $invoiceId = $invoiceData->createInvoice($params);

        unset($_SESSION['promotion_code']);

        return $invoiceId ?: null;
    }





    public function getInvoiceById(int $invoiceId): ?array
    {
        return $this->invoiceData->getInvoiceById($invoiceId);
    }



    /************************************** */
    // test
    public function markInvoiceAsPaid(int $invoiceId): bool
    {
        return $this->invoiceData->updateInvoicePaidStatus($invoiceId);
    }
    /************************************** */

}
