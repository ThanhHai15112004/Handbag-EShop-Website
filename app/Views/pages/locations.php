<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>
<section class="page-title relative mt-122">
    <div class="container w_1890">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-inner hidden">
                    <div class="bg-page-title-inner">
                        <img class="parallax" src="assets/images/backgroup/page-title_10.jpg" alt="">
                    </div>
                    <img class="icon_1 wow fadeInLeft" data-wow-delay=".2s"
                        src="assets/images/backgroup/icon_page-title.png" alt="">
                    <img class="icon_2 wow fadeInRight" data-wow-delay=".2s"
                        src="assets/images/backgroup/icon_2_page-title.png" alt="">
                    <div class="overlay"></div>
                    <div class="breadcrumb flex flex-column items-center wow fadeIn" data-wow-delay=".2s">
                        <h1 class="title center">Hệ thống cửa hàng</h1>
                        <ul>
                            <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                    viewBox="0 0 24 24" style="enable-background: new 0 0 512 512" xml:space="preserve">
                                    <g>
                                        <path fill="#0c0c0c" fill-rule="evenodd"
                                            d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                                            clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                                    </g>
                                </svg>
                                <span>Hệ thống cửa hàng</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="pt-130 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-50 wow fadeIn" data-wow-delay=".2s">
                <div class="location-box gradient">
                    <h4>
                        Basilico Fastfood <span class="color-main"> TP HCM</span>
                    </h4>
                    <ul>
                        <li>
                            <span>Địa chỉ: 613 Âu Cơ, Phường Phú Trung, Tân Phú, TP HCM</span>
                        </li>
                        <li><span>Email: anhminh@gmail.com</span></li>
                        <li><span>Điện thoại: 19008386</span></li>
                        <li>
                            <span>Giờ mở cửa: Thứ 2 - Thứ 6: 8h00 - 22h00 <br>
                                Thứ 7 - Chủ nhật: 9h00 - 23h00, Ngày lễ: Đóng cửa</span>
                        </li>
                    </ul>
                    <div class="flex">
                        <a href="<?= BASE_URL ?>contact" class="tf-button">Xem bản đồ
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                viewBox="0 0 24 24" style="enable-background: new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path fill="#0c0c0c" fill-rule="evenodd"
                                        d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                                        clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                                </g>
                            </svg></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mb-50 wow fadeIn" data-wow-delay=".4s">
                <div class="widget-gg-map flex hidden radius-20 h-full min-height-400">
                    <iframe
                        src="https://www.google.com/maps?q=613+Âu+Cơ,+Phường+Phú+Trung,+Tân+Phú,+TP+HCM,+Vietnam&output=embed"
                        style="border: 0; width: 100%" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 mb-50 wow fadeIn" data-wow-delay=".6s">
                <div class="hidden radius-20 h-full">
                    <img class="h-full w-full" src="assets/images/common/item_39.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="tf-section restaurant-section pt-118 pb-120">
    <div class="overlay"></div>
    <div class="img-bg">
        <img class="parallax" src="assets/images/backgroup/bg-section-14.jpg" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="restaurant-form">
                    <form action="#">
                        <h2>Tìm cửa hàng gần bạn</h2>
                        <p class="desc">
                            Tìm kiếm hệ thống cửa hàng Basilico trên toàn quốc.
                        </p>
                        <fieldset class="mb-30">
                            <input type="text" name="inp" placeholder="Nhập địa chỉ hoặc khu vực bạn muốn tìm"
                                required="">
                        </fieldset>
                        <div class="flex flex-wrap">
                            <button class="tf-button mr-12 mb-10" type="submit">
                                sử dụng vị trí của tôi <i class="fas fa-map-marker-alt"></i>
                            </button>
                            <button class="tf-button mb-10 style" type="submit">
                                tìm kiếm cửa hàng
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                    viewBox="0 0 118.783 118.783" style="enable-background: new 0 0 512 512"
                                    xml:space="preserve">
                                    <g>
                                        <path
                                            d="M115.97 101.597 88.661 74.286a47.75 47.75 0 0 0 7.333-25.488c0-26.509-21.49-47.996-47.998-47.996S0 22.289 0 48.798c0 26.51 21.487 47.995 47.996 47.995a47.776 47.776 0 0 0 27.414-8.605l26.984 26.986a9.574 9.574 0 0 0 6.788 2.806 9.58 9.58 0 0 0 6.791-2.806 9.602 9.602 0 0 0-.003-13.577zM47.996 81.243c-17.917 0-32.443-14.525-32.443-32.443s14.526-32.444 32.443-32.444c17.918 0 32.443 14.526 32.443 32.444S65.914 81.243 47.996 81.243z"
                                            fill="#ffffff" opacity="1" data-original="#000000" class=""></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once(__DIR__ . '/../includes/footer.php'); ?>
<a id="scroll-top"></a>
<script src="app/js/jquery.min.js"></script>
<script type="text/javascript" src="app/js/jquery.cookie.min.js"></script>
<script src="app/js/plugin.js"></script>
<script src="app/js/swiper-bundle.min.js"></script>
<script src="app/js/swiper.js"></script>
<script src="app/js/jquery-validate.js"></script>
<script src="app/js/countto.js"></script>
<script src="app/js/jquery.easing.js"></script>
<script src="app/js/wow.min.js"></script>
<script src="app/js/app.js"></script>
<script src="app/js/bootstrap.bundle.min.js"></script>
<script src="app/js/simpleParallax.min.js"></script>
<script>
var image = document.getElementsByClassName("parallax");
new simpleParallax(image);
</script>
<script>
var swiper = new Swiper(".sl-testimonial", {
    direction: "horizontal",
    loop: false,
    navigation: {
        nextEl: ".swiper-next-testimonial",
        prevEl: ".swiper-prev-testimonial",
    },
});

var testimonialLinks = document.querySelectorAll(
    ".testimonial-list-item a"
);
testimonialLinks.forEach(function(link) {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        var slideIndex = parseInt(link.getAttribute("data-slide-index"));
        swiper.slideTo(slideIndex, 500);
        testimonialLinks[slideIndex].classList.add("active");
        // Xóa lớp 'active' khỏi tất cả các link
        testimonialLinks.forEach(function(link) {
            link.classList.remove("active");
        });

        // Thêm lớp 'active' cho link được click
        link.classList.add("active");
    });
});

let hamburger = document.querySelector(".hamburger");
let icon_hamburger = hamburger.querySelector(".icon_hamburger");
let close_hamburger = hamburger.querySelector(".close_hamburger");
icon_hamburger.addEventListener("click", function() {
    hamburger.classList.toggle("toggle");
});
close_hamburger.addEventListener("click", function() {
    hamburger.classList.toggle("toggle");
});
</script>
</body>

</html>