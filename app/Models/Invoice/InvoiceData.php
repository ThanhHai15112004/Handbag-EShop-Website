<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class InvoiceData extends BaseQuery
{
    public function getOrderById(int $orderId): ?array
    {
        return $this->fetchOne("SELECT * FROM orders WHERE id_orders = :id", ['id' => $orderId]);
    }

    public function insertInvoice(array $data): ?int
    {
        $sql = "
            INSERT INTO invoices (id_orders, payment_method, amount_paid, earned_points, issued_at, is_paid)
            VALUES (:id_orders, :payment_method, :amount_paid, :earned_points, NOW(), :is_paid)
        ";
        return $this->insert($sql, $data);
    }


    public function getMembershipByAccount(int $userId): ?array
    {
        return $this->fetchOne(
            "SELECT * FROM user_memberships WHERE id_accounts = :id",
            ['id' => $userId]
        );
    }

    public function createMembership(int $userId, int $points): bool
    {
        $sql = "INSERT INTO user_memberships (id_accounts, id_level, point_balance, joined_at)
                VALUES (:id, :id_level, :points, NOW())";

        return $this->executeQuery($sql, [
            'id' => $userId,
            'id_level' => 3,  // mặc định 'Đồng'
            'points' => $points
        ]) ? true : false;
    }

    public function addPoints(int $userId, int $points): bool
    {
        $sql = "UPDATE user_memberships 
                SET point_balance = point_balance + :points 
                WHERE id_accounts = :id";

        return $this->executeQuery($sql, ['points' => $points, 'id' => $userId]) ? true : false;
    }

    public function updateMembershipPoints(int $membershipId, int $points, float $amount): void
    {
        $this->executeQuery("
            UPDATE user_memberships 
            SET 
                point_balance = point_balance + :points,
                total_spent = total_spent + :amount,
                updated_at = NOW()
            WHERE id_memberships = :id
        ", [
            'points' => $points,
            'amount' => $amount,
            'id' => $membershipId
        ]);
    }

    public function logPointTransaction(array $data): void
    {
        $this->insert("
            INSERT INTO point_transactions (id_memberships, id_invoice, points_changed, reason)
            VALUES (:id_memberships, :id_invoice, :points_changed, :reason)
        ", $data);
    }

    public function getInvoiceById(int $id): ?array
    {
        $sql = "
            SELECT 
                i.id_invoice,
                i.id_orders,
                i.amount_paid,
                i.payment_method,
                i.earned_points,
                i.issued_at
            FROM invoices i
            WHERE i.id_invoice = :id
        ";

        return $this->fetchOne($sql, ['id' => $id]);
    }



    public function logInvoiceHistory(array $data): void
    {
        $this->insert("
            INSERT INTO invoice_history (id_accounts, id_invoice, action, changed_by, timestamp, note)
            VALUES (:id_accounts, :id_invoice, :action, :changed_by, NOW(), :note)
        ", $data);
    }

    public function createInvoice(array $params): ?int
    {
        $sql = "INSERT INTO invoices (id_orders, payment_method, amount_paid, issued_at, is_paid, earned_points)
                VALUES (:id_orders, :payment_method, :amount_paid, NOW(), :is_paid, :earned_points)";

        return $this->insert($sql, $params);
    }



    public function createInvoiceWithTransaction(array $order, int $userId, string $paymentMethod): ?int
    {
        try {
            $this->connection->beginTransaction();

            // Tính điểm
            $amountPaid = (float)$order['total_price'];
            $earnedPoints = floor($amountPaid / 10000);

            // Tạo hóa đơn
            $invoiceId = $this->insertInvoice([
                'id_orders' => $order['id_orders'],
                'payment_method' => $paymentMethod,
                'amount_paid' => $amountPaid,
                'earned_points' => $earnedPoints
            ]);

            if (!$invoiceId) {
                $this->connection->rollBack();
                return null;
            }

            $membership = $this->getMembershipByAccount($userId);

            if ($membership) {
                $this->updateMembershipPoints(
                    $membership['id_memberships'],
                    $earnedPoints,
                    $amountPaid
                );

                $this->logPointTransaction([
                    'id_memberships' => $membership['id_memberships'],
                    'id_invoice' => $invoiceId,
                    'points_changed' => $earnedPoints,
                    'reason' => "Tích điểm từ đơn hàng #" . $order['id_orders']
                ]);
            } else {
                $this->createMembership($userId, $earnedPoints);
            }

            $this->connection->commit();
            return $invoiceId;
        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("🔥 Transaction failed: " . $e->getMessage());
            return null;
        }
    }



    public function getUnpaidExpiredInvoices(int $minMinutes = 5, int $maxMinutes = 15): array
    {
        $sql = "
            SELECT i.id_invoice, i.id_orders, i.issued_at, i.is_paid, a.id_accounts
            FROM invoices i
            JOIN orders o ON i.id_orders = o.id_orders
            JOIN accounts a ON o.id_accounts = a.id_accounts
            WHERE i.is_paid = 'unpaid'
            AND i.issued_at <= NOW() - INTERVAL :min_minute MINUTE
            AND i.issued_at >= NOW() - INTERVAL :max_minute MINUTE
        ";

        return $this->fetchAll($sql, [
            'min_minute' => $minMinutes,
            'max_minute' => $maxMinutes
        ]);
    }


    /*************************************************** */
    //test
    public function updateInvoicePaidStatus(int $invoiceId): bool
    {
        $sql = "UPDATE invoices SET is_paid = 'paid' WHERE id_invoice = :id";
        return $this->executeQuery($sql, ['id' => $invoiceId]) ? true : false;
    }


    /******************************************************* */
}
