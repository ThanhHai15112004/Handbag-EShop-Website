<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class AccountData extends BaseQuery
{
    public function getAllAccounts(): array
    {
        $sql = "
            SELECT 
                a.id_accounts,
                a.email,
                a.full_name,
                a.username AS phone,
                a.role,
                a.avatar_url,
                a.is_active,
                a.created_at,
                m.point_balance,
                l.level_name
            FROM accounts a
            LEFT JOIN user_memberships m ON a.id_accounts = m.id_accounts
            LEFT JOIN membership_levels l ON m.id_level = l.id_level
        ";

        return $this->fetchAll($sql);
    }

    public function getAllMembershipLevels(): array
    {
        $sql = "SELECT id_level, level_name FROM membership_levels ORDER BY id_level ASC";
        return $this->fetchAll($sql);
    }
    
    public function getAccountById(int $id): ?array
    {
        $sql = "
            SELECT 
                a.id_accounts,
                a.full_name,
                a.email,
                a.username,
                a.birthday,
                a.gender,
                a.city,
                a.address,
                m.point_balance
            FROM accounts a
            LEFT JOIN user_memberships m ON a.id_accounts = m.id_accounts
            WHERE a.id_accounts = :id
        ";
        return $this->fetchOne($sql, ['id' => $id]);
    }




    public function updateAccountRaw(string $sql, array $params): int
    {
        return $this->update($sql, $params);
    }

    public function insertMembershipRaw(string $sql, array $params): int
    {
        return $this->insert($sql, $params);
    }

    public function findMembershipByAccountId(int $accountId): ?array
    {
        $sql = "SELECT id_memberships FROM user_memberships WHERE id_accounts = :id";
        return $this->fetchOne($sql, ['id' => $accountId]);
    }

    public function deleteMembershipsByAccountId(int $accountId): void
    {
        $sql = "DELETE FROM user_memberships WHERE id_accounts = :id";
        $this->updateAccountRaw($sql, ['id' => $accountId]);
    }

    public function deletePointTransactionsByMembership(int $membershipId): void
    {
        $sql = "DELETE FROM point_transactions WHERE id_memberships = :id";
        $this->updateAccountRaw($sql, ['id' => $membershipId]);
    }

    public function findCartByAccountId(int $accountId): ?array
    {
        $sql = "SELECT id_cart FROM carts WHERE id_accounts = :id";
        return $this->fetchOne($sql, ['id' => $accountId]);
    }

    public function deleteCartItemsByCartId(int $cartId): void
    {
        $sql = "DELETE FROM cart_items WHERE id_cart = :id";
        $this->updateAccountRaw($sql, ['id' => $cartId]);
    }

    public function deleteCartByAccountId(int $accountId): void
    {
        $sql = "DELETE FROM carts WHERE id_accounts = :id";
        $this->updateAccountRaw($sql, ['id' => $accountId]);
    }

    public function deleteDeliveryLogsByAccountId(int $accountId): void
    {
        $sql = "DELETE FROM delivery_status_logs WHERE id_accounts = :id OR changed_by = :id";
        $this->updateAccountRaw($sql, ['id' => $accountId]);
    }

    public function deleteDeliveriesByAccountId(int $accountId): void
    {
        $sql = "DELETE FROM delivery WHERE id_accounts = :id";
        $this->updateAccountRaw($sql, ['id' => $accountId]);
    }

    public function deleteDeliveryByAccountId(int $accountId): void
    {
        $sql = "DELETE FROM delivery WHERE id_accounts = :id OR id_accounts IN (
                    SELECT id_accounts FROM orders WHERE id_accounts = :id
                )";
        $this->updateAccountRaw($sql, ['id' => $accountId]);
    }







}
