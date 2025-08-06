<?php

class ProductModel
{
    public int $productId;
    public int $categoryId;
    public string $name;
    public float $price;
    public ?string $description;
    public bool $isAvailable;
    public ?string $createdAt;
    public ?string $updatedAt;

    public ?string $categoryName;
    public ?string $imageUrl;

    public ?float $calo;
    public ?float $protein;
    public ?float $carbohydrate;
    public ?float $fat;
    public ?float $gram;

    public function __construct(array $data)
    {
        $this->productId   = (int)($data['id_products'] ?? 0);
        $this->categoryId  = (int)($data['id_categories'] ?? 0);
        $this->name        = $data['name'] ?? '';
        $this->price       = (float)($data['price'] ?? 0);
        $this->description = $data['description'] ?? null;
        $this->isAvailable = (bool)($data['is_available'] ?? true);
        $this->createdAt   = $data['created_at'] ?? null;
        $this->updatedAt   = $data['updated_at'] ?? null;

        $this->categoryName = $data['category_name'] ?? null;
        $this->imageUrl     = $data['image_url'] ?? null;

        $this->gram         = isset($data['gram']) ? (float)$data['gram'] : null;
        $this->calo         = isset($data['calo']) ? (float)$data['calo'] : null;
        $this->protein      = isset($data['protein']) ? (float)$data['protein'] : null;
        $this->carbohydrate = isset($data['carbohydrate']) ? (float)$data['carbohydrate'] : null;
        $this->fat          = isset($data['fat']) ? (float)$data['fat'] : null;
    }

    public function getFormattedNameWithPrice(): string
    {
        return $this->name . ' - ' . number_format($this->price, 0, ',', '.') . 'đ';
    }

    public function isInStock(): bool
    {
        return $this->isAvailable;
    }

    public function getCaloriesInfo(): string
    {
        return ($this->calo ?? 0) . ' Cal';
    }

    public function getNutritionText(): string
    {
        $parts = [];

        if ($this->calo !== null)         $parts[] = "{$this->calo} Cal";
        if ($this->gram !== null)        $parts[] = "{$this->gram}g";
        return implode(' / ', $parts);
    }

    
}
