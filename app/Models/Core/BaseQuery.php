<?php

require_once __DIR__ . '/Database.php';

class BaseQuery
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    protected function executeQuery(string $sql, array $parameters = []): bool|\PDOStatement
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
            return $statement;
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    protected function fetchOne(string $sql, array $parameters = []): ?array
    {
        $statement = $this->executeQuery($sql, $parameters);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    protected function fetchAll(string $sql, array $parameters = []): array
    {
        $statement = $this->executeQuery($sql, $parameters);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insert(string $sql, array $parameters = []): int
    {
        $this->executeQuery($sql, $parameters);
        return (int)$this->connection->lastInsertId();
    }

    protected function update(string $sql, array $parameters = []): int
    {
        $statement = $this->executeQuery($sql, $parameters);
        return $statement->rowCount();
    }

    protected function delete(string $sql, array $parameters = []): int
    {
        $statement = $this->executeQuery($sql, $parameters);
        return $statement->rowCount();
    }


    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }

    public function fetchAllColumn(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }


}
