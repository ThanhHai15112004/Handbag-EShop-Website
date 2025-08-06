<?php

class CartSessionStorage
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->initializeSessionCart();
    }

    private function initializeSessionCart(): void
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function getCart(): array
    {
        return $_SESSION['cart'];
    }

    public function addItem(array $item): void
    {
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['id'] === $item['id']) {
                $cartItem['quantity'] += $item['quantity'];
                return;
            }
        }

        $_SESSION['cart'][] = $item;
    }


    public function changeQuantity(int $productId, int $change): void
    {
        foreach ($_SESSION['cart'] as $key => &$cartItem) {
            if ($cartItem['id'] === $productId) {
                $cartItem['quantity'] += $change;

                if ($cartItem['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }

                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }


    public function removeItem(int $productId): void
    {
        foreach ($_SESSION['cart'] as $key => $cartItem) {
            if ($cartItem['id'] === $productId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }

    public function getTotals(): array
    {
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($_SESSION['cart'] as $item) {
            $totalQuantity += $item['quantity'];
            $totalPrice += $item['quantity'] * $item['price'];
        }

        return [
            'total_quantity' => $totalQuantity,
            'total_price' => $totalPrice
        ];
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }
}
