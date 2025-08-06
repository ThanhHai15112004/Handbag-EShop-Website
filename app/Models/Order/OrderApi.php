<?php
require_once __DIR__ . '/../Core/BaseQuery.php';
require_once __DIR__ . '/OrderData.php';

class OrderApi
{
    private OrderData $orderData;

    public function __construct()
    {
        $this->orderData = new OrderData();
    }

    public function getOrderById(int $orderId): ?array
    {
        return $this->orderData->getOrderById($orderId);
    }


    public function getAllOrders(): array
    {
        return $this->orderData->getAllOrders();
    }

    public function createOrder(int $userId, array $cartItems): int|false
    {
        return $this->orderData->createOrderWithItems($userId, $cartItems);
    }


    public function createOrderWithInvoice(array $orderData, array $invoiceData): bool
    {
        return $this->orderData->createOrderWithInvoice($orderData, $invoiceData);
    }

    public function updateOrder(array $orderData): bool
    {
        return $this->orderData->updateOrder($orderData);
    }

    public function deleteOrderById(int $id): bool
    {
        return $this->orderData->deleteOrderById($id);
    }

    public function getOrderItems(int $orderId): array
    {
        return $this->orderData->getOrderItems($orderId);
    }


    public function getTotalsFromDatabase(int $userId): array
    {
        return $this->orderData->getTotalsFromDatabase($userId);
    }


    public function getOrderHistory(int $userId): array
    {
        return $this->orderData->getOrderHistoryByUser($userId);
    }

    public function getOrdersByAccount(int $userId): array
    {
        return $this->orderData->getOrdersByAccount($userId);
    }

}
