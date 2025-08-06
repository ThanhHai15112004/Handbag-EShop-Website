<?php
require_once __DIR__ . '/../../../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../configs/config.php';

if (!AuthMiddleware::check()) {
    header("Location: " . BASE_URL . "login");
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: " . BASE_URL . "");
    exit;
}
require_once __DIR__ . '/index.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Quản lý Thức ăn Nhanh</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        padding: 0 20px;
        font-size: 24px;
    }

    .nav-item {
        padding: 15px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
        border-left: 3px solid transparent;
    }

    .nav-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-left-color: #fff;
    }

    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left-color: #fff;
    }

    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 30px;
    }

    .header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .header-left h1 {
        margin: 0;
        margin-bottom: 5px;
    }

    .header-left p {
        margin: 0;
        color: #666;
    }

    .header-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 15px;
        background: #f8f9fa;
        border-radius: 25px;
        border: 1px solid #e9ecef;
    }

    .admin-avatar {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }

    .admin-details {
        display: flex;
        flex-direction: column;
    }

    .admin-name {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin: 0;
    }

    .admin-role {
        font-size: 12px;
        color: #666;
        margin: 0;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .dropdown-btn:hover {
        background-color: #f8f9fa;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 180px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        z-index: 1000;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f8f9fa;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.logout:hover {
        background-color: #fee;
        color: #dc3545;
    }

    .content-section {
        display: none;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .content-section.active {
        display: block;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .table tr:hover {
        background-color: #f8f9fa;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #888;
        width: 70%;
        max-width: 700px;
        animation: fadeIn 0.3s ease;
        
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }

    .close:hover {
        color: #000;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-30px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .stat-number {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 300px;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 5px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        gap: 10px;
    }

    .pagination button {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        border-radius: 4px;
    }

    .pagination button.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    #productForm {
        padding: 20px;
        max-width: 100%;
        font-family: "Segoe UI", sans-serif;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 15px;
    }

    a {
        text-decoration: none;
        color: #ff6b6b;
    }

    #productForm h4 {
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }

    #productForm small.text-muted {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #777;
    }

    #productForm .btn {
        padding: 8px 20px;
        font-size: 15px;
        margin-left: 10px;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
    }

    .success-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .flash-message {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 18px;
        border-radius: 5px;
        color: #fff;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        opacity: 1;
        animation: fadeOutFlash 5s forwards;
    }

    .flash-success {
        background-color: #28a745;
    }

    .flash-error {
        background-color: #dc3545;
    }

    @keyframes fadeOutFlash {
        0% {
            opacity: 1;
        }

        80% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            display: none;
        }
    }

    .success-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .flash-message {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 18px;
        border-radius: 5px;
        color: #fff;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        opacity: 1;
        animation: fadeOutFlash 5s forwards;
    }

    .flash-success {
        background-color: #28a745;
    }

    .flash-error {
        background-color: #dc3545;
    }

    @keyframes fadeOutFlash {
        0% {
            opacity: 1;
        }

        80% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            display: none;
        }
    }
    </style>
</head>

<body>

    <body class="body header-fixed main counter-scroll home1">
        <?php if (!empty($_SESSION['login_success'])): ?>
        <div id="loginSuccessPopup" class="success-popup">
            <?= htmlspecialchars($_SESSION['login_success']) ?>
        </div>
        <?php unset($_SESSION['login_success']); ?>
        <?php endif; ?>


        <script>
        window.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("loginSuccessPopup");
            if (popup) {
                setTimeout(() => popup.style.display = "none", 5000);
            }
        });
        </script>

        <div class="container">
            <!-- Sidebar -->
            <div class="sidebar">
                <h2>🍔 FastFood Admin</h2>
                <div class="nav-item active" data-target="dashboard">📊 Dashboard</div>
                <div class="nav-item" data-target="accounts">👤 Quản lý Tài khoản</div>
                <div class="nav-item" data-target="products">🍟 Quản lý Sản phẩm</div>
                <div class="nav-item" data-target="categories">🍟 Quản lý Loại Sản phẩm</div>
                <div class="nav-item" data-target="delivery">📦 Quản lý Giao Hàng</div>
                <div class="nav-item" data-target="orders">🛒 Quản lý Đơn hàng</div>
            </div>


            <!-- Main Content -->
            <div class="main-content">
                <!-- Dashboard -->
                <div id="dashboard" class="content-section active">
                    <div class="header">
                        <div class="header-left">
                            <h1>Dashboard</h1>
                            <p>Chào mừng đến với hệ thống quản lý</p>
                        </div>
                        <div class="header-right">
                            <div class="admin-info">
                                <div class="admin-avatar">AD</div>
                                <div class="admin-details">
                                    <div class="admin-name" id="adminName">Admin User</div>
                                    <div class="admin-role">Quản trị viên</div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="dropdown-btn">
                                    ⚙️
                                </button>
                                <div class="dropdown-content">
                                    <div class="dropdown-item" id="openProfileModal">👤 Thông tin tài khoản</div>
                                    <div class="dropdown-item" id="openChangePasswordModal">🔒 Đổi mật khẩu</div>
                                    <div class="dropdown-item"><a href="#" class="logout-btn"
                                            onclick="confirmLogout(event)">Đăng xuất</a></div>

                                    <script>
                                    function confirmLogout(event) {
                                        event.preventDefault();

                                        if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
                                            const logoutUrl = BASE_URL.endsWith('/') ? BASE_URL +
                                                'logout?logout=success' : BASE_URL + '/logout?logout=success';
                                            window.location.href = logoutUrl;
                                        }
                                    }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number"></div>
                            <div>Tổng Tài khoản</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"></div>
                            <div>Tổng Sản phẩm</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"></div>
                            <div>Tổng Giao Hàng</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"></div>
                            <div>Đơn hàng</div>
                        </div>
                    </div>
                </div>
            

                <?php include_once(__DIR__ . '/../../components/Admin/accounts_manager.php'); ?>

                <?php include_once(__DIR__ . '/../../components/Admin/products_manager.php'); ?>

                <?php include_once(__DIR__ . '/../../components/Admin/categories_manager.php'); ?>

                <?php include_once(__DIR__ . '/../../components/Admin/dashbroad.php'); ?>

                <?php include_once(__DIR__ . '/../../components/Admin/delivery_manager.php'); ?>

                <?php include_once(__DIR__ . '/../../components/Admin/orders_manager.php'); ?>



                <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

                <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="flash-message flash-success">
                    <?= $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error_message'])): ?>
                <div class="flash-message flash-error">
                    <?= $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </div>


                <!-- Sidebar  -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const navItems = document.querySelectorAll('.nav-item');
                    const sections = document.querySelectorAll('.content-section');

                    const savedTab = sessionStorage.getItem('activeTab');
                    if (savedTab) {
                        activateTab(savedTab);
                    }

                    navItems.forEach(item => {
                        item.addEventListener('click', function() {
                            const targetId = this.getAttribute('data-target');
                            sessionStorage.setItem('activeTab', targetId);
                            activateTab(targetId);
                        });
                    });

                    function activateTab(targetId) {
                        sections.forEach(section => {
                            section.classList.remove('active');
                        });

                        navItems.forEach(item => {
                            item.classList.remove('active');
                        });

                        const targetSection = document.getElementById(targetId);
                        const targetNav = document.querySelector(`.nav-item[data-target="${targetId}"]`);
                        if (targetSection) targetSection.classList.add('active');
                        if (targetNav) targetNav.classList.add('active');
                    }
                });


                //Login admin
                document.addEventListener('DOMContentLoaded', function() {
                    const dropdownBtn = document.querySelector('.dropdown-btn');
                    const dropdownContent = document.querySelector('.dropdown-content');

                    dropdownBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        dropdownContent.classList.toggle('show');
                    });

                    document.addEventListener('click', function() {
                        dropdownContent.classList.remove('show');
                    });
                });



                //open/close dashbroad
                document.addEventListener('DOMContentLoaded', function() {
                    // Modal Thông tin Admin
                    const openProfileBtn = document.getElementById('openProfileModal');
                    const profileModal = document.getElementById('adminProfileModal');
                    const closeProfileBtn = document.getElementById('closeProfileModal');
                    const cancelProfileBtn = document.getElementById('cancelProfileModal');

                    openProfileBtn.addEventListener('click', () => {
                        profileModal.style.display = 'block';
                    });

                    closeProfileBtn.addEventListener('click', () => {
                        profileModal.style.display = 'none';
                    });

                    cancelProfileBtn.addEventListener('click', () => {
                        profileModal.style.display = 'none';
                    });

                    // Modal Đổi mật khẩu
                    const openChangePassBtn = document.getElementById('openChangePasswordModal');
                    const changePassModal = document.getElementById('changePasswordModal');
                    const closeChangePassBtn = document.getElementById('closeChangePasswordModal');
                    const cancelChangePassBtn = document.getElementById('cancelChangePasswordModal');

                    openChangePassBtn.addEventListener('click', () => {
                        changePassModal.style.display = 'block';
                    });

                    closeChangePassBtn.addEventListener('click', () => {
                        changePassModal.style.display = 'none';
                    });

                    cancelChangePassBtn.addEventListener('click', () => {
                        changePassModal.style.display = 'none';
                    });

                    // Đóng khi bấm ra ngoài modal
                    window.addEventListener('click', function(e) {
                        if (e.target === profileModal) profileModal.style.display = 'none';
                        if (e.target === changePassModal) changePassModal.style.display = 'none';
                    });
                });
                </script>





    </body>

</html>