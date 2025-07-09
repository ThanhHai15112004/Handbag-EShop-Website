<?php

require_once __DIR__ . '/../Models/ProductModel.php';

class ProductService
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function fetchProductList(): array
    {
        return $this->productModel->getAllProducts();
    }
}
