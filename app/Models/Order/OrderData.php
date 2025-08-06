<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class OrderData extends BaseQuery
{
    public function getAllOrders(): array
    {
        $sql = "
            SELECT 
                o.id_orders,
                o.status,
                o.total_price,
                o.total_quantity,
                o.created_at,
                a.full_name,
                i.payment_method,
                i.amount_paid,
                i.earned_points
            FROM orders o
            JOIN accounts a ON o.id_accounts = a.id_accounts
            LEFT JOIN invoices i ON o.id_orders = i.id_orders
            ORDER BY o.created_at DESC
        ";
        return $this->fetchAll($sql);
    }

    public function getOrderById(int $orderId): ?array
    {
        $sql = "SELECT * FROM orders WHERE id_orders = :id";
        return $this->fetchOne($sql, ['id' => $orderId]);
    }


    public function getTotalsFromDatabase(int $userId): array
    {
        require_once __DIR__ . '/../Core/Database.php';

        $db = Database::getInstance()->getConnection();

        $stmtCart = $db->prepare("
            SELECT total_quantity, total_price 
            FROM carts 
            WHERE id_accounts = :userId 
            ORDER BY created_at DESC 
            LIMIT 1

        ");
        $stmtCart->execute(['userId' => $userId]);
        $cartId = $stmtCart->fetchColumn();

        if (!$cartId) {
            return [
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'total_quantity' => 0
            ];
        }

        $stmtTotal = $db->prepare("
            SELECT 
                SUM(ci.price * ci.quantity) AS subtotal,
                SUM(ci.quantity) AS total_quantity
            FROM cart_items ci
            WHERE ci.id_cart = :cartId
        ");
        $stmtTotal->execute(['cartId' => $cartId]);
        $result = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        $subtotal = (int)($result['subtotal'] ?? 0);
        $totalQty = (int)($result['total_quantity'] ?? 0);
        
        return [
            'subtotal' => $subtotal,
            'discount' => 0, // sau này áp mã KMK sẽ xử lý
            'total'    => $subtotal,
            'total_quantity' => $totalQty
        ];
    }



    public function createOrderWithInvoice(array $orderData, array $invoiceData): bool
    {
        try {
            $this->connection->beginTransaction();

            $orderSql = "
                INSERT INTO orders (id_accounts, total_price, total_quantity, status, created_at)
                VALUES (:id_accounts, :total_price, :total_quantity, :status, NOW())
            ";

            $orderId = $this->insert($orderSql, [
                'id_accounts'     => $orderData['id_accounts'],
                'total_price'     => $orderData['total_price'],
                'total_quantity'  => $orderData['total_quantity'],
                'status'          => $orderData['status']
            ]);

            $invoiceSql = "
                INSERT INTO invoices (id_orders, payment_method, amount_paid, issued_at, is_paid, earned_points)
                VALUES (:id_orders, :payment_method, :amount_paid, NOW(), :is_paid, :earned_points)
            ";

            $this->insert($invoiceSql, [
                'id_orders'       => $orderId,
                'payment_method'  => $invoiceData['payment_method'],
                'amount_paid'     => $invoiceData['amount_paid'],
                'is_paid'         => $invoiceData['is_paid'],
                'earned_points'   => $invoiceData['earned_points']
            ]);

            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log('❌ Lỗi tạo đơn hàng: ' . $e->getMessage());
            return false;
        }
    }


    public function updateOrder(array $data): bool
    {
        // Danh sách giá trị status hợp lệ
        $validStatuses = ['pending', 'confirmed', 'delivered', 'cancelled'];

        // Nếu status truyền vào không hợp lệ thì return false
        if (!in_array($data['status'], $validStatuses, true)) {
            error_log("❌ Trạng thái không hợp lệ: " . $data['status']);
            return false;
        }

        $sql = "
            UPDATE orders 
            SET 
                total_price = :total_price,
                total_quantity = :total_quantity,
                status = :status
            WHERE id_orders = :id_orders
        ";

        $updated = $this->update($sql, [
            'total_price'     => $data['total_price'],
            'total_quantity'  => $data['total_quantity'],
            'status'          => $data['status'],
            'id_orders'       => $data['id_orders']
        ]);

        return $updated > 0;
    }


    public function deleteOrderById(int $id): bool
    {
        try {
            $this->connection->beginTransaction();

            // 🔍 1. Hóa đơn
            $invoiceIds = $this->fetchAllColumn("
                SELECT id_invoice FROM invoices WHERE id_orders = :id_orders
            ", ['id_orders' => $id]);

            if (!empty($invoiceIds)) {
                $invoiceIds = array_values($invoiceIds); // ✅ FIX
                $placeholders = implode(',', array_fill(0, count($invoiceIds), '?'));

                $this->delete("
                    DELETE FROM invoice_history WHERE id_invoice IN ($placeholders)
                ", $invoiceIds);

                $this->delete("
                    DELETE FROM point_transactions WHERE id_invoice IN ($placeholders)
                ", $invoiceIds);
            }

            $this->delete("
                DELETE FROM invoices WHERE id_orders = :id_orders
            ", ['id_orders' => $id]);

            // 🔍 2. Giao hàng
            $deliveryIds = $this->fetchAllColumn("
                SELECT id_delivery FROM delivery WHERE id_orders = :id_orders
            ", ['id_orders' => $id]);

            if (!empty($deliveryIds)) {
                $deliveryIds = array_values($deliveryIds); // ✅ FIX
                $placeholders = implode(',', array_fill(0, count($deliveryIds), '?'));

                $this->delete("
                    DELETE FROM delivery_status_logs WHERE id_delivery IN ($placeholders)
                ", $deliveryIds);
            }

            $this->delete("
                DELETE FROM delivery WHERE id_orders = :id_orders
            ", ['id_orders' => $id]);

            // 🔥 3. Items trong đơn hàng
            $this->delete("
                DELETE FROM order_items WHERE order_id = :id_orders
            ", ['id_orders' => $id]);

            // 🔥 4. Xóa đơn hàng
            $deleted = $this->delete("
                DELETE FROM orders WHERE id_orders = :id_orders
            ", ['id_orders' => $id]);

            error_log("🗑️ Đã xóa $deleted dòng trong bảng orders.");

            $this->connection->commit();
            return $deleted > 0;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("❌ Lỗi khi xóa đơn hàng ID = $id: " . $e->getMessage());
            return false;
        }
    }



    public function createOrderWithItems(int $userId, array $cartItems): int|false
    {
        if (empty($cartItems)) {
            error_log("❌ Cart trống. Không thể tạo đơn hàng.");
            return false;
        }

        try {
            $this->connection->beginTransaction();

            $totalPrice = 0;
            $totalQty = 0;

            foreach ($cartItems as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
                $totalQty   += $item['quantity'];
            }

            // Ghi log để kiểm tra cart
            error_log("🛒 CartItems: " . json_encode($cartItems));

            // 1. Tạo đơn hàng
            $orderId = $this->insert("
                INSERT INTO orders (id_accounts, total_price, total_quantity, status)
                VALUES (:userId, :total_price, :total_quantity, 'pending')
            ", [
                'userId'        => $userId,
                'total_price'   => $totalPrice,
                'total_quantity'=> $totalQty
            ]);

            // 2. Thêm từng item vào bảng order_items
            $sqlInsertItem = "
                INSERT INTO order_items (order_id, product_id, quantity, price, note)
                VALUES (:order_id, :product_id, :quantity, :price, :note)
            ";

            foreach ($cartItems as $item) {
                $this->executeQuery($sqlInsertItem, [
                    'order_id'   => $orderId,
                    'product_id' => $item['id'],   // ✅ sửa lại đây
                    'quantity'   => $item['quantity'] ?? 0,
                    'price'      => $item['price'] ?? 0,
                    'note'       => $item['note'] ?? null
                ]);
            }


            $this->connection->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("❌ Lỗi tạo đơn hàng: " . $e->getMessage());
            return false;
        }
    }




    public function getOrderItems(int $orderId): array
    {
        $sql = "
            SELECT 
                oi.product_id,
                p.name,
                oi.quantity,
                oi.price,
                pi.image_url
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id_products
            LEFT JOIN product_images pi ON p.id_products = pi.id_products AND pi.is_main = 1
            WHERE oi.order_id = :order_id
        ";

        return $this->fetchAll($sql, ['order_id' => $orderId]);
    }


    public function getOrderHistoryByUser(int $userId): array
    {
        $sql = "
            SELECT
                o.id_orders,
                o.status            AS order_status,
                o.created_at        AS order_date,
                d.delivery_status,
                d.shipped_at,
                d.delivered_at,
                GROUP_CONCAT(CONCAT(p.name, ' x', oi.quantity) SEPARATOR ', ') AS items,
                SUM(oi.price * oi.quantity)                                AS total_price
            FROM orders o
            JOIN delivery d ON o.id_orders = d.id_orders
            JOIN order_items oi ON o.id_orders = oi.order_id
            JOIN products p    ON oi.product_id = p.id_products
            WHERE o.id_accounts = :userId
            GROUP BY o.id_orders, d.delivery_status, d.shipped_at, d.delivered_at
            ORDER BY o.created_at DESC
        ";
        return $this->fetchAll($sql, ['userId' => $userId]);
    }


    public function getOrdersByAccount(int $userId): array
    {
        return $this->fetchAll(
            "SELECT * FROM orders WHERE id_accounts = :uid ORDER BY created_at DESC",
            ['uid' => $userId]
        );
    }

}
