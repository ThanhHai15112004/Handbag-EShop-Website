<?php

require_once __DIR__ . '/../Models/Carts/CartApi.php';
require_once __DIR__ . '/../configs/config.php';


class CartController
{
    private CartApi $cartApi;

    public function __construct()
    {
        $this->cartApi = new CartApi();
    }


    public function addToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id_products'] ?? 0);
            $name = $_POST['name'] ?? '';
            $price = (float)($_POST['price'] ?? 0);
            $image = $_POST['image_url'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);

            if ($id && $name && $price > 0 && $quantity > 0) {
                $this->cartApi->addItem([
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'image_url' => $image,
                    'quantity' => $quantity
                ]);
            }

            // Quay về trang hiện tại hoặc mở popup tuỳ bạn
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }



    public function removeFromCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $productId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

            if ($productId > 0) {
                $this->cartApi->removeItem($productId);
            }

            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }


    public function updateQuantity(): void
    {
        $productId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $change = (int)($_POST['change'] ?? 0);

        if (isset($_GET['id'])) {
            $change = ($_GET['route'] === 'cart-increase') ? 1 : -1;
        }

        if ($productId > 0 && $change !== 0) {
            $this->cartApi->changeQuantity($productId, $change);
        }

        // Quay lại trang trước đó
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

}
