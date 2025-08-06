<?php

require_once __DIR__ . '/AccountData.php';
require_once __DIR__ . '/AuthModel.php';

class AccountApi
{
    private AccountData $accountData;
    private AuthModel $authModel;

    public function __construct()
    {
        $this->accountData = new AccountData();
        $this->authModel = new AuthModel();
    }

    public function getAll(): array
    {
        return $this->accountData->getAllAccounts();
    }

    public function createAccountWithMembership(array $formData): int
    {
        $accountData = [
            'username' => $formData['phone'],
            'password' => password_hash($formData['password'], PASSWORD_DEFAULT),
            'email' => $formData['email'],
            'full_name' => $formData['fullName'],
            'role' => in_array($formData['role'], ['admin', 'staff', 'shipper', 'user']) 
                        ? $formData['role'] 
                        : 'user',
            'is_active' => $formData['accountStatus'],
            'verification_token' => null,
            'token_expiry' => null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $level = isset($formData['membershipLevel']) && is_numeric($formData['membershipLevel']) 
            ? (int)$formData['membershipLevel'] 
            : 0;

        if ($level <= 0) {
            $level = null;
        }

        return $this->authModel->createAccount(
            $accountData,
            $formData['avatarUrl'],
            (int)$formData['points'],
            $level
        );
    }


    public function getMembershipLevels(): array
    {
        return $this->accountData->getAllMembershipLevels();
    }


    public function updateAccountWithMembership(array $data): void
    {
        $sql = "UPDATE accounts SET 
                    username = :username,
                    email = :email,
                    full_name = :full_name,
                    role = :role,
                    is_active = :is_active,
                    avatar_url = :avatar_url"
                . (isset($data['password']) ? ", password = :password" : "") . "
                WHERE id_accounts = :id_accounts";

        $params = [
            'username'      => $data['username'],
            'email'         => $data['email'],
            'full_name'     => $data['full_name'],
            'role'          => $data['role'],
            'is_active'     => $data['is_active'],
            'avatar_url'    => $data['avatar_url'],
            'id_accounts'   => $data['id_accounts'],
        ];

        if (isset($data['password'])) {
            $params['password'] = $data['password'];
        }

        $this->accountData->updateAccountRaw($sql, $params);

        $exists = $this->accountData->findMembershipByAccountId($data['id_accounts']);

        if ($exists) {
            $updateMembershipSql = "
                UPDATE user_memberships SET 
                    id_level = :level, 
                    point_balance = :points 
                WHERE id_accounts = :id";

            $this->accountData->updateAccountRaw($updateMembershipSql, [
                'level' => $data['level'],
                'points' => $data['points'],
                'id' => $data['id_accounts']
            ]);
        } else {
            $insertMembershipSql = "
                INSERT INTO user_memberships (id_accounts, id_level, point_balance, joined_at) 
                VALUES (:id, :level, :points, NOW())";

            $this->accountData->insertMembershipRaw($insertMembershipSql, [
                'id' => $data['id_accounts'],
                'level' => $data['level'],
                'points' => $data['points']
            ]);
        }
    }

    public function deleteAccountById(int $id): void
    {
        $membership = $this->accountData->findMembershipByAccountId($id);
        if ($membership) {
            $membershipId = $membership['id_memberships'];
            $this->accountData->deletePointTransactionsByMembership($membershipId);
            $this->accountData->deleteMembershipsByAccountId($id);
        }

        $cart = $this->accountData->findCartByAccountId($id);
        if ($cart) {
            $this->accountData->deleteCartItemsByCartId($cart['id_cart']);
            $this->accountData->deleteCartByAccountId($id);
        }

        $this->authModel->deleteUser($id);

        $this->accountData->deleteDeliveryLogsByAccountId($id);
        $this->accountData->deleteDeliveriesByAccountId($id);
    }


    public function getAccountProfile(int $accountId): ?array
    {
        return $this->accountData->getAccountById($accountId);
    }


    public function updateProfileInfo(array $data): void
    {
        $sql = "UPDATE accounts SET
                    full_name = :full_name,
                    email = :email,
                    username = :username,
                    birthday = :birthday,
                    gender = :gender,
                    city = :city,
                    address = :address
                WHERE id_accounts = :id_accounts";

        $this->accountData->updateAccountRaw($sql, $data);
    }






    
}
