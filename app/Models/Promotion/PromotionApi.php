<?php

require_once __DIR__ . '/PromotionData.php';

class PromotionApi
{
    private PromotionData $promotionData;

    public function __construct()
    {
        $this->promotionData = new PromotionData();
    }

    public function calculateDiscount(string $code, float $subtotal): float
    {
        $promo = $this->promotionData->getByCode($code);

        if (!$promo || !$promo['is_active']) {
            return 0;
        }

        $now = strtotime(date('Y-m-d H:i:s'));
        $start = strtotime($promo['start_date']);
        $end = strtotime($promo['end_date']);

        if ($now < $start || $now > $end) {
            return 0;
        }

        if ($promo['discount_type'] === 'amount') {
            return (float)$promo['value'];
        }

        if ($promo['discount_type'] === 'percent') {
            return round($subtotal * $promo['value'] / 100);
        }

        return 0;
    }

    public function getAllValid(): array
    {
        return $this->promotionData->getAllValid();
    }
    public function getByCode(string $code): ?array
    {
        return $this->promotionData->getByCode($code);
    }

    public function applyPromotionToOrder(string $code, int $orderId): bool
    {
        $promo = $this->promotionData->getByCode($code);

        if (!$promo || !$promo['is_active']) return false;

        $now = time();
        $start = strtotime($promo['start_date']);
        $end   = strtotime($promo['end_date']);

        if ($now < $start || $now > $end) return false;

        return $this->promotionData->attachPromotionToOrder($promo['id_promotions'], $orderId);
    }

    public function getPromotionsByOrder(int $orderId): array
    {
        return $this->promotionData->getPromotionsByOrder($orderId);
    }

    public function checkPromotionAttached(int $promotionId, int $orderId): bool
    {
        return $this->promotionData->checkPromotionAttached($promotionId, $orderId);
    }

    public function getPromotionByCode(string $code): ?array
    {
        return $this->promotionData->getByCode($code);
    }

}
