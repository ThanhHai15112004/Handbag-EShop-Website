<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class DeliveryData extends BaseQuery
{
    public function getAllDeliveries(): array
    {
        $sql = "    
            SELECT d.id_delivery, o.id_orders, a.full_name, d.shipping_address,
                   d.delivery_status, d.shipped_at, d.delivered_at
            FROM delivery d
            JOIN orders o ON d.id_orders = o.id_orders
            JOIN accounts a ON d.id_accounts = a.id_accounts
            ORDER BY d.shipped_at DESC
        ";
        return $this->fetchAll($sql);
    }

    public function updateDelivery(array $data): bool
    {
        $query = "
            UPDATE delivery 
            SET 
                id_orders = :id_orders,
                id_accounts = :id_accounts,
                shipping_address = :shipping_address,
                delivery_status = :delivery_status,
                shipped_at = :shipped_at,
                delivered_at = :delivered_at
            WHERE id_delivery = :id_delivery
        ";

        $updatedRows = $this->update($query, [
            'id_orders'        => $data['id_orders'],
            'id_accounts'      => $data['id_accounts'],
            'shipping_address' => $data['shipping_address'],
            'delivery_status'  => $data['delivery_status'],
            'shipped_at'       => $data['shipped_at'] ?? null,
            'delivered_at'     => $data['delivered_at'] ?? null,
            'id_delivery'      => $data['id_delivery'],
        ]);

        return $updatedRows > 0;
    }


    public function getOrders(): array
    {
        $stmt = $this->connection->prepare("
            SELECT id_orders, created_at FROM orders ORDER BY created_at DESC LIMIT 1000
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getShippers(): array
    {
        $stmt = $this->connection->prepare("
            SELECT id_accounts, full_name 
            FROM accounts 
            WHERE role = 'shipper' AND is_active = 1
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createDelivery(array $data): bool
    {
        $sql = "
            INSERT INTO delivery (id_orders, id_accounts, shipping_address, 
                                delivery_status, shipped_at, delivered_at)
            VALUES (:id_orders, :id_accounts, :shipping_address, 
                    :delivery_status, :shipped_at, :delivered_at)
        ";

        $id = $this->insert($sql, [
            'id_orders'        => $data['id_orders'],
            'id_accounts'      => $data['id_accounts'],
            'shipping_address' => $data['shipping_address'],
            'delivery_status'  => $data['delivery_status'],
            'shipped_at'       => $data['shipped_at'] ?? null,
            'delivered_at'     => $data['delivered_at'] ?? null,
        ]);

        return $id > 0;
    }

    public function deleteDeliveryById(int $id): bool
    {
        $sql = "DELETE FROM delivery WHERE id_delivery = :id_delivery";
        $deletedRows = $this->delete($sql, ['id_delivery' => $id]);
        return $deletedRows > 0;
    }


    public function getByOrderId(int $orderId): ?array
    {
        return $this->fetchOne(
            "SELECT * FROM delivery WHERE id_orders = :oid LIMIT 1",
            ['oid' => $orderId]
        );
    }

    

}
