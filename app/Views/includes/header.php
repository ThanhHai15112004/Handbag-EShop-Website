<?php include_once(__DIR__ . '/../includes/head.php'); ?>
<?php require_once __DIR__ . '/../../configs/config.php'; ?>

<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $redirect = (isset($_SESSION['user']) 
                && isset($_SESSION['user']['token_expiry']) 
                && time() < $_SESSION['user']['token_expiry'])
        ? BASE_URL . 'profile'
        : BASE_URL . 'login';
       
    require_once __DIR__ . '/../../Models/Carts/CartApi.php';
    $cartApi = new CartApi();
    $cartItems = $cartApi->getCart();
    $cartQuantity = 0;
    foreach ($cartItems as $item) {
        $cartQuantity += $item['quantity'];
    }

    $isLoggedIn = isLoggedInWithValidToken();
    $isAdmin = $isLoggedIn && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';

?>




<body class="body header-fixed main counter-scroll home1">
    <?php if (!empty($_SESSION['login_success'])): ?>
    <div id="loginSuccessPopup" class="success-popup">
        <?= htmlspecialchars($_SESSION['login_success']) ?>
    </div>
    <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>
    <style>
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
    </style>
    <script>
    const BASE_URL = "<?= BASE_URL ?>";
    window.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("loginSuccessPopup");
        if (popup) {
            setTimeout(() => popup.style.display = "none", 5000);
        }
    });
    </script>






    <?php if (!empty($_GET['logout']) && $_GET['logout'] === 'success'): ?>
    <div id="logoutSuccessPopup" class="popup-logout">
        Bạn đã đăng xuất thành công.
    </div>
    <?php endif; ?>
    <style>
    .popup-logout {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #555;
        color: white;
        padding: 10px 16px;
        border-radius: 5px;
        font-size: 14px;
        z-index: 9999;
        opacity: 1;
        animation: fadeOut 5s forwards;
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        80% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
    </style>

<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>



    <!-- preloade -->
    <div class="preload preload-container">
        <div class="preload-logo"></div>
    </div>
    <!-- preload -->
    <div id="wrapper">
        <!-- topbar -->
        <div class="topbar style">
            <div class="container w_1200">
                <div class="row">
                    <div class="col-md-12 wow fadeIn" data-wow-delay=".3s">
                        <div class="topbar-inner flex">
                            <div class="topbar__logo mt3">
                                <a href="<?= BASE_URL ?>">
                                    <img src="assets/images/logo/logo.png" alt="">
                                </a>
                            </div>
                            <!-- /.mobile-button -->
                            <div class="topbar-info">
                                <div class="flex items-center pr-30 tablet-r-auto hide-mobile hide-mobile">
                                    <div class="icon mr-15">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <ul class="phone">
                                        <li>
                                            <p>Gọi đặt hàng</p>
                                            <p>19008386</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mr-25 hide-tablet">
                                    <img src="../public/assets/images/icon/dashed.png" alt="">
                                </div>
                                <ul class="action gradient">
                                    <?php if ($isAdmin): ?>
                                        <li style="height: 100%; display: flex; align-items: center;">
                                            <a href="<?= BASE_URL ?>admin" title="Quản trị Admin"
                                                style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" style="width: 22px; height: 22px; fill: white;">
                                                    <path
                                                        d="M622.3 271.1c9.1-9.1 14.4-21.6 14.4-34.6s-5.3-25.5-14.4-34.6l-45.3-45.3c-9.1-9.1-21.6-14.4-34.6-14.4s-25.5 5.3-34.6 14.4L480 165.4V128c0-35.3-28.7-64-64-64H224c-35.3 0-64 28.7-64 64v37.4l-27.7-27.7c-9.1-9.1-21.6-14.4-34.6-14.4s-25.5 5.3-34.6 14.4l-45.3 45.3c-9.1 9.1-14.4 21.6-14.4 34.6s5.3 25.5 14.4 34.6L93.3 352H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h576c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64h-29.3l69.6-80.9zM320 288c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z" />
                                                </svg>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a href="<?= $redirect ?>" class="login" title="Quản lý tài khoản người dùng">

                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                style="enable-background: new 0 0 512 512" xml:space="preserve"
                                                class="">
                                                <g>
                                                    <path
                                                        d="M256 0c-74.439 0-135 60.561-135 135s60.561 135 135 135 135-60.561 135-135S330.439 0 256 0zM423.966 358.195C387.006 320.667 338.009 300 286 300h-60c-52.008 0-101.006 20.667-137.966 58.195C51.255 395.539 31 444.833 31 497c0 8.284 6.716 15 15 15h420c8.284 0 15-6.716 15-15 0-52.167-20.255-101.461-57.034-138.805z"
                                                        fill="#ffffff" data-original="#ffffff" class="" opacity="1">
                                                    </path>
                                                </g>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="flat-show-search">
                                        <a href="#" class="show-search" title="Tìm kiếm 1 thứ gì đó">
                                            <svg class="" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512"
                                                x="0" y="0" viewBox="0 0 118.783 118.783"
                                                style="enable-background: new 0 0 512 512" xml:space="preserve">
                                                <g>
                                                    <path
                                                        d="M115.97 101.597 88.661 74.286a47.75 47.75 0 0 0 7.333-25.488c0-26.509-21.49-47.996-47.998-47.996S0 22.289 0 48.798c0 26.51 21.487 47.995 47.996 47.995a47.776 47.776 0 0 0 27.414-8.605l26.984 26.986a9.574 9.574 0 0 0 6.788 2.806 9.58 9.58 0 0 0 6.791-2.806 9.602 9.602 0 0 0-.003-13.577zM47.996 81.243c-17.917 0-32.443-14.525-32.443-32.443s14.526-32.444 32.443-32.444c17.918 0 32.443 14.526 32.443 32.444S65.914 81.243 47.996 81.243z"
                                                        fill="#000000" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </a>
                                        <div class="top-search">
                                            <form action="#" id="searchform-all" method="get">
                                                <div>
                                                    <input type="text" id="s" class="sss" placeholder="Search...">
                                                    <button type="submit" id="searchsubmit">
                                                        <svg class="" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="512"
                                                            height="512" x="0" y="0" viewBox="0 0 118.783 118.783"
                                                            style="enable-background: new 0 0 512 512"
                                                            xml:space="preserve">
                                                            <g>
                                                                <path
                                                                    d="M115.97 101.597 88.661 74.286a47.75 47.75 0 0 0 7.333-25.488c0-26.509-21.49-47.996-47.998-47.996S0 22.289 0 48.798c0 26.51 21.487 47.995 47.996 47.995a47.776 47.776 0 0 0 27.414-8.605l26.984 26.986a9.574 9.574 0 0 0 6.788 2.806 9.58 9.58 0 0 0 6.791-2.806 9.602 9.602 0 0 0-.003-13.577zM47.996 81.243c-17.917 0-32.443-14.525-32.443-32.443s14.526-32.444 32.443-32.444c17.918 0 32.443 14.526 32.443 32.444S65.914 81.243 47.996 81.243z"
                                                                    fill="#000000" data-original="#000000" class="">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <li class="cart relative" title="Giỏ hàng của bạn">
                                        <a href="javascript:void(0);" class="open-mini-cart">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512"
                                                x="0" y="0" viewBox="0 0 24 24"
                                                style="enable-background: new 0 0 512 512" xml:space="preserve"
                                                class="">
                                                <g>
                                                    <path
                                                        d="M1 1a1 1 0 1 0 0 2h1.78a.694.694 35.784 0 1 .657.474l3.297 9.893c.147.44.165.912.053 1.362l-.271 1.087C6.117 17.41 7.358 19 9 19h12a1 1 0 1 0 0-2H9c-.39 0-.64-.32-.545-.697l.205-.818A.64.64 142.028 0 1 9.28 15H20a1 1 0 0 0 .95-.684l2.665-8A1 1 0 0 0 22.666 5H6.555a.694.694 35.783 0 1-.658-.474l-.948-2.842A1 1 0 0 0 4 1zm7 19a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"
                                                        fill="#ffffff" data-original="#000000" class="" opacity="1">
                                                    </path>
                                                </g>
                                            </svg>
                                            <?php if ($cartQuantity > 0): ?>
                                                <span class="cart-badge" style="position: absolute; top: 1px; right: -5px; background: red; color: white; font-size: 11px; font-weight: 600; padding: 2px 5px; border-radius: 80%; line-height: 1; z-index: 999;"> <?= $cartQuantity ?></span>
                                            <?php endif; ?>
                                            
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Danh mục yêu thích của bạn">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512"
                                                x="0" y="0" viewBox="0 0 512 512"
                                                style="enable-background: new 0 0 512 512" xml:space="preserve"
                                                class="">
                                                <g>
                                                    <path
                                                        d="M376 30c-27.783 0-53.255 8.804-75.707 26.168-21.525 16.647-35.856 37.85-44.293 53.268-8.437-15.419-22.768-36.621-44.293-53.268C189.255 38.804 163.783 30 136 30 58.468 30 0 93.417 0 177.514c0 90.854 72.943 153.015 183.369 247.118 18.752 15.981 40.007 34.095 62.099 53.414C248.38 480.596 252.12 482 256 482s7.62-1.404 10.532-3.953c22.094-19.322 43.348-37.435 62.111-53.425C439.057 330.529 512 268.368 512 177.514 512 93.417 453.532 30 376 30z"
                                                        fill="#ffffff" data-original="#000000" class="" opacity="1">
                                                    </path>
                                                </g>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                                <div class="dropdown selector-drop" id="language">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                        viewBox="0 0 24 24" style="enable-background: new 0 0 512 512"
                                        xml:space="preserve">
                                        <g>
                                            <path
                                                d="M12 16a1 1 0 0 1-.71-.29l-6-6a1 1 0 0 1 1.42-1.42l5.29 5.3 5.29-5.29a1 1 0 0 1 1.41 1.41l-6 6a1 1 0 0 1-.7.29z"
                                                data-name="16" fill="#0c0c0c" data-original="#000000" class=""
                                                opacity="1"></path>
                                        </g>
                                    </svg>
                                    <img class="icon" src="../public/assets/images/icon/vietnam-icon.png" alt="">
                                    <a href="javascript:void(0);" class="btn-selector" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Việt Nam
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li class="dropdown-item active">
                                            <img src="../public/assets/images/icon/vietnam-icon.png" alt="">
                                            <span>Việt Nam</span>
                                        </li>
                                        <li class="dropdown-item">
                                            <img src="../public/assets/images/icon/usa.svg" alt="">
                                            <span>Tiếng Anh</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end topbar -->
        <!-- Header -->
        <header id="header_main" class="header style1">
            <div class="container w_1290">
                <div id="site-header" class="style1">
                    <div class="site-header-inner w-full">
                        <div class="header__logo-mobile">
                            <a href="<?= BASE_URL ?>"><img src="../public/assets/images/logo/logo2.png" alt=""></a>
                        </div>
                        <nav id="main-nav" class="main-nav">
                            <ul id="menu-primary-menu" class="menu">
                                <li class="menu-item">
                                    <a href="<?= BASE_URL ?>"> Trang chủ </a>
                                </li>
                                <li class="menu-item menu-item-has-children">
                                    <a href="#"> Trang </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a class="menu-item" href="<?= BASE_URL ?>about">Về chúng tôi</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>portfolio">Bộ sưu tập</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>teams">Đội ngũ</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>book-table">Đặt bàn</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>recruitment">Tuyển dụng</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>locations">Hệ thống cửa hàng</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>faq">Câu hỏi thường gặp</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>404">404</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a href="<?= BASE_URL ?>menu-restaurant"> Menu </a>
                                </li>

                                <li class="menu-item">
                                    <a href="<?= BASE_URL ?>offers"> Ưu đãi </a>
                                </li>
                                <li class="menu-item menu-item-has-children">
                                    <a href="#"> Blog </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a class="menu-item" href="<?= BASE_URL ?>blog">Blog</a>
                                            <a class="menu-item" href="<?= BASE_URL ?>blog-details">Blog Details</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item">
                                    <a href="<?= BASE_URL ?>contact"> Liên hệ </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="right ml-auto flex justify-end items-center">
                            <a href="<?= BASE_URL ?>menu-restaurant" class="tf-button mr-25">đặt hàng trực tuyến</a>
                            <div class="hamburger mr-12">
                                <img class="icon_hamburger cursor-pointer"
                                    src="../public/assets/images/icon/hamburger.png" alt="">
                                <div class="content">
                                    <div class="icon close_hamburger cursor-pointer">x</div>
                                    <h5 class="mb-36 color-3 text-spacing-1">
                                        Thức ăn nhanh tốt nhất <br>
                                        Đặc biệt và chất lượng.
                                    </h5>
                                    <ul class="mb-42 list">
                                        <li>
                                            <p class="text-spacing-1 uppercase text-500 mb-0 font-1 text-spacing-1">
                                                gọi để đặt hàng
                                            </p>
                                            <p class="phone font-1 text-spacing-1">19008386</p>
                                        </li>
                                        <li class="style">
                                            <p class="mb4 font2 color-3">
                                                613 Âu Cơ, Phường Phú Trung, Tân Phú, TP HCM
                                            </p>
                                            <p class="font2 color-3">anhminh@gmail.com</p>
                                        </li>
                                        <li>
                                            <p class="mb5 font2 color-3">
                                                <span>Thứ 2 – Thứ 5:</span> 8.00 – 21.00
                                            </p>
                                            <p class="mb5 font2 color-3">
                                                <span>Thứ 6 – Thứ 7 : </span> 9.00 – 22.00
                                            </p>
                                            <p class="mb5 font2 color-3">
                                                <span>Chủ nhật:</span> 8.00 – 23.00
                                            </p>
                                            <p class="mb5 font2 color-3">
                                                <span>Ngày lễ: </span>
                                                <span class="color-2">Đóng cửa</span>
                                            </p>
                                        </li>
                                    </ul>
                                    <ul class="social-style relative flex items-center justify-center">
                                        <li>
                                            <a href="#" class="active"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-skype"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="fab fa-facebook-messenger"></i></a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                    viewBox="0 0 448 512">
                                                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <path
                                                        d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /#main-nav -->
                    </div>
                    <a class="flex justify-center mobile-logo">
                        <img src="../public/assets/images/logo/logo.png" alt="">
                    </a>
                    <div class="mobile-button"><span></span></div>
                </div>
            </div>
        </header>


        <script>
        const isLoggedIn =
            <?= isset($_SESSION['user']) && time() < $_SESSION['user']['token_expiry'] ? 'true' : 'false' ?>;
        </script>