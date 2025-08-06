<?php
require_once __DIR__ . '/../../../Controllers/ProductController.php';

$productController = new ProductController();
$products = $productController->showProductList();
$categories = $productController->getActiveCategories();
?>


<div id="categories" class="content-section mt-5 active">
    <div class="section-header">
        <h2>Quản lý Loại Sản phẩm</h2>
        <button id="addcategorieBtn" class="btn btn-primary">➕ Thêm Loại Sản phẩm</button>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Tìm kiếm sản phẩm..." />
    </div>

    <table class="table" id="categoriesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên loại</th>
                <th>Icon</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= $category['id_categories'] ?></td>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td>
                    <?php if (!empty($category['icon_image'])): ?>
                    <?php
                                    $icon = $category['icon_image'];
                                    $isUrl = filter_var($icon, FILTER_VALIDATE_URL);
                                    $iconPath = $isUrl ? $icon : '<?= BASE_URL ?>' . ltrim($icon, '/');
                                ?>
                    <img src="<?= $iconPath ?>" width="30" alt="<?= htmlspecialchars($category['name']) ?>">

                    <?php else: ?>
                    Không có
                    <?php endif; ?>
                </td>


                <td>
                    <?= $category['is_active'] 
                        ? '<span class="badge badge-active">Hoạt động</span>' 
                        : '<span class="badge badge-inactive">Tạm ẩn</span>' ?>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning edit-category-btn"
                        data-id="<?= $category['id_categories'] ?>"
                        data-name="<?= htmlspecialchars($category['name']) ?>"
                        data-icon="<?= $category['icon_image'] ?>"
                        data-status="<?= $category['is_active'] ?>">
                        Sửa
                    </button>
                    <button 
                        class="btn btn-sm btn-danger delete-category-btn"
                        data-id="<?= $category['id_categories'] ?>"
                        data-name="<?= htmlspecialchars($category['name']) ?>">
                        Xoá
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4">Không có loại sản phẩm nào.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: white;
}

.badge-active {
    background-color: #28a745; /* xanh lá */
}

.badge-inactive {
    background-color: #dc3545; /* đỏ */
}
</style>



<!-- Modal Sửa Loại sản phẩm -->
<div id="categoryModals" class="modal">
    <div class="modal-content">

        <span class="close" id="closeCategoryModal">&times;</span>

        <h3 id="categoryModalTitle" style="text-align: center; margin-bottom: 20px;">🛠 Chỉnh sửa Loại sản phẩm</h3>

        <form id="categoryForms" action="<?= BASE_URL ?>admin-update-category" method="POST"
            enctype="multipart/form-data">

            <input type="hidden" name="id_categories" id="categoryId">

            <div class="form-group">
                <label for="categoryName">Tên loại sản phẩm:</label>
                <input type="text" class="form-control" id="categoryName" name="name" required>
            </div>

            <div class="form-group">
                <label>Icon loại (upload hoặc dán link):</label>
                <input type="file" class="form-control mb-2" id="categoryIconFile" name="icon_file" accept="image/*">
                <input type="url" class="form-control" id="categoryIconUrl" name="icon_image"
                    placeholder="Hoặc nhập link ảnh https://...">
                <small class="text-muted">Nếu upload ảnh mới → ưu tiên dùng ảnh đó.</small>
            </div>

            <div class="form-group">
                <label for="categoryStatus">Trạng thái:</label>
                <select class="form-control" id="categoryStatus" name="is_active">
                    <option value="1">Hoạt động</option>
                    <option value="0">Tạm ẩn</option>
                </select>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-secondary" id="cancelCategoryBtn">Hủy</button>
                <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Loại sản phẩm -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeCategoryModal">&times;</span>
        <h3 id="categoryModalTitle">Thêm Loại sản phẩm</h3>

        <form id="categoryForm" action="<?= BASE_URL ?>admin-create-category" method="POST"
            enctype="multipart/form-data">

            <input type="hidden" id="categoryId" name="id_categories" value="<?= $category['id_categories'] ?? '' ?>">

            <div class="form-group">
                <label>Tên loại sản phẩm:</label>
                <input type="text" name="name" id="categoryName" class="form-control"
                    value="<?= isset($category) ? htmlspecialchars($category['name']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Icon loại (upload hoặc link):</label>

                <input type="file" name="icon_file" id="categoryIconFile" class="form-control mb-2" accept="image/*">

                <input type="url" name="icon_image" id="categoryIconUrl" class="form-control"
                    placeholder="Hoặc dán link https://..."
                    value="<?= isset($category) ? htmlspecialchars($category['icon_image']) : '' ?>">

                <small class="text-muted">Bạn có thể chọn ảnh từ máy hoặc dán link ảnh (ưu tiên ảnh đã upload).</small>
            </div>

            <div class="form-group">
                <label>Trạng thái:</label>
                <select name="is_active" id="categoryStatus" class="form-control">
                    <option value="1" <?= isset($category) && $category['is_active'] ? 'selected' : '' ?>>Hoạt động
                    </option>
                    <option value="0" <?= isset($category) && !$category['is_active'] ? 'selected' : '' ?>>Tạm ẩn
                    </option>
                </select>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelCategoryBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", function() {
    const openBtn = document.getElementById("addcategorieBtn");
    const modal = document.getElementById("categoryModal");
    const closeBtn = document.getElementById("closeCategoryModal");
    const cancelBtn = document.getElementById("cancelCategoryBtn");
    const form = document.getElementById("categoryForm");

    openBtn.addEventListener("click", function() {
        modal.style.display = "block";
        form.reset();

        document.getElementById("categoryModalTitle").innerText = "Thêm Loại sản phẩm";
        document.getElementById("categoryId").value = '';
    });

    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    cancelBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });








    document.querySelectorAll(".edit-category-btn").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const icon = this.dataset.icon;
            const status = this.dataset.status;

            // Mở modal
            const modal = document.getElementById("categoryModals");
            modal.style.display = "block";

            // Cập nhật tiêu đề
            document.getElementById("categoryModalTitle").innerText = "Chỉnh sửa Loại sản phẩm";

            // Gán dữ liệu vào form
            document.getElementById("categoryId").value = id;
            document.getElementById("categoryName").value = name;
            document.getElementById("categoryIconUrl").value = icon;
            document.getElementById("categoryStatus").value = status;
        });
    });



    
});

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("categoryModals");
  const closeBtn = document.getElementById("closeCategoryModal");
  const cancelBtn = document.getElementById("cancelCategoryBtn");
  const form = document.getElementById("categoryForms");

  // Mở modal khi click nút Sửa
  document.querySelectorAll(".edit-category-btn").forEach(button => {
    button.addEventListener("click", function () {
      // Gán dữ liệu
      document.getElementById("categoryModalTitle").innerText = "Chỉnh sửa Loại sản phẩm";
      document.getElementById("categoryId").value = this.dataset.id;
      document.getElementById("categoryName").value = this.dataset.name;
      document.getElementById("categoryIconUrl").value = this.dataset.icon;
      document.getElementById("categoryStatus").value = this.dataset.status;

      // Mở modal
      modal.style.display = "block";
    });
  });

  // Nút đóng
  closeBtn.onclick = () => modal.style.display = "none";
  cancelBtn.onclick = () => modal.style.display = "none";
  window.onclick = (e) => {
    if (e.target === modal) modal.style.display = "none";
  };
});
</script>




<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-category-btn').forEach(button => {
    button.addEventListener('click', function () {
      const categoryId = this.dataset.id;
      const categoryName = this.dataset.name;

      const confirmDelete = confirm(`❗ Bạn có chắc muốn xoá loại sản phẩm: "${categoryName}"?`);
      if (confirmDelete) {
        window.location.href = BASE_URL + `admin-delete-category?id=${categoryId}`;
      }
    });
  });
});
</script>
