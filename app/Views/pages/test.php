<?php 
require_once __DIR__ . '/../../Models/Carts/CartApi.php';

$cart = new CartApi();
$cart->addItem([
    'id' => 1,
    'name' => 'Pizza',
    'price' => 89000,
    'quantity' => 1,
    'image' => 'pizza.jpg'
]);

print_r($cart->getCart());
