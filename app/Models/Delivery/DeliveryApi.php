<?php

require_once __DIR__ . '/DeliveryData.php';

class DeliveryApi
{
    private DeliveryData $deliveryData;

    public function __construct()
    {
        $this->deliveryData = new DeliveryData();
    }

    public function getAll(): array
    {
        return $this->deliveryData->getAllDeliveries();
    }

    public function updateDelivery(array $data): bool
    {
        return $this->deliveryData->updateDelivery($data);
    }

    public function getOrders(): array
    {
        return $this->deliveryData->getOrders();
    }

    public function getShippers(): array
    {
        return $this->deliveryData->getShippers();
    }

    public function createDelivery(array $data): bool
    {
        return $this->deliveryData->createDelivery($data);
    }

    public function deleteDeliveryById(int $id): bool
    {
        return $this->deliveryData->deleteDeliveryById($id);
    }


    public function getDeliveryByOrderId(int $orderId): ?array
    {
        return $this->deliveryData->getByOrderId($orderId);
    }
}
