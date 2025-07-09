<?php

require_once __DIR__ . '/../../database/database.php';

class ProductModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function getAllProducts(): array
    {
        $products = [];

        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }
}
