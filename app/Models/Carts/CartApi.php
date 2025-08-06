<?php

require_once __DIR__ . '/CartSessionStorage.php';
require_once __DIR__ . '/CartDatabaseStorage.php';
require_once __DIR__ . '/../../helpers/auth_helpers.php';

class CartApi
{
    private CartSessionStorage|CartDatabaseStorage $storage;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isLoggedInWithValidToken() && isset($_SESSION['user']['id'])) {
            $this->storage = new CartDatabaseStorage((int)$_SESSION['user']['id']);
        } else {
            $this->storage = new CartSessionStorage();
        }
    }

    public function getCart(): array
    {
        return $this->storage->getCart();
    }

    public function getTotals(): array
    {
        $items = $this->getCart();
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;

        // Nếu có ưu đãi đang áp dụng trong session
        if (!empty($_SESSION['promotion'])) {
            $promotion = $_SESSION['promotion'];
            if ($promotion['discount_type'] === 'amount') {
                $discount = (float)$promotion['value'];
            } elseif ($promotion['discount_type'] === 'percent') {
                $discount = $subtotal * ((float)$promotion['value'] / 100);
            }
        }

        return [
            'total_price' => $subtotal,
            'discount'    => $discount,
            'final_total' => $subtotal - $discount
        ];
    }


    public function addItem(array $item): void
    {
        $this->storage->addItem($item);
    }

    public function changeQuantity(int $productId, int $change): void
    {
        $this->storage->changeQuantity($productId, $change);
    }

    public function removeItem(int $productId): void
    {
        $this->storage->removeItem($productId);
    }

    public function clearCart(): void
    {
        $this->storage->clear();
    }
}
