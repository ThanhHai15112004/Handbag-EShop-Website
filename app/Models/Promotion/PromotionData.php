<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class PromotionData extends BaseQuery
{
    
    public function getByCode(string $code): ?array
    {
        $sql = "SELECT * FROM promotions WHERE code = :code LIMIT 1";
        return $this->fetchOne($sql, ['code' => $code]);
    }

    public function getAllValid(): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * 
                FROM promotions 
                WHERE is_active = 1 
                AND start_date <= :now 
                AND end_date >= :now
                ORDER BY end_date ASC";

        return $this->fetchAll($sql, ['now' => $now]);
    }

    public function attachPromotionToOrder(int $promotionId, int $orderId): bool
    {
        $sql = "INSERT INTO promotion_order (id_promotions, id_orders) 
                VALUES (:promotionId, :orderId)";
        return $this->execute($sql, [
            'promotionId' => $promotionId,
            'orderId'     => $orderId
        ]);
    }

    public function getPromotionsByOrder(int $orderId): array
    {
        $sql = "
            SELECT p.*
            FROM promotions p
            JOIN promotion_order po ON p.id_promotions = po.id_promotions
            WHERE po.id_orders = :orderId
        ";
        return $this->fetchAll($sql, ['orderId' => $orderId]);
    }

    public function checkPromotionAttached(int $promotionId, int $orderId): bool
    {
        $sql = "SELECT COUNT(*) as count 
                FROM promotion_order 
                WHERE id_promotions = :promotionId AND id_orders = :orderId";
        $result = $this->fetchOne($sql, [
            'promotionId' => $promotionId,
            'orderId'     => $orderId
        ]);
        return $result && $result['count'] > 0;
    }
}
