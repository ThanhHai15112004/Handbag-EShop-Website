<?php

require_once __DIR__ . '/../ProductController.php';
require_once __DIR__ . '/../../Models/Products/ProductApi.php';
require_once __DIR__ . '/../../Models/Auth/AccountApi.php';

require_once __DIR__ . '/../../Models/Order/OrderApi.php';

require_once __DIR__ . '/../../Models/Delivery/DeliveryApi.php';
require_once __DIR__ . '/../../configs/config.php';


class AdminController
{
    private $productController;

    public function __construct()
    {
        $this->productController = new ProductController();
        $this->accountApi = new AccountApi();
        $this->orderApi = new OrderApi();
        $this->deliveryApi = new DeliveryApi();
    }

    public function index(): void
    {
        $categoryId = $_GET['category_id'] ?? null;

        if ($categoryId) {
            $products = $this->productController->getProductsByCategory((int)$categoryId);
        } else {
            $products = $this->productController->showProductList();
        }

        $categories = $this->productController->getActiveCategories();

        $accountApi = new AccountApi();
        $accounts = $accountApi->getAll();
        $membershipLevels = $accountApi->getMembershipLevels();

        $orderApi = new OrderApi();
        $orders = $orderApi->getAllOrders();
        


        $deliveryApi = new DeliveryApi();
        $deliveries = $deliveryApi->getAll();
        $ordersForDelivery = $deliveryApi->getOrders();
        $shippers = $deliveryApi->getShippers();

        require_once __DIR__ . '/../../Views/pages/admin/index.php';
    }


    public function createAccount(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ!');
        }

        try {
            $requiredFields = ['username', 'password', 'email', 'full_name', 'role'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error_message'] = "❌ Thiếu thông tin bắt buộc: $field.";
                    header("Location: " . BASE_URL . "admin-accounts");
                    exit;
                }
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "❌ Email không hợp lệ.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            $accountApi = new AccountApi();
            $success = $accountApi->createAccountWithMembership($_POST);

            if ($success) {
                $_SESSION['success_message'] = '✅ Tạo tài khoản thành công!';
            } else {
                $_SESSION['error_message'] = '❌ Tạo tài khoản thất bại. Vui lòng thử lại.';
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (Create Account): " . $e->getMessage());
            $_SESSION['error_message'] = '❌ Lỗi kết nối cơ sở dữ liệu.';

        } catch (Exception $e) {
            error_log("🛑 ERROR (Create Account): " . $e->getMessage());
            $_SESSION['error_message'] = '❌ Có lỗi xảy ra. Vui lòng thử lại.';
        }

        header("Location: " . BASE_URL . "admin-accounts");
        exit;
    }



    public function createCategory(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Phương thức không hợp lệ.");
        }

        try {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $_SESSION['error_message'] = "❌ Tên loại sản phẩm không được để trống.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $iconFile = $_FILES['icon_file'] ?? null;
            if ($iconFile && $iconFile['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
                if (!in_array($iconFile['type'], $allowedTypes)) {
                    $_SESSION['error_message'] = "❌ Chỉ chấp nhận ảnh .jpg, .png, .svg hoặc .webp.";
                    header("Location: " . BASE_URL . "admin");
                    exit;
                }
            }

            $categoryData = [
                'name' => $name,
                'icon_file' => $iconFile
            ];

            $productApi = new ProductApi();
            $success = $productApi->createCategory($categoryData);

            if ($success) {
                $_SESSION['success_message'] = "✅ Thêm loại sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Không thể thêm loại sản phẩm. Hãy thử lại.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (createCategory): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi kết nối cơ sở dữ liệu.";

        } catch (Exception $e) {
            error_log("🛑 ERROR (createCategory): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Có lỗi xảy ra. Vui lòng thử lại.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }




    public function createProduct(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ.');
        }

        try {
            $name = trim($_POST['name'] ?? '');
            $price = $_POST['price'] ?? 0;
            $description = trim($_POST['description'] ?? '');
            $categoryId = $_POST['id_categories'] ?? 0;

            if ($name === '' || strlen($name) < 2) {
                $_SESSION['error_message'] = "❌ Tên sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if (!is_numeric($price) || $price <= 0) {
                $_SESSION['error_message'] = "❌ Giá sản phẩm phải là số dương.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if ($description === '') {
                $_SESSION['error_message'] = "❌ Mô tả sản phẩm không được để trống.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if (!is_numeric($categoryId) || $categoryId <= 0) {
                $_SESSION['error_message'] = "❌ Vui lòng chọn loại sản phẩm hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $images = $_FILES['images'] ?? null;
            if (!$images || !isset($images['name']) || count(array_filter($images['name'])) === 0) {
                $_SESSION['error_message'] = "❌ Vui lòng chọn ít nhất một ảnh sản phẩm.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            foreach ($images['type'] as $type) {
                if (!in_array($type, $allowedTypes)) {
                    $_SESSION['error_message'] = "❌ Chỉ chấp nhận ảnh JPEG, PNG hoặc WebP.";
                    header("Location: " . BASE_URL . "admin");
                    exit;
                }
            }

            $productData = [
                'name'          => $name,
                'price'         => $price,
                'description'   => $description,
                'id_categories' => (int)$categoryId,
                'is_available'  => isset($_POST['is_available']) ? 1 : 0,
                'is_banner'     => isset($_POST['is_banner']) ? 1 : 0,
                'calo'          => $_POST['calo'] ?? null,
                'protein'       => $_POST['protein'] ?? null,
                'carbohydrate'  => $_POST['carbohydrate'] ?? null,
                'fat'           => $_POST['fat'] ?? null,
                'gram'          => $_POST['gram'] ?? null
            ];

            $productApi = new ProductApi();
            $success = $productApi->createProduct($productData, $images);

            if ($success) {
                $_SESSION['success_message'] = "✅ Thêm sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Thêm sản phẩm thất bại.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (createProduct): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu.";

        } catch (Exception $e) {
            error_log("🛑 ERROR (createProduct): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }


    public function updateProduct(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ.');
        }

        try {
            $id = $_POST['productId'] ?? null;
            if (!$id || !is_numeric($id) || $id <= 0) {
                $_SESSION['error_message'] = "❌ ID sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $name = trim($_POST['name'] ?? '');
            $price = $_POST['price'] ?? 0;
            $images = $_FILES['images'] ?? null;

            $description = trim($_POST['description'] ?? '');
            $categoryId = $_POST['id_categories'] ?? 0;

            if ($name === '' || strlen($name) < 2) {
                $_SESSION['error_message'] = "❌ Tên sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if (!is_numeric($price) || $price <= 0) {
                $_SESSION['error_message'] = "❌ Giá sản phẩm phải là số dương.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if ($description === '') {
                $_SESSION['error_message'] = "❌ Mô tả không được để trống.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if (!is_numeric($categoryId) || $categoryId <= 0) {
                $_SESSION['error_message'] = "❌ Vui lòng chọn loại sản phẩm hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $productData = [
                'id_products'    => (int)$id,
                'name'           => $name,
                'price'          => $price,
                'description'    => $description,
                'id_categories'  => (int)$categoryId,
                'is_available'   => isset($_POST['is_available']) ? 1 : 0,
                'is_banner'      => isset($_POST['is_banner']) ? 1 : 0,
                'calo'           => $_POST['calo'] ?? null,
                'protein'        => $_POST['protein'] ?? null,
                'carbohydrate'   => $_POST['carbohydrate'] ?? null,
                'fat'            => $_POST['fat'] ?? null,
                'gram'           => $_POST['gram'] ?? null
            ];

            $productApi = new ProductApi();
            $success = $productApi->updateProduct($productData);

            if ($success) {
                $_SESSION['success_message'] = "✅ Cập nhật sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Cập nhật sản phẩm thất bại. Hãy thử lại.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (updateProduct): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi cập nhật sản phẩm.";
        } catch (Exception $e) {
            error_log("🛑 ERROR (updateProduct): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }



    public function deleteProduct(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] <= 0) {
                $_SESSION['error_message'] = "❌ Thiếu hoặc ID sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $productId = (int)$_GET['id'];

            $productApi = new ProductApi();
            $success = $productApi->deleteProduct($productId);

            if ($success) {
                $_SESSION['success_message'] = "✅ Xóa sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Xóa sản phẩm thất bại. Có thể sản phẩm đang được liên kết với khuyến mãi hoặc đơn hàng.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (deleteProduct): " . $e->getMessage());

            if (str_contains($e->getMessage(), 'foreign key')) {
                $_SESSION['error_message'] = "❌ Không thể xoá sản phẩm do đang được liên kết với dữ liệu khác.";
            } else {
                $_SESSION['error_message'] = "❌ Lỗi hệ thống khi xoá sản phẩm.";
            }

        } catch (Exception $e) {
            error_log("🛑 ERROR (deleteProduct): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Có lỗi xảy ra. Vui lòng thử lại.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }



    public function updateCategory(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Phương thức không hợp lệ.");
        }

        try {
            $id = $_POST['id_categories'] ?? null;
            $name = trim($_POST['name'] ?? '');

            if (!$id || !is_numeric($id) || $id <= 0) {
                $_SESSION['error_message'] = "❌ Thiếu hoặc ID loại sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            if ($name === '') {
                $_SESSION['error_message'] = "❌ Tên loại sản phẩm không được để trống.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $iconFile = $_FILES['icon_file'] ?? null;
            if ($iconFile && $iconFile['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
                if (!in_array($iconFile['type'], $allowedTypes)) {
                    $_SESSION['error_message'] = "❌ Chỉ chấp nhận ảnh định dạng: JPG, PNG, SVG, WebP.";
                    header("Location: " . BASE_URL . "admin");
                    exit;
                }
            }

            $categoryData = [
                'id_categories' => (int)$id,
                'name' => $name,
                'icon_file' => $iconFile
            ];

            $productApi = new ProductApi();
            $success = $productApi->updateCategory($categoryData);

            if ($success) {
                $_SESSION['success_message'] = "✅ Cập nhật loại sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Cập nhật loại sản phẩm thất bại.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (updateCategory): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi cập nhật loại sản phẩm.";

        } catch (Exception $e) {
            error_log("🛑 ERROR (updateCategory): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi cập nhật.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }





    public function deleteCategory(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // 1. Kiểm tra tham số
            if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] <= 0) {
                $_SESSION['error_message'] = "❌ ID loại sản phẩm không hợp lệ.";
                header("Location: " . BASE_URL . "admin");
                exit;
            }

            $categoryId = (int)$_GET['id'];
            $productApi = new ProductApi();
            $success = $productApi->deleteCategory($categoryId);

            if ($success) {
                $_SESSION['success_message'] = "✅ Xóa loại sản phẩm thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Không thể xóa loại sản phẩm. Có thể đang được sử dụng bởi sản phẩm khác.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (deleteCategory): " . $e->getMessage());

            if (str_contains($e->getMessage(), 'foreign key')) {
                $_SESSION['error_message'] = "❌ Không thể xóa loại sản phẩm do có sản phẩm đang sử dụng.";
            } else {
                $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi xóa loại sản phẩm.";
            }

        } catch (Exception $e) {
            error_log("🛑 ERROR (deleteCategory): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi xóa loại sản phẩm.";
        }

        header("Location: " . BASE_URL . "admin");
        exit;
    }




    public function updateAccount(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Phương thức không hợp lệ.");
        }

        $accountId = isset($_POST['accountId']) ? (int)$_POST['accountId'] : 0;

        if ($accountId <= 0) {
            $_SESSION['error_message'] = "❌ ID tài khoản không hợp lệ.";
            header("Location: " . BASE_URL . "admin-accounts");
            exit;
        }

        try {
            // Validate cơ bản
            $email = trim($_POST['email'] ?? '');
            $fullName = trim($_POST['fullName'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $role = $_POST['role'] ?? 'user';
            $status = $_POST['accountStatus'] ?? '1';
            $points = isset($_POST['points']) ? (int)$_POST['points'] : 0;
            $level = $_POST['membershipLevel'] ?? null;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "❌ Email không hợp lệ.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            if ($fullName === '' || strlen($fullName) < 2) {
                $_SESSION['error_message'] = "❌ Họ tên không được để trống.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            if (!in_array($role, ['user', 'staff', 'shipper', 'admin'])) {
                $_SESSION['error_message'] = "❌ Quyền không hợp lệ.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            if (!in_array($status, ['0', '1'])) {
                $_SESSION['error_message'] = "❌ Trạng thái tài khoản không hợp lệ.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            if (!is_numeric($points) || $points < 0) {
                $_SESSION['error_message'] = "❌ Điểm phải là số ≥ 0.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            if ($level !== null && !is_numeric($level)) {
                $_SESSION['error_message'] = "❌ Cấp độ thành viên không hợp lệ.";
                header("Location: " . BASE_URL . "admin-accounts");
                exit;
            }

            // Chuẩn bị dữ liệu
            $accountData = [
                'id_accounts'   => $accountId,
                'username'      => $phone,
                'email'         => $email,
                'full_name'     => $fullName,
                'role'          => $role,
                'is_active'     => $status,
                'avatar_url'    => $_POST['avatarUrl'] ?? '',
                'points'        => $points,
                'level'         => $level
            ];

            // Nếu có mật khẩu mới
            if (!empty($_POST['password'])) {
                $accountData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Gọi API cập nhật
            $accountApi = new AccountApi();
            $accountApi->updateAccountWithMembership($accountData);

            $_SESSION['success_message'] = "✅ Cập nhật tài khoản thành công.";

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (updateAccount): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi cập nhật tài khoản.";
        } catch (Exception $e) {
            error_log("🛑 ERROR (updateAccount): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi cập nhật tài khoản.";
        }

        header("Location: " . BASE_URL . "admin-accounts");
        exit;
    }




    public function deleteAccount(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $redirectUrl = BASE_URL . "admin-accounts";

        try {
            // 1. Kiểm tra ID
            if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] <= 0) {
                $_SESSION['error_message'] = "❌ Thiếu hoặc ID tài khoản không hợp lệ.";
                header("Location: $redirectUrl");
                exit;
            }

            $accountId = (int)$_GET['id'];

            // 2. Không cho xoá chính mình
            if (isset($_SESSION['user']) && (int)$_SESSION['user']['id_accounts'] === $accountId) {
                $_SESSION['error_message'] = "❌ Không thể tự xoá tài khoản của chính bạn khi đang đăng nhập.";
                header("Location: $redirectUrl");
                exit;
            }

            // 3. Thực thi xoá
            $accountApi = new AccountApi();
            $success = $accountApi->deleteAccountById($accountId);

            if ($success) {
                $_SESSION['success_message'] = "✅ Xóa tài khoản thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Không thể xoá tài khoản. Có thể tài khoản đang được liên kết với đơn hàng, hoá đơn hoặc điểm thưởng.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (deleteAccount): " . $e->getMessage());

            if (str_contains($e->getMessage(), 'foreign key')) {
                $_SESSION['error_message'] = "❌ Không thể xoá tài khoản do có dữ liệu liên kết (hoá đơn, đơn hàng, điểm thưởng...).";
            } else {
                $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi xoá tài khoản.";
            }

        } catch (Exception $e) {
            error_log("🛑 ERROR (deleteAccount): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi xoá tài khoản.";
        }

        header("Location: $redirectUrl");
        exit;
    }





    public function updateDelivery(): void
    {
        require_once __DIR__ . '/../../Models/Delivery/DeliveryApi.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Phương thức không hợp lệ.");
        }

        try {
            $data = $_POST;

            // 1. Kiểm tra dữ liệu đầu vào
            $requiredFields = ['id_delivery', 'id_orders', 'id_accounts', 'shipping_address', 'delivery_status'];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $_SESSION['error_message'] = "❌ Thiếu thông tin bắt buộc: $field.";
                    header("Location: " . BASE_URL . "admin-deliveries");
                    exit;
                }
            }

            // 2. Validate cụ thể
            if (!in_array($data['delivery_status'], ['pending', 'shipping', 'delivered', 'failed'])) {
                $_SESSION['error_message'] = "❌ Trạng thái giao hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            if (!is_numeric($data['id_delivery']) || $data['id_delivery'] <= 0) {
                $_SESSION['error_message'] = "❌ ID giao hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            if (!is_numeric($data['id_orders']) || $data['id_orders'] <= 0) {
                $_SESSION['error_message'] = "❌ ID đơn hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            // 3. Gọi API cập nhật
            $api = new DeliveryApi();
            $success = $api->updateDelivery($data);

            if ($success) {
                $_SESSION['success_message'] = "✅ Cập nhật giao hàng thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Cập nhật giao hàng thất bại. Kiểm tra dữ liệu đầu vào hoặc trạng thái.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (updateDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi cập nhật giao hàng.";
        } catch (Exception $e) {
            error_log("🛑 ERROR (updateDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định.";
        }

        header('Location: ' . BASE_URL . 'admin-deliveries');
        exit();
    }


    public function createDelivery(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Phương thức không hợp lệ.");
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // 1. Kiểm tra các trường bắt buộc
            $requiredFields = ['id_orders', 'id_accounts', 'shipping_address', 'delivery_status'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error_message'] = "❌ Thiếu thông tin bắt buộc: $field.";
                    header("Location: " . BASE_URL . "admin-deliveries");
                    exit;
                }
            }

            // 2. Validate cụ thể
            $deliveryStatus = $_POST['delivery_status'];
            if (!in_array($deliveryStatus, ['pending', 'shipping', 'delivered', 'failed'])) {
                $_SESSION['error_message'] = "❌ Trạng thái giao hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            if (!is_numeric($_POST['id_orders']) || $_POST['id_orders'] <= 0) {
                $_SESSION['error_message'] = "❌ ID đơn hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            if (!is_numeric($_POST['id_accounts']) || $_POST['id_accounts'] <= 0) {
                $_SESSION['error_message'] = "❌ ID người giao hàng không hợp lệ.";
                header("Location: " . BASE_URL . "admin-deliveries");
                exit;
            }

            // 3. Chuẩn bị dữ liệu
            $deliveryData = [
                'id_orders'        => (int)$_POST['id_orders'],
                'id_accounts'      => (int)$_POST['id_accounts'],
                'shipping_address' => trim($_POST['shipping_address']),
                'delivery_status'  => $deliveryStatus,
                'shipped_at'       => $_POST['shipped_at'] ?? null,
                'delivered_at'     => $_POST['delivered_at'] ?? null
            ];

            // 4. Gọi API
            $deliveryApi = new DeliveryApi();
            $success = $deliveryApi->createDelivery($deliveryData);

            if ($success) {
                $_SESSION['success_message'] = "✅ Tạo giao hàng thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Tạo giao hàng thất bại. Có thể đơn hàng đã có thông tin giao.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (createDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi tạo giao hàng.";

        } catch (Exception $e) {
            error_log("🛑 ERROR (createDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi tạo giao hàng.";
        }

        header("Location: " . BASE_URL . "admin-deliveries");
        exit;
    }




    public function deleteDelivery(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Kiểm tra ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
            $_SESSION['error_message'] = "❌ Thiếu hoặc ID giao hàng không hợp lệ.";
            header("Location: " . BASE_URL . "admin-deliveries");
            exit;
        }

        $deliveryId = (int)$_GET['id'];

        try {
            $api = new DeliveryApi();
            $success = $api->deleteDeliveryById($deliveryId);

            if ($success) {
                $_SESSION['success_message'] = "✅ Xóa giao hàng thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Không thể xoá giao hàng. Có thể đã bị ràng buộc dữ liệu.";
            }

        } catch (PDOException $e) {
            error_log("🛑 DB ERROR (deleteDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi xoá giao hàng.";

        } catch (Exception $e) {
            error_log("🛑 ERROR (deleteDelivery): " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Đã xảy ra lỗi không xác định khi xoá giao hàng.";
        }

        header("Location: " . BASE_URL . "admin-deliveries");
        exit;
    }




    public function createOrder(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $orderApi = new OrderApi();

                // Validate dữ liệu đầu vào
                $accountId = isset($_POST['accountId']) ? (int)$_POST['accountId'] : 0;
                $status = $_POST['orderStatus'] ?? '';
                $totalPrice = isset($_POST['totalPrice']) ? (float)$_POST['totalPrice'] : 0;
                $totalQty = isset($_POST['totalQuantity']) ? (int)$_POST['totalQuantity'] : 0;
                $paymentMethod = $_POST['paymentMethod'] ?? '';
                $amountPaid = isset($_POST['amountPaid']) ? (float)$_POST['amountPaid'] : 0;
                $isPaid = isset($_POST['isPaid']) ? (int)$_POST['isPaid'] : 0;
                $earnedPoints = isset($_POST['earnedPoints']) ? (int)$_POST['earnedPoints'] : 0;

                if (
                    $accountId <= 0 || $totalPrice < 0 || $totalQty <= 0 || $amountPaid < 0 ||
                    !in_array($status, ['pending', 'confirmed', 'cancelled', 'delivered'], true) ||
                    !in_array($paymentMethod, ['cash', 'momo', 'zalopay'], true)
                ) {
                    $_SESSION['error_message'] = "❌ Dữ liệu đầu vào không hợp lệ.";
                    header("Location: " . BASE_URL . "admin-orders");
                    exit;
                }

                // Gộp dữ liệu đã hợp lệ
                $orderData = [
                    'id_accounts'     => $accountId,
                    'status'          => $status,
                    'total_price'     => $totalPrice,
                    'total_quantity'  => $totalQty,
                ];

                $invoiceData = [
                    'payment_method'  => $paymentMethod,
                    'amount_paid'     => $amountPaid,
                    'is_paid'         => $isPaid,
                    'earned_points'   => $earnedPoints,
                ];

                $success = $orderApi->createOrderWithInvoice($orderData, $invoiceData);

                if ($success) {
                    $_SESSION['success_message'] = "✅ Tạo đơn hàng thành công.";
                } else {
                    $_SESSION['error_message'] = "❌ Tạo đơn hàng thất bại. Có thể do lỗi cơ sở dữ liệu.";
                }

            } catch (PDOException $e) {
                error_log("🛑 PDO ERROR (createOrder): " . $e->getMessage());
                $_SESSION['error_message'] = "❌ Lỗi cơ sở dữ liệu khi tạo đơn hàng.";

            } catch (Exception $e) {
                error_log("🛑 ERROR (createOrder): " . $e->getMessage());
                $_SESSION['error_message'] = "❌ Có lỗi không xác định khi tạo đơn hàng.";
            }

            header("Location: " . BASE_URL . "admin-orders");
            exit;
        }
    }


    public function updateOrder(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $orderId = isset($_POST['orderId']) ? (int)$_POST['orderId'] : 0;

                if ($orderId <= 0) {
                    $_SESSION['error_message'] = "❌ Thiếu hoặc sai ID đơn hàng.";
                    header("Location: " . BASE_URL . "admin-orders");
                    exit;
                }

                // Lấy dữ liệu đầu vào và kiểm tra hợp lệ
                $status = $_POST['orderStatus'] ?? '';
                $totalPrice = isset($_POST['totalPrice']) ? (float)$_POST['totalPrice'] : 0;
                $totalQuantity = isset($_POST['totalQuantity']) ? (int)$_POST['totalQuantity'] : 0;

                if (!in_array($status, ['pending', 'confirmed', 'delivered', 'cancelled'], true)) {
                    $_SESSION['error_message'] = "❌ Trạng thái đơn hàng không hợp lệ.";
                    header("Location: " . BASE_URL . "admin-orders");
                    exit;
                }

                if ($totalPrice < 0 || $totalQuantity <= 0) {
                    $_SESSION['error_message'] = "❌ Tổng tiền hoặc số lượng không hợp lệ.";
                    header("Location: " . BASE_URL . "admin-orders");
                    exit;
                }

                $orderData = [
                    'id_orders'      => $orderId,
                    'status'         => $status,
                    'total_price'    => $totalPrice,
                    'total_quantity' => $totalQuantity
                ];

                $orderApi = new OrderApi();
                $success = $orderApi->updateOrder($orderData);

                if ($success) {
                    $_SESSION['success_message'] = "✅ Cập nhật đơn hàng thành công.";
                } else {
                    $_SESSION['error_message'] = "❌ Cập nhật đơn hàng thất bại.";
                }
            } catch (PDOException $e) {
                error_log("🛑 PDO ERROR (updateOrder): " . $e->getMessage());
                $_SESSION['error_message'] = "❌ Lỗi hệ thống khi cập nhật đơn hàng.";
            } catch (Exception $e) {
                error_log("🛑 ERROR (updateOrder): " . $e->getMessage());
                $_SESSION['error_message'] = "❌ Có lỗi không xác định khi cập nhật đơn hàng.";
            }

            header("Location: " . BASE_URL . "admin-orders");
            exit;
        }
    }



    public function deleteOrder(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = "❌ Thiếu hoặc sai ID đơn hàng cần xóa.";
            header("Location: " . BASE_URL . "admin-orders");
            exit;
        }

        $orderId = (int)$_GET['id'];

        if ($orderId <= 0) {
            $_SESSION['error_message'] = "❌ ID đơn hàng không hợp lệ.";
            header("Location: " . BASE_URL . "admin-orders");
            exit;
        }

        try {
            $orderApi = new OrderApi();
            $success = $orderApi->deleteOrderById($orderId);

            if ($success) {
                $_SESSION['success_message'] = "✅ Đơn hàng đã được xóa thành công.";
            } else {
                $_SESSION['error_message'] = "❌ Không thể xóa đơn hàng do ràng buộc dữ liệu (ví dụ: đã có hóa đơn hoặc giao hàng).";
            }
        } catch (PDOException $e) {
            error_log("🛑 PDOException khi xóa đơn hàng ID=$orderId: " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi hệ thống khi xóa đơn hàng. Vui lòng thử lại sau.";
        } catch (Exception $e) {
            error_log("🛑 Exception khi xóa đơn hàng ID=$orderId: " . $e->getMessage());
            $_SESSION['error_message'] = "❌ Lỗi không xác định khi xóa đơn hàng.";
        }

        header("Location: " . BASE_URL . "admin-orders");
        exit;
    }



}
