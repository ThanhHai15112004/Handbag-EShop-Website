<?php
require_once __DIR__ . '/../Core/BaseQuery.php';

class AuthModel extends BaseQuery
{

    public function findUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM accounts WHERE email = :email AND is_active = 1";
        return $this->fetchOne($sql, ['email' => $email]);
    }

    public function updateLastLogin(int $userId): void
    {
        $sql = "UPDATE accounts SET last_login_at = NOW() WHERE id_accounts = :id";
        $this->update($sql, ['id' => $userId]);
    }


    public function checkLogin(string $email, string $inputPassword): ?array
    {
        $user = $this->findUserByEmail($email);

        if (!$user) {
            return null;
        }

        $storedPassword = $user['password'];

        if (substr($storedPassword, 0, 4) === '$2y$') {
            if (!password_verify($inputPassword, $storedPassword)) {
                return null;
            }
        } else {
            if ($inputPassword !== $storedPassword) {
                return null;
            }
        }

        return $user;
    }


    public function createUser(array $data): int
    {
        $sql = "
            INSERT INTO accounts 
            (username, password, email, full_name, role, is_active, verification_token, token_expiry, created_at)
            VALUES 
            (:username, :password, :email, :full_name, :role, :is_active, :verification_token, :token_expiry, :created_at)
        ";

        return $this->insert($sql, $data);
    }


    public function createAccount(array $accountData, string $avatarUrl, int $point, ?int $level): int
    {
        $userId = $this->createUser($accountData);

        $this->update("UPDATE accounts SET avatar_url = :url WHERE id_accounts = :id", [
            'url' => $avatarUrl,
            'id' => $userId
        ]);

        if ($level) {
            $this->insert("INSERT INTO user_memberships (id_level, id_accounts, point_balance, joined_at)
                        VALUES (:id_level, :id_accounts, :point_balance, :joined_at)", [
                'id_level' => $level,
                'id_accounts' => $userId,
                'point_balance' => $point,
                'joined_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $userId;
    }




    public function findUserByToken(string $token): ?array
    {
        $sql = "SELECT * FROM accounts WHERE verification_token = :token AND is_active = 0";
        return $this->fetchOne($sql, ['token' => $token]);
    }

    public function activateUser(int $userId): void
    {
        $sql = "UPDATE accounts SET is_active = 1, verification_token = NULL WHERE id_accounts = :id";
        $this->update($sql, ['id' => $userId]);
    }

    public function deleteUser(int $userId): void
    {
        $sql = "DELETE FROM accounts WHERE id_accounts = :id";
        $this->delete($sql, ['id' => $userId]);
    }

}
