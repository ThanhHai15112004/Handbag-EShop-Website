<?php

require_once __DIR__ . '/ProductData.php';
require_once __DIR__ . '/ProductModel.php'; // Entity

class ProductApi
{
    private ProductData $productData;

    public function __construct()
    {
        $this->productData = new ProductData();
    }

    public function getActiveCategories(): array
    {
        return $this->productData->getAllCategories();
    }

    public function getAllProducts(): array
    {
        if (!method_exists($this->productData, 'getAllAvailableProducts')) {
            return [];
        }

        $rawProducts = $this->productData->getAllAvailableProducts();

        return array_map(function ($row) {
            return new ProductModel($row);
        }, $rawProducts);
    }

    

    public function getProductById(int $productId): ?ProductModel
    {
        $row = $this->productData->getProductById($productId);

        return $row ? new ProductModel($row) : null;
    }

    public function getProductsByCategory(int $categoryId): array
    {
        $rawProducts = $this->productData->getProductsByCategory($categoryId);

        return array_map(function ($row) {
            return new ProductModel($row);
        }, $rawProducts);
    }

    public function getProductImages(int $productId): array
    {
        return $this->productData->getAllImagesByProductId($productId);
    }

    public function getBannerProducts(): array
    {
        $rawProducts = $this->productData->getBannerProducts();

        return array_map(function ($row) {
            return new ProductModel($row);
        }, $rawProducts);
    }

    public function getSubImages(int $productId): array
    {
        return $this->productData->getSubImagesByProductId($productId);
    }

    public function createProduct(array $productData, array $images): bool
    {
        return $this->productData->createProduct($productData, $images);
    }

    public function updateProduct(array $data, array $images = []): bool
    {
        return $this->productData->updateProduct($data);
    }


    public function deleteProduct(int $productId): void
    {
        $this->productData->deleteProduct($productId);
    }

    public function createCategory(array $categoryData): bool
    {
        $productData = new ProductData();
        return $productData->createCategory($categoryData);
    }

    public function updateCategory(array $categoryData): bool
    {
        $productData = new ProductData();
        return $productData->updateCategory($categoryData);
    }


    public function deleteCategory(int $categoryId): void
    {
        $productData = new ProductData();
        $productData->deleteCategory($categoryId);
    }



}
