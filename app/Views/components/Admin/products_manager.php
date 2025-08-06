<?php 

$categories = $this->productController->getActiveCategories();
$product = null;

require_once __DIR__ . '/../../../Controllers/ProductController.php';
$productController = new ProductController();
$products = $productController->showProductList();

$allProducts = $productController->showProductList();

$itemsPerPage = 10;
$totalProducts = count($allProducts);
$totalPages = ceil($totalProducts / $itemsPerPage);

$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$startIndex = ($currentPage - 1) * $itemsPerPage;
$products = array_slice($allProducts, $startIndex, $itemsPerPage);
?>

<!-- Quản lý Sản phẩm -->
<div id="products" class="content-section">
    <div class="section-header">
        <h2>Quản lý Sản phẩm</h2>
        <button id="addProductBtn" class="btn btn-primary">➕ Thêm Sản phẩm</button>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Tìm kiếm sản phẩm..." />
    </div>

    <table class="table" id="productsTable">
        <thead>
            <tr>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product->productId ?></td>
                <td><?= htmlspecialchars($product->name) ?></td>
                <td><?= $product->categoryName ?? 'Không rõ' ?></td>
                <td><?= number_format((float)$product->price, 0, ',', '.') ?> đ</td>

                <td><?= nl2br(htmlspecialchars($product->description ?? '')) ?></td>
                <td>
                    <?php if ($product->isAvailable): ?>
                    <span class="badge bg-success">Có sẵn</span>
                    <?php else: ?>
                    <span class="badge bg-danger">Hết hàng</span>
                    <?php endif; ?>

                </td>
                <td>
                    <?= $product->createdAt ? date('d/m/Y H:i', strtotime($product->createdAt)) : 'N/A' ?>


                </td>
                <td>
                    <button class="btn btn-warning btn-sm edit-product-btn" data-id="<?= $product->productId ?>"
                        data-name="<?= htmlspecialchars($product->name) ?>" data-category="<?= $product->categoryId ?>"
                        data-price="<?= $product->price ?>"
                        data-description="<?= htmlspecialchars($product->description ?? '') ?>"
                        data-status="<?= $product->isAvailable ?>" data-calo="<?= $product->calo ?>"
                        data-protein="<?= $product->protein ?>" data-carb="<?= $product->carbohydrate ?>"
                        data-fat="<?= $product->fat ?>" data-gram="<?= $product->gram ?>">
                        Sửa
                    </button>

                    <button class="btn btn-sm btn-danger delete-product-btn" data-id="<?= $product->productId ?>">
                        Xoá
                    </button>

                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="8">Không có sản phẩm nào.</td>
            </tr>
            <?php endif; ?>
        </tbody>

    </table>
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <ul class="pagination-list">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
    <style>
    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
    }

    .bg-success {
        background-color: #28a745;
        color: white;
    }

    .bg-danger {
        background-color: #dc3545;
        color: white;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination-list {
        list-style: none;
        display: inline-flex;
        gap: 8px;
        padding: 0;
    }

    .page-item a {
        padding: 6px 12px;
        border: 1px solid #ccc;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
    }

    .page-item.active a {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    </style>




</div>


<!-- Modal sửa sản phẩm -->
<div id="editProductModal" class="modal">
    <div class="modal-content">

       <span class="close" id="closeEditModal">&times;</span>
        <h3 style="text-align: center; margin-bottom: 20px; color: #333;">📝 Chỉnh sửa thông tin sản phẩm</h3>

        <form action="<?= BASE_URL ?>admin-update-product" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productId" id="editProductId" />

            <div class="form-group">
                <label for="editProductName">Tên sản phẩm</label>
                <input type="text" class="form-control" id="editProductName" name="name" required />
            </div>

            <div class="form-group">
                <label for="editProductCategory">Loại sản phẩm</label>
                <select id="editProductCategory" name="id_categories" class="form-control" required>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categories'] ?>"><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Ảnh hiện tại:</label>
                <div id="currentImages" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;"></div>
            </div>

            <div class="form-group">
                <label>Chọn ảnh mới (nếu muốn thay đổi):</label>
                <input type="file" id="editProductImages" name="images[]" multiple>

                <small class="text-muted">Ảnh đầu tiên sẽ là ảnh chính (is_main)</small>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="editIsBanner" name="is_banner" />
                    Sử dụng ảnh trong banner?
                </label>
            </div>
            

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex:1;">
                    <label for="editProductPrice">Giá</label>
                    <input type="number" class="form-control" id="editProductPrice" name="price" required step="1000" />
                </div>
                <div class="form-group" style="flex:1;">
                    <label for="editProductStatus">Trạng thái</label>
                    <select id="editProductStatus" name="is_available" class="form-control">
                        <option value="1">Có sẵn</option>
                        <option value="0">Hết hàng</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="editProductDescription">Mô tả</label>
                <textarea id="editProductDescription" name="description" class="form-control" rows="3"></textarea>
            </div>

            <fieldset style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <legend style="font-weight: bold;">🍽 Dinh dưỡng / khẩu phần</legend>
                <div class="form-row" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1 1 45%;">
                        <label for="editCalo">Calo</label>
                        <input type="number" class="form-control" name="calo" id="editCalo" step="0.01" />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%;">
                        <label for="editProtein">Protein (g)</label>
                        <input type="number" class="form-control" name="protein" id="editProtein" step="0.01" />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%;">
                        <label for="editCarb">Carbohydrate (g)</label>
                        <input type="number" class="form-control" name="carbohydrate" id="editCarb" step="0.01" />
                    </div>
                    <div class="form-group" style="flex: 1 1 45%;">
                        <label for="editFat">Fat (g)</label>
                        <input type="number" class="form-control" name="fat" id="editFat" step="0.01" />
                    </div>
                    <div class="form-group" style="flex: 1 1 100%;">
                        <label for="editGram">Khối lượng (gram)</label>
                        <input type="number" class="form-control" name="gram" id="editGram" step="0.01" />
                    </div>
                </div>
            </fieldset>

            <div style="text-align: right; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Hủy</button>
                <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>




<!-- Modal Sản phẩm -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeProductModal">&times;</span>
        <h3 id="productModalTitle">Thêm Sản phẩm</h3>
        <form id="productForm" action="<?= BASE_URL ?>admin-create-product" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="productId" name="productId" value="<?= $product ? $product->productId : '' ?>" />

            <div class="form-row">
                <div class="form-group">
                    <label>Tên sản phẩm:</label>
                    <input type="text" id="productName" name="name" class="form-control" required
                        value="<?= $product ? htmlspecialchars($product->name) : '' ?>" />

                </div>
                <div class="form-group">
                    <label>Loại sản phẩm:</label>
                    <select id="productType" name="id_categories" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id_categories']) ?>"
                            <?= isset($product) && $product->categoryId == $cat['id_categories'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Giá:</label>
                    <input type="number" id="productPrice" name="price" class="form-control" required step="1000"
                        value="<?= $product ? $product->price : '' ?>" />

                </div>
                <div class="form-group">
                    <label>Trạng thái:</label>
                    <select id="productStatus" name="is_available" class="form-control">
                        <option value="1" <?= $product && $product->isAvailable ? 'selected' : '' ?>>Có sẵn</option>
                        <option value="0" <?= $product && !$product->isAvailable ? 'selected' : '' ?>>Hết hàng</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Mô tả:</label>
                <textarea id="productDescription" name="description" class="form-control" rows="3">
                    <?= $product ? htmlspecialchars($product->description) : '' ?>
                </textarea>
            </div>

            <div class="form-group">
                <label>Ảnh sản phẩm (có thể chọn nhiều):</label>
                <input type="file" id="productImages" name="images[]" multiple required>

                <small class="text-muted">Ảnh đầu tiên sẽ là ảnh chính (is_main)</small>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="isBanner" name="is_banner"
                        <?= $product && $product->imageUrl && strpos($product->imageUrl, 'banner') !== false ? 'checked' : '' ?> />

                    Sử dụng ảnh trong banner?
                </label>
            </div>

            <div class="form-group">
                <h4>Thông tin dinh dưỡng (trên mỗi khẩu phần)</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Calo:</label>
                        <input type="number" name="calo" id="calo" class="form-control"
                            value="<?= $product ? $product->calo : '' ?>" step="0.01" />

                    </div>
                    <div class="form-group">
                        <label>Protein (g):</label>
                        <input type="number" name="protein" id="protein" class="form-control"
                            value="<?= $product ? $product->protein : '' ?>" step="0.01" />

                    </div>
                    <div class="form-group">
                        <label>Carbohydrate (g):</label>
                        <input type="number" name="carbohydrate" id="carbohydrate" class="form-control"
                            value="<?= $product ? $product->carbohydrate : '' ?>" step="0.01" />
                    </div>
                    <div class="form-group">
                        <label>Fat (g):</label>
                        <input type="number" name="fat" id="fat" class="form-control"
                            value="<?= $product ? $product->fat : '' ?>" step="0.01" />
                    </div>
                    <div class="form-group">
                        <label>Khối lượng (gram):</label>
                        <input type="number" name="gram" id="gram" class="form-control"
                            value="<?= $product ? $product->gram : '' ?>" step="0.01" />
                    </div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelProductBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE_URL = "<?= BASE_URL ?>";

document.addEventListener("DOMContentLoaded", function () {
    // ---------- Modal ADD ----------
    const openProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const closeProductBtn = document.getElementById('closeProductModal');
    const cancelProductBtn = document.getElementById('cancelProductBtn');

    if (openProductBtn && productModal) {
        openProductBtn.onclick = () => productModal.style.display = 'block';
        closeProductBtn.onclick = () => productModal.style.display = 'none';
        cancelProductBtn.onclick = () => productModal.style.display = 'none';

        window.addEventListener('click', (e) => {
            if (e.target === productModal) {
                productModal.style.display = 'none';
            }
        });
    }

    // ---------- Form Validate ADD ----------
    const form = document.getElementById("productForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            let errors = [];

            const name = document.getElementById("productName").value.trim();
            const price = document.getElementById("productPrice").value.trim();
            const category = document.getElementById("productType").value;
            const description = document.getElementById("productDescription").value.trim();

            if (name === "") errors.push("Vui lòng nhập tên sản phẩm.");
            if (price === "" || isNaN(price) || Number(price) <= 0) errors.push("Giá sản phẩm không hợp lệ.");
            if (!category) errors.push("Vui lòng chọn loại sản phẩm.");
            if (description === "") errors.push("Vui lòng nhập mô tả.");

            const files = document.getElementById("productImages").files;
            if (files.length === 0) errors.push("Vui lòng chọn ít nhất một ảnh sản phẩm.");

            if (errors.length > 0) {
                e.preventDefault();
                alert("Đã xảy ra lỗi:\n\n" + errors.join("\n"));
                return false;
            }

            alert("Dữ liệu hợp lệ, đang gửi lên máy chủ...");
        });
    }

    // ---------- Modal EDIT ----------
    const editProductModal = document.getElementById('editProductModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    if (editProductModal) {
        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Đổ dữ liệu vào form
                document.getElementById('editProductId').value = btn.dataset.id;
                document.getElementById('editProductName').value = btn.dataset.name;
                document.getElementById('editProductCategory').value = btn.dataset.category;
                document.getElementById('editProductPrice').value = btn.dataset.price;
                document.getElementById('editProductStatus').value = btn.dataset.status;
                document.getElementById('editProductDescription').value = btn.dataset.description;

                document.getElementById('editCalo').value = btn.dataset.calo;
                document.getElementById('editProtein').value = btn.dataset.protein;
                document.getElementById('editCarb').value = btn.dataset.carb;
                document.getElementById('editFat').value = btn.dataset.fat;
                document.getElementById('editGram').value = btn.dataset.gram;

                editProductModal.style.display = 'block';
            });
        });

        closeEditModal.onclick = () => editProductModal.style.display = 'none';
        cancelEditBtn.onclick = () => editProductModal.style.display = 'none';
        window.onclick = (e) => {
            if (e.target === editProductModal) editProductModal.style.display = 'none';
        };
    }

    // ---------- Xoá sản phẩm ----------
    document.querySelectorAll(".delete-product-btn").forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.dataset.id;
            const confirmDelete = confirm("❗ Bạn có chắc chắn muốn xoá sản phẩm này không?");
            if (confirmDelete) {

                window.location.href = BASE_URL + `admin-delete-product?id=${productId}`;
            }
        });
    });
});
</script>

