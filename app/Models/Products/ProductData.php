<?php

require_once __DIR__ . '/../Core/BaseQuery.php';

class ProductData extends BaseQuery
{
    public function getAllCategories(): array
    {
        $sql = "SELECT id_categories, name, icon_image, is_active 
                FROM categories";
        return $this->fetchAll($sql);
    }

    public function getProductsByCategory(int $categoryId): array
    {
        $sql = "
            SELECT 
                p.id_products,
                p.id_categories, 
                p.name,
                p.price,
                p.description,
                i.image_url,
                n.calo,
                n.protein,
                n.carbohydrate,
                n.fat,
                n.gram
            FROM products p
            LEFT JOIN product_images i 
                ON p.id_products = i.id_products AND i.is_main = 1
            LEFT JOIN product_nutrition n 
                ON p.id_products = n.id_products
            WHERE p.id_categories = :categoryId 
              AND p.is_available = 1
        ";

        return $this->fetchAll($sql, ['categoryId' => $categoryId]);
    }

    

    public function getProductById(int $productId): ?array
    {
        $sql = "
            SELECT 
                p.id_products,
                p.name,
                p.price,
                p.description,
                i.image_url,
                n.calo,
                n.protein,
                n.carbohydrate,
                n.fat,
                n.gram
            FROM products p
            LEFT JOIN product_images i 
                ON p.id_products = i.id_products AND i.is_main = 1
            LEFT JOIN product_nutrition n 
                ON p.id_products = n.id_products
            WHERE p.id_products = :productId 
              AND p.is_available = 1
        ";

        return $this->fetchOne($sql, ['productId' => $productId]);
    }

    public function getAllAvailableProducts(): array
    {
        $sql = "
            SELECT 
                p.id_products,
                p.id_categories,
                c.name AS category_name, -- thêm dòng này
                p.name,
                p.price,
                p.description,
                p.is_available,
                p.created_at,
                p.updated_at,
                i.image_url,
                n.calo,
                n.protein,
                n.carbohydrate,
                n.fat,
                n.gram
            FROM products p
            LEFT JOIN categories c ON p.id_categories = c.id_categories
            LEFT JOIN product_images i ON p.id_products = i.id_products AND i.is_main = 1
            LEFT JOIN product_nutrition n ON p.id_products = n.id_products

        ";

        return $this->fetchAll($sql);
    }

    public function getBannerProducts(): array
    {
        $sql = "
            SELECT 
                p.id_products,
                p.id_categories,
                p.name,
                p.price,
                p.description,
                p.is_available,
                p.created_at,
                p.updated_at,
                i.image_url,
                n.calo,
                n.gram
            FROM products p
            LEFT JOIN product_images i 
                ON p.id_products = i.id_products 
                AND i.is_banner = 1
            LEFT JOIN product_nutrition n 
                ON p.id_products = n.id_products
            WHERE p.is_banner = 1 AND p.is_available = 1
        ";

        return $this->fetchAll($sql);
    }

    public function getAllImagesByProductId(int $productId): array
    {
        $sql = "SELECT image_url
                FROM product_images
                WHERE id_products = :productId 
                AND is_main = 1 
                AND is_banner = 0
                LIMIT 1";

        return $this->fetchAll($sql, ['productId' => $productId]);
    }

    public function getSubImagesByProductId(int $productId): array
    {
        $sql = "SELECT image_url 
                FROM product_images 
                WHERE id_products = :productId 
                AND is_main = 0 
                AND is_banner = 0";

        return $this->fetchAll($sql, ['productId' => $productId]);
    }

    public function createProduct(array $productData, array $images): bool
    {
        try {
            $this->connection->beginTransaction();

            // 1. Insert vào bảng products
            $stmt = $this->connection->prepare("
                INSERT INTO products 
                    (name, price, description, id_categories, is_available, is_banner, created_at)
                VALUES 
                    (:name, :price, :description, :id_categories, :is_available, :is_banner, NOW())
            ");
            $stmt->execute([
                'name' => $productData['name'],
                'price' => $productData['price'],
                'description' => $productData['description'],
                'id_categories' => $productData['id_categories'],
                'is_available' => $productData['is_available'],
                'is_banner' => $productData['is_banner']
            ]);

            $productId = (int)$this->connection->lastInsertId();

            // 2. Insert vào bảng product_nutrition
            $stmt = $this->connection->prepare("
                INSERT INTO product_nutrition 
                    (id_products, calo, protein, carbohydrate, fat, gram)
                VALUES 
                    (:id_products, :calo, :protein, :carbohydrate, :fat, :gram)
            ");
            $stmt->execute([
                'id_products' => $productId,
                'calo' => $productData['calo'],
                'protein' => $productData['protein'],
                'carbohydrate' => $productData['carbohydrate'],
                'fat' => $productData['fat'],
                'gram' => $productData['gram']
            ]);

            // 3. Upload ảnh vào thư mục và insert vào bảng product_images
            $uploadDir = __DIR__ . '/../../../public/assets/images/uploads/';
            $relativePath = 'assets/images/uploads/';

            if (!empty($images['tmp_name'][0])) {
                for ($i = 0; $i < count($images['name']); $i++) {
                    $tmpPath = $images['tmp_name'][$i];
                    $filename = time() . '_' . basename($images['name'][$i]);
                    $targetPath = $uploadDir . $filename;

                    if (is_uploaded_file($tmpPath)) {

                        if (move_uploaded_file($tmpPath, $targetPath)) {

                            $stmt = $this->connection->prepare("
                                INSERT INTO product_images 
                                    (id_products, image_url, is_main, is_banner)
                                VALUES 
                                    (:id_products, :image_url, :is_main, :is_banner)
                            ");
                            $stmt->execute([
                                'id_products' => $productId,
                                'image_url' => $relativePath . $filename,
                                'is_main' => $i === 0 ? 1 : 0,
                                'is_banner' => ($i === 0 && $productData['is_banner']) ? 1 : 0
                            ]);

                        } else {
                            error_log("❌ move_uploaded_file failed: $tmpPath → $targetPath");
                        }

                    } else {
                        error_log("❌ Không phải file upload hợp lệ: $tmpPath");
                    }
                }
            } else {
                error_log("❌ Không có ảnh nào được upload.");
            }

            $this->connection->commit();
            return true;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("❌ Lỗi khi thêm sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function createCategory(array $categoryData): bool
    {
        try {
            // 1. Chuẩn bị thư mục upload
            $uploadDir = __DIR__ . '/../../../public/assets/images/uploads/';
            $relativePath = 'assets/images/uploads/';

            // Tạo thư mục nếu chưa có
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // 2. Xử lý ảnh: Ưu tiên ảnh upload
            $iconPath = null;

            if (!empty($categoryData['icon_file']['tmp_name'])) {
                $tmpPath = $categoryData['icon_file']['tmp_name'];
                $filename = time() . '_' . basename($categoryData['icon_file']['name']);
                $targetPath = $uploadDir . $filename;

                if (is_uploaded_file($tmpPath)) {
                    if (move_uploaded_file($tmpPath, $targetPath)) {
                        $iconPath = $relativePath . $filename;
                    } else {
                        error_log("❌ move_uploaded_file thất bại: $tmpPath → $targetPath");
                    }
                } else {
                    error_log("❌ Không phải file upload hợp lệ: $tmpPath");
                }
            }

            // 3. Nếu không có ảnh upload thì dùng link
            if (!$iconPath && !empty($categoryData['icon_image'])) {
                $iconPath = $categoryData['icon_image'];
            }

            // 4. Insert vào CSDL
            $stmt = $this->connection->prepare("
                INSERT INTO categories (name, icon_image, is_active)
                VALUES (:name, :icon_image, :is_active)
            ");

            return $stmt->execute([
                'name' => $categoryData['name'],
                'icon_image' => $iconPath,
                'is_active' => $categoryData['is_active']
            ]);
        } catch (PDOException $e) {
            error_log("❌ Lỗi thêm category: " . $e->getMessage());
            return false;
        }
    }



    public function updateProduct(array $data): bool
    {
        try {
            $this->connection->beginTransaction();

            // 1. Update bảng products
            $stmt = $this->connection->prepare("
                UPDATE products SET
                    name = :name,
                    price = :price,
                    description = :description,
                    id_categories = :id_categories,
                    is_available = :is_available,
                    is_banner = :is_banner,
                    updated_at = NOW()
                WHERE id_products = :id_products
            ");

            $stmt->execute([
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'id_categories' => $data['id_categories'],
                'is_available' => $data['is_available'],
                'is_banner' => $data['is_banner'],
                'id_products' => $data['id_products']
            ]);

            // 2. Update bảng product_nutrition
            $stmt = $this->connection->prepare("
                UPDATE product_nutrition SET
                    calo = :calo,
                    protein = :protein,
                    carbohydrate = :carbohydrate,
                    fat = :fat,
                    gram = :gram
                WHERE id_products = :id_products
            ");

            $stmt->execute([
                'calo' => $data['calo'],
                'protein' => $data['protein'],
                'carbohydrate' => $data['carbohydrate'],
                'fat' => $data['fat'],
                'gram' => $data['gram'],
                'id_products' => $data['id_products']
            ]);

            // 3. Nếu có ảnh mới => xoá ảnh cũ → thêm ảnh mới
            if (!empty($_FILES['images']['tmp_name'][0])) {
                // Xoá ảnh cũ
                $this->delete("DELETE FROM product_images WHERE id_products = :id", [
                    'id' => $data['id_products']
                ]);

                // Upload ảnh mới
                $uploadDir = __DIR__ . '/../../../public/assets/images/uploads/';
                $relativePath = 'assets/images/uploads/';

                for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    $tmpPath = $_FILES['images']['tmp_name'][$i];
                    $filename = time() . '_' . basename($_FILES['images']['name'][$i]);
                    $targetPath = $uploadDir . $filename;

                    if (is_uploaded_file($tmpPath) && move_uploaded_file($tmpPath, $targetPath)) {
                        $stmt = $this->connection->prepare("
                            INSERT INTO product_images 
                                (id_products, image_url, is_main, is_banner)
                            VALUES 
                                (:id_products, :image_url, :is_main, :is_banner)
                        ");
                        $stmt->execute([
                            'id_products' => $data['id_products'],
                            'image_url' => $relativePath . $filename,
                            'is_main' => $i === 0 ? 1 : 0,
                            'is_banner' => ($i === 0 && $data['is_banner']) ? 1 : 0
                        ]);
                    }
                }
            }


            $this->connection->commit();
            return true;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("❌ Lỗi cập nhật sản phẩm: " . $e->getMessage());
            return false;
        }
    }



    public function deleteProduct(int $productId): void
    {
        $this->connection->beginTransaction();

        // 1. Xoá cart_items chứa sản phẩm này
        $this->delete("DELETE FROM cart_items WHERE id_products = :productId", [
            'productId' => $productId
        ]);

        // 2. Xoá dinh dưỡng và hình ảnh (nếu có)
        $this->delete("DELETE FROM product_nutrition WHERE id_products = :productId", [
            'productId' => $productId
        ]);
        $this->delete("DELETE FROM product_images WHERE id_products = :productId", [
            'productId' => $productId
        ]);

        // 3. Cuối cùng mới xoá sản phẩm
        $this->delete("DELETE FROM products WHERE id_products = :productId", [
            'productId' => $productId
        ]);

        $this->connection->commit();
    }

    public function updateCategory(array $data): bool
    {
        try {
            $uploadDir = __DIR__ . '/../../../public/assets/images/uploads/';
            $relativePath = 'assets/images/uploads/';
            $iconPath = $data['icon_image'] ?? null;

            if (!empty($data['icon_file']['tmp_name'])) {
                $tmpPath = $data['icon_file']['tmp_name'];
                $filename = time() . '_' . basename($data['icon_file']['name']);
                $targetPath = $uploadDir . $filename;

                if (is_uploaded_file($tmpPath) && move_uploaded_file($tmpPath, $targetPath)) {
                    $iconPath = $relativePath . $filename;
                }
            }

            $stmt = $this->connection->prepare("
                UPDATE categories 
                SET name = :name, icon_image = :icon_image, is_active = :is_active
                WHERE id_categories = :id
            ");

            return $stmt->execute([
                'name' => $data['name'],
                'icon_image' => $iconPath,
                'is_active' => $data['is_active'],
                'id' => $data['id_categories']
            ]);
        } catch (PDOException $e) {
            error_log("❌ Cập nhật category lỗi: " . $e->getMessage());
            return false;
        }
    }



    public function deleteCategory(int $categoryId): bool
    {
        // Kiểm tra có sản phẩm liên kết không
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) FROM products WHERE id_categories = :id
        ");
        $stmt->execute(['id' => $categoryId]);
        $productCount = $stmt->fetchColumn();

        if ($productCount > 0) {
            // Không xoá được vì còn sản phẩm
            return false;
        }

        // Nếu không có thì cho xoá
        return $this->delete("DELETE FROM categories WHERE id_categories = :id", [
            'id' => $categoryId
        ]);
    }


    


}
