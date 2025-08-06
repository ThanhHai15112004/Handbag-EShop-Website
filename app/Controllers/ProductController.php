<?php

require_once __DIR__ . '/../Models/Products/ProductApi.php';

class ProductController
{
    private ProductApi $productApi;

    public function __construct()
    {
        $this->productApi = new ProductApi();
    }

    public function showProductList(): array
    {
        return $this->productApi->getAllProducts();
    }

    public function showProductDetail(int $productId): ?ProductModel
    {
        return $this->productApi->getProductById($productId);
    }

    public function showProductsByCategory(int $categoryId): array
    {
        return $this->productApi->getProductsByCategory($categoryId);
    }

    public function getProductImages(int $productId): array
    {
        return $this->productApi->getProductImages($productId);
    }

    public function getActiveCategories(): array
    {
        return $this->productApi->getActiveCategories();
    }

    public function showBannerProducts(): array
    {
        return $this->productApi->getBannerProducts();
    }

    public function getSubImages(int $productId): array
    {
        return $this->productApi->getSubImages($productId);
    }

    public function createProduct(array $data): int
    {
        return $this->productApi->createProduct($data);
    }

    public function updateProduct(int $productId, array $data): void
    {
        $this->productApi->updateProduct($productId, $data);
    }

    public function deleteProduct(int $productId): void
    {
        $this->productApi->deleteProduct($productId);
    }

    public function createCategory(array $data): bool
    {
        return $this->productApi->createCategory($data);
    }

}
