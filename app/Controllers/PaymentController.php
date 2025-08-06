    <?php

    require_once __DIR__ . '/../Models/Invoice/InvoiceApi.php';
    require_once __DIR__ . '/../Models/Delivery/DeliveryApi.php';
    require_once __DIR__ . '/../Models/Order/OrderApi.php';
    require_once __DIR__ . '/../configs/config.php'; 


    class PaymentController
    {
        public function showPaymentPage(): void
        {
            if (session_status() === PHP_SESSION_NONE) session_start();

            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . BASE_URL . "login");
                exit;
            }

            $invoiceId = $_GET['invoice_id'] ?? 0;

            if (!$invoiceId) {
                header("Location: " . BASE_URL . "order?error=missing_invoice");
                exit;
            }

            $invoiceApi = new InvoiceApi();
            $orderApi = new OrderApi();

            $invoice = $invoiceApi->getInvoiceById((int)$invoiceId);


            if (!$invoice) {
                header("Location: " . BASE_URL . "order?error=invoice_not_found");
                exit;
            }

            $order = $orderApi->getOrderById((int)$invoice['id_orders']);

            if (!$order) {
                header("Location: " . BASE_URL . "order?error=order_not_found");
                exit;
            }

            // Tạo URL VietQR
            $bankId = BANK_ID;
            $accountNo = ACCOUNT_NO;
            $accountName = ACCOUNT_NAME;
            $template = VIETQR_TEMPLATE;

            $amount = (int)$invoice['amount_paid'];
            $description = 'ttdh' . $order['id_orders']; // Ví dụ: ttdh24162

            $vietQrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.jpg?" .
                        "amount={$amount}&addInfo=" . urlencode($description) .
                        "&accountName=" . urlencode($accountName);

            // Render view
            require_once __DIR__ . '/../Views/pages/payment-qr.php';
        }




        public function createInvoiceAndDelivery(): void
        {
            if (session_status() === PHP_SESSION_NONE) session_start();

            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . BASE_URL . "login");
                exit;
            }

            $userId = $_SESSION['user']['id'];
            $orderId = $_GET['order_id'] ?? 0;
            $shippingAddress = $_SESSION['shipping_address'] ?? '';
            

            if (!$orderId || empty($shippingAddress)) {
                header("Location: " . BASE_URL . "order?error=missing_info");
                exit;
            }

            $method = $_GET['method'] ?? 'banking';
            $paymentMethod = in_array($method, ['banking', 'cash', 'visa']) ? $method : 'banking';
            $isCod = ($paymentMethod === 'cash');


            $invoiceApi = new InvoiceApi();
            $invoiceId = $invoiceApi->createInvoiceSimple((int)$orderId, (int)$userId, $paymentMethod);



            if (!$invoiceId) {
                header("Location: " . BASE_URL . "payment?order_id=$orderId&error=invoice_failed");
                exit;
            }

            if ($isCod) {
                // Trạng thái hóa đơn là unpaid, bỏ qua QR
                header("Location: " . BASE_URL . "payment-success");
                exit;
            }



            $deliveryApi = new DeliveryApi();
            $success = $deliveryApi->createDelivery([
                'id_orders' => $orderId,
                'id_accounts' => $userId,
                'shipping_address' => $shippingAddress,
                'delivery_status' => 'pending'
            ]);

            if (!$success) {
                header("Location: " . BASE_URL . "payment?order_id=$orderId&error=delivery_failed");
                exit;
            }

            unset($_SESSION['shipping_address']);

            header("Location: " . BASE_URL . "payment-qr?invoice_id=$invoiceId");
            exit;
        }



        public function showPaymentSuccessPage(): void
        {
            if (session_status() === PHP_SESSION_NONE) session_start();

            // Nếu muốn, có thể kiểm tra thêm quyền/hoá đơn ở đây

            require_once __DIR__ . '/../Views/pages/payment-success.php';
        }





        //***************************** */
        // test
        public function mockPaymentSuccess(): void
        {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $invoiceId = $_GET['invoice_id'] ?? 0;

            if (!$invoiceId) {
                header("Location: " . BASE_URL . "payment-qr?error=missing_invoice");
                exit;
            }

            $invoiceApi = new InvoiceApi();
            $updated = $invoiceApi->markInvoiceAsPaid((int)$invoiceId);

            if ($updated) {
                header("Location: " . BASE_URL . "payment-success");
                exit;
            } else {
                header("Location: " . BASE_URL . "payment-qr?invoice_id=$invoiceId&error=update_failed");
                exit;
            }
        }
        /****************************************************** */

    }