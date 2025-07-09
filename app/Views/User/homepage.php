<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/styles.css">
    <link rel="shortcut icon" href="../public/assets/imgs/logos/iconElisaShop.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <!-- Promo Banner Section -->
        <div class="header__promo-banner">
            <div class="promo-banner__item">SIÊU SALE NGÀY HỘI</div>
            <div class="promo-banner__item promo-banner__item--highlight">GIẢM TỚI 50%++</div>
            <div class="promo-banner__item">BẮT ĐẦU TỪ HÔM NAY</div>
        </div>

        <!-- Sub Navigation Section -->
        <div class="header__subnav">
            <div class="subnav__logo">
                <?php
                echo '<a href="?url=product" ' ?>>
                    <img src="../public/assets/imgs/logos/logoElisaShop.png" alt="Elisa Shop Logo" class="logo__image">
                </a>
                
            </div>

            <div class="subnav__search">
                <div class="search__container">
                    <select class="search__category-select">
                        <option value="all">All</option>
                        <option value="handbags">Túi xách</option>
                        <option value="accessories">Phụ kiện</option>
                    </select>
                    <input type="text" class="search__input" placeholder="Tìm kiếm sản phẩm...">
                    <button class="search__button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="subnav__selector-actions">
                <div class="selector-actions__selectors">
                    <div class="selector__language">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/2560px-Flag_of_Vietnam.svg.png" alt="Vietnam Flag" class="selector__flag">
                        <span class="selector__arrow">▼</span>
                    </div>
                    <div class="selector__currency">
                        <span class="selector__text">VND</span>
                        <span class="selector__arrow">▼</span>
                    </div>
                </div>

                <ul class="selector-actions__links">
                    <li class="links__item">
                        <a href="#" class="links__link">TRỢ GIÚP</a>
                    </li>
                    <li class="links__item">
                        <a href="#" class="links__link">LIÊN HỆ</a>
                    </li>
                    <li class="links__item">
                        <a href="#" class="links__link links__link--icon-text">
                            <i class="fa fa-user"></i>
                            <span>Tài khoản</span>
                        </a>
                    </li>
                    <li class="links__item">
                        <a href="#" class="links__link links__link--icon-text">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Giỏ hàng</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Navigation Section -->
        <nav class="header__mainnav">
            <ul class="mainnav__menu">
                <li class="menu__item menu__item--active">
                    <a href="#" class="menu__link">TRANG CHỦ</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">HÀNG MỚI</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">BÁN CHẠY</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link menu__link--highlight">FLASH SALE</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link menu__link--highlight">ĐỒNG GIÁ 199K</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">SẢN PHẨM</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">BỘ SƯU TẬP</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">TIN TỨC</a>
                </li>
                <li class="menu__item">
                    <a href="#" class="menu__link">GIỚI THIỆU</a>
                </li>
            </ul>
        </nav>
    </header>
</body>
</html>