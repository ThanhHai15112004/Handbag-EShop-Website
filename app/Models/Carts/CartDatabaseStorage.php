<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class CartDatabaseStorage extends BaseQuery
{
    private int $userId;

    public function __construct(int $userId)
    {
        parent::__construct();
        $this->userId = $userId;
    }

    public function getCart(): array
    {
        $sql = "
            SELECT 
                ci.id_products, 
                p.name, 
                ci.quantity, 
                ci.price, 
                pi.image_url
            FROM carts c
            LEFT JOIN cart_items ci ON c.id_cart = ci.id_cart
            LEFT JOIN products p ON ci.id_products = p.id_products
            LEFT JOIN product_images pi 
                ON pi.id_products = p.id_products AND pi.is_main = 1
            WHERE c.id_accounts = :userId
        ";

        $result = $this->fetchAll($sql, ['userId' => $this->userId]);

        return array_map(function ($item) {
            return [
                'id' => $item['id_products'], // đổi key này cho đúng view
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image_url' => $item['image_url']
            ];
        }, array_filter($result, fn($item) => isset($item['id_products'])));
    }

    public function addItem(array $item): void
    {
        $cartId = $this->getOrCreateCart();

        $sqlCheck = "
            SELECT quantity FROM cart_items 
            WHERE id_cart = :cartId AND id_products = :productId
        ";
        $existing = $this->fetchOne($sqlCheck, [
            'cartId' => $cartId,
            'productId' => $item['id']
        ]);

        if ($existing) {
            $this->update("
                UPDATE cart_items
                SET quantity = quantity + :quantity
                WHERE id_cart = :cartId AND id_products = :productId
            ", [
                'cartId' => $cartId,
                'productId' => $item['id'],
                'quantity' => $item['quantity']
            ]);
        } else {
            $this->insert("
                INSERT INTO cart_items (id_cart, id_products, quantity, price)
                VALUES (:cartId, :productId, :quantity, :price)
            ", [
                'cartId' => $cartId,
                'productId' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $this->updateCartTotals($cartId);
    }

    public function changeQuantity(int $productId, int $change): void
    {
        $cartId = $this->getOrCreateCart();

        $this->update("
            UPDATE cart_items
            SET quantity = quantity + :change
            WHERE id_cart = :cartId AND id_products = :productId
        ", [
            'cartId' => $cartId,
            'productId' => $productId,
            'change' => $change
        ]);

        $this->delete("
            DELETE FROM cart_items
            WHERE id_cart = :cartId AND id_products = :productId AND quantity <= 0
        ", [
            'cartId' => $cartId,
            'productId' => $productId
        ]);

        $this->updateCartTotals($cartId);
    }

    public function removeItem(int $productId): void
    {
        $cartId = $this->getOrCreateCart();

        $this->delete("
            DELETE FROM cart_items
            WHERE id_cart = :cartId AND id_products = :productId
        ", [
            'cartId' => $cartId,
            'productId' => $productId
        ]);

        $this->updateCartTotals($cartId);
    }

    public function clear(): void
    {
        $cartId = $this->getOrCreateCart();

        $this->delete("DELETE FROM cart_items WHERE id_cart = :cartId", [
            'cartId' => $cartId
        ]);

        $this->updateCartTotals($cartId);
    }

    private function getOrCreateCart(): int
    {
        $sql = "SELECT id_cart FROM carts WHERE id_accounts = :userId";
        $cart = $this->fetchOne($sql, ['userId' => $this->userId]);

        if ($cart) {
            return (int)$cart['id_cart'];
        }

        return $this->insert("
            INSERT INTO carts (id_accounts, total_quantity, total_price)
            VALUES (:userId, 0, 0)
        ", ['userId' => $this->userId]);
    }

    private function updateCartTotals(int $cartId): void
    {
        $totals = $this->fetchOne("
            SELECT 
                SUM(quantity) as total_quantity,
                SUM(quantity * price) as total_price
            FROM cart_items
            WHERE id_cart = :cartId
        ", ['cartId' => $cartId]);

        $this->update("
            UPDATE carts
            SET total_quantity = :total_quantity,
                total_price = :total_price,
                updated_at = NOW()
            WHERE id_cart = :cartId
        ", [
            'total_quantity' => $totals['total_quantity'] ?? 0,
            'total_price' => $totals['total_price'] ?? 0,
            'cartId' => $cartId
        ]);
    }


public function getTotals(): array
{
    $sql = "
        SELECT total_quantity, total_price 
        FROM carts 
        WHERE id_accounts = :userId 
        ORDER BY created_at DESC 
        LIMIT 1
    ";

    $totals = $this->fetchOne($sql, ['userId' => $this->userId]);

    return [
        'total_quantity' => $totals['total_quantity'] ?? 0,
        'total_price' => $totals['total_price'] ?? 0
    ];
}


}
