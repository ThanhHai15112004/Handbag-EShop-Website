<?php
require_once __DIR__ . '/../../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../configs/config.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!AuthMiddleware::check()) {
    header("Location: " . BASE_URL . "login");
    exit;
}

$user = $_SESSION['user'];
$tokenExpiry = $user['token_expiry'] ?? time();
$remainingSeconds = $tokenExpiry - time();
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông Tin Cá Nhân - FastFood</title>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
        color: #ff6b6b;
    }

    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #ff6b6b, #ff8e53);
        min-height: 100vh;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 20px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 28px;
        font-weight: bold;
        color: #e53e3e;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(45deg, #e53e3e, #ff6b6b);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }

    .welcome-text {
        color: #333;
    }

    .logout-btn {
        background: #e53e3e;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .logout-btn:hover {
        background: #c53030;
        transform: translateY(-2px);
    }

    .main-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
    }

    .sidebar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        height: fit-content;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .sidebar h3 {
        color: #e53e3e;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }

    .menu-item:hover,
    .menu-item.active {
        background: linear-gradient(45deg, #e53e3e, #ff6b6b);
        color: white;
        transform: translateX(5px);
    }

    .menu-icon {
        width: 20px;
        height: 20px;
        background: currentColor;
        mask-size: contain;
        mask-repeat: no-repeat;
    }

    .content-area {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .section {
        display: none;
    }

    .section.active {
        display: block;
    }

    .section h2 {
        color: #e53e3e;
        margin-bottom: 25px;
        font-size: 24px;
    }

    .profile-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        color: #333;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 12px 15px;
        border: 2px solid #f0f0f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #e53e3e;
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
    }

    .save-btn {
        background: linear-gradient(45deg, #e53e3e, #ff6b6b);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(229, 62, 62, 0.3);
    }

    .order-card {
        background: #f9f9f9;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #e53e3e;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .order-id {
        font-weight: bold;
        color: #e53e3e;
    }

    .order-status {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .order-items {
        color: #666;
        margin-bottom: 10px;
    }

    .order-total {
        font-weight: bold;
        color: #e53e3e;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .main-content {
            grid-template-columns: 1fr;
        }

        .profile-form {
            grid-template-columns: 1fr;
        }

        .header {
            flex-direction: column;
            gap: 15px;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section.active {
        animation: fadeInUp 0.5s ease;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background-size: cover;
        background-position: center;
        border-radius: 50%;
        display: inline-block;
    }

    .pagination {
    margin-top: 20px;
    text-align: center;
    }
    .page-link {
    display: inline-block;
    margin: 0 5px;
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
    }
    .page-link.active {
    background-color: #e53e3e;
    color: white;
    border-color: #e53e3e;
    }
    .page-link:hover {
    background-color: #fdd;
    }


    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo"><a href="<?= BASE_URL ?>">🍔 FastFood</a></div>
            <div class="user-info">
                <?php
                    $avatarPath = !empty($acc['avatar_url']) 
                        ? $acc['avatar_url'] 
                        : 'assets/images/avatar/default-avatar.png';
                    ?>
                <div class="avatar" style="background-image: url('<?= BASE_URL . $avatarPath ?>')"></div>

                <div class="welcome-text">
                    <?php if (!empty($accountProfile)): ?>
                    <div>
                        Xin chào, <strong><?= htmlspecialchars($accountProfile['full_name']) ?></strong>
                    </div>
                    <div style="font-size: 15px; color: #e53e3e">
                        Điểm tích lũy: <?= number_format($accountProfile['point_balance'] ?? 0) ?>
                    </div>
                    <?php endif; ?>

                </div>
                <a href="#" class="logout-btn" onclick="confirmLogout(event)">Đăng xuất</a>
                <script>
                const BASE_URL = "<?= BASE_URL ?>";
                function confirmLogout(event) {
                    event.preventDefault(); // Không chuyển trang ngay

                    if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
                        // Điều hướng đến trang logout
                        window.location.href = BASE_URL + "logout?logout=success";
                    }
                }
                </script>

            </div>
        </div>

        <div class="main-content">
            <div class="sidebar">
                <h3>Tài khoản của tôi</h3>
                <div class="menu-item active" onclick="showSection('profile')">
                    <div class="menu-icon"></div>
                    <span>Thông tin cá nhân</span>
                </div>
                <div class="menu-item" onclick="showSection('orders')">
                    <div class="menu-icon"></div>
                    <span>Lịch sử đơn hàng</span>
                </div>
                <div class="menu-item" onclick="showSection('addresses')">
                    <div class="menu-icon"></div>
                    <span>Địa chỉ giao hàng</span>
                </div>
                <div class="menu-item" onclick="showSection('favorites')">
                    <div class="menu-icon"></div>
                    <span>Món ăn yêu thích</span>
                </div>
            </div>

            <div class="content-area">
                <div class="section active" id="profile">
                    <h2>Thông tin cá nhân</h2>
                    <form class="profile-form" method="post" action="<?= BASE_URL ?>profile-update">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="full_name"
                                value="<?= htmlspecialchars($accountProfile['full_name']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                value="<?= htmlspecialchars($accountProfile['email']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="username"
                                value="<?= htmlspecialchars($accountProfile['username']) ?>" />
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="birthday"
                                value="<?= htmlspecialchars($accountProfile['birthday'] ?? '') ?>" />
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label>
                            <select name="gender">
                                <?php foreach (['Nam', 'Nữ', 'Khác'] as $g): ?>
                                <option <?= ($accountProfile['gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Thành phố</label>
                            <input type="text" name="city"
                                value="<?= htmlspecialchars($accountProfile['city'] ?? '') ?>"
                                placeholder="Nhập tên thành phố..." />

                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ</label>
                            <textarea name="address"
                                rows="3"><?= htmlspecialchars($accountProfile['address'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="save-btn">Cập nhật thông tin</button>
                    </form>
                </div>

               <div class="section" id="orders">
                    <h2>Lịch sử đơn hàng</h2>

                    <?php if (!empty($pagedOrders)): ?>
                        <?php foreach ($pagedOrders as $o): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-id">
                                        #ĐH<?= str_pad($o['id_orders'], 6, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <div class="order-status status-<?= $o['delivery_status'] ?>">
                                        <?php
                                            $labels = [
                                                'pending'   => 'Chờ xác nhận',
                                                'shipping'  => 'Đang giao',
                                                'delivered' => 'Đã giao',
                                                'failed'    => 'Giao thất bại',
                                            ];
                                            echo $labels[$o['delivery_status']] 
                                                ?? ucfirst($o['delivery_status']);
                                        ?>
                                    </div>
                                </div>

                                <div class="order-items">
                                    <?= htmlspecialchars($o['items']) ?>
                                </div>

                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div style="color:#666">
                                        <?= date('d/m/Y - H:i', strtotime($o['order_date'])) ?>
                                    </div>
                                    <div class="order-total">
                                        <?= number_format($o['total_price'], 0, ',', '.') ?>₫
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav class="pagination">
                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                    <a
                                    href="<?= BASE_URL ?>profile?page=<?= $p ?>"
                                    class="page-link<?= $p === $currentPage ? ' active' : '' ?>"
                                    >
                                    <?= $p ?>
                                    </a>
                                <?php endfor; ?>
                            </nav>
                        <?php endif; ?>

                    <?php else: ?>
                        <p>Chưa có đơn hàng nào.</p>
                    <?php endif; ?>
                </div>



                <div class="section" id="addresses">
                    <h2>Địa chỉ giao hàng</h2>
                    <div class="order-card">
                        <div class="order-header">
                            <div><strong>Địa chỉ nhà</strong></div>
                            <div style="
                    color: #e53e3e;
                    font-size: 12px;
                    background: #ffe6e6;
                    padding: 3px 10px;
                    border-radius: 10px;
                  ">
                                Mặc định
                            </div>
                        </div>
                        <div>123 Nguyễn Huệ, Quận 1, TP.HCM</div>
                        <div style="color: #666; margin-top: 5px">
                            Nguyễn Văn A - 0901234567
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="order-header">
                            <div><strong>Địa chỉ công ty</strong></div>
                        </div>
                        <div>456 Lê Lợi, Quận 1, TP.HCM</div>
                        <div style="color: #666; margin-top: 5px">
                            Nguyễn Văn A - 0901234567
                        </div>
                    </div>

                    <button class="save-btn">+ Thêm địa chỉ mới</button>
                </div>

                <div class="section" id="favorites">
                    <h2>Món ăn yêu thích</h2>
                    <div class="order-card">
                        <div style="display: flex; align-items: center; gap: 15px">
                            <div style="
                    width: 60px;
                    height: 60px;
                    background: linear-gradient(45deg, #e53e3e, #ff6b6b);
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 24px;
                  ">
                                🍔
                            </div>
                            <div style="flex: 1">
                                <div style="font-weight: bold; margin-bottom: 5px">
                                    Burger Bò Phô Mai Đặc Biệt
                                </div>
                                <div style="color: #666">
                                    Bánh mì burger, thịt bò, phô mai, salad, sốt đặc biệt
                                </div>
                                <div style="color: #e53e3e; font-weight: bold; margin-top: 5px">
                                    129.000đ
                                </div>
                            </div>
                            <button style="
                    background: #e53e3e;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 20px;
                    cursor: pointer;
                  ">
                                Đặt ngay
                            </button>
                        </div>
                    </div>

                    <div class="order-card">
                        <div style="display: flex; align-items: center; gap: 15px">
                            <div style="
                    width: 60px;
                    height: 60px;
                    background: linear-gradient(45deg, #e53e3e, #ff6b6b);
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 24px;
                  ">
                                🍗
                            </div>
                            <div style="flex: 1">
                                <div style="font-weight: bold; margin-bottom: 5px">
                                    Gà Rán Giòn Cay
                                </div>
                                <div style="color: #666">
                                    Gà rán tẩm bột giòn, gia vị cay đặc trưng
                                </div>
                                <div style="color: #e53e3e; font-weight: bold; margin-top: 5px">
                                    89.000đ
                                </div>
                            </div>
                            <button style="
                    background: #e53e3e;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 20px;
                    cursor: pointer;
                  ">
                                Đặt ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll(".section");
        sections.forEach((section) => section.classList.remove("active"));

        const menuItems = document.querySelectorAll(".menu-item");
        menuItems.forEach((item) => item.classList.remove("active"));

        document.getElementById(sectionId).classList.add("active");

        event.currentTarget.classList.add("active");
    }

    document.addEventListener("DOMContentLoaded", function() {
        const saveBtn = document.querySelector(".save-btn");
        if (saveBtn) {
            saveBtn.addEventListener("click", function() {
                alert("Thông tin đã được cập nhật thành công!");
            });
        }
    });
    </script>
</body>

</html>