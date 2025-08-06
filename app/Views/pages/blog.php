<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>
<section class="page-title relative mt-122 hidden">
    <div class="container w_1890">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-inner hidden">
                    <div class="bg-page-title-inner">
                        <img class="parallax" src="assets/images/backgroup/page-title_9.jpg" alt="">
                    </div>
                    <img class="icon_1 wow fadeInLeft" data-wow-delay=".2s"
                        src="assets/images/backgroup/icon_page-title.png" alt="">
                    <img class="icon_2 wow fadeInRight" data-wow-delay=".2s"
                        src="assets/images/backgroup/icon_2_page-title.png" alt="">
                    <div class="overlay"></div>
                    <div class="breadcrumb flex flex-column items-center wow fadeIn" data-wow-delay=".2s">
                        <h1 class="title center">Blog</h1>
                        <ul>
                            <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                            <li><span>Blog</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="tf-section pt-130 pb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeIn" data-wow-delay=".3s">
                <div class="subscribe style1 flex items-center radius-20 gradient-6">
                    <div class="icon">
                        <img class="ring" src="assets/images/icon/ring.png" alt="">
                    </div>
                    <div class="content">
                        <h4 class="mb-12 text-spacing-0_5">Đăng ký nhận <br class="show-desktop"> tin tức & khuyến mãi
                            từ chúng
                            tôi</h4>
                        <p class="white">Chúng tôi cam kết không gửi thư rác đến hộp thư của bạn.</p>
                    </div>
                    <div class="form">
                        <form action="#">
                            <input type="text" name="inp" placeholder="Email của bạn..." required="">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0"
                                    viewBox="0 0 24 24" style="enable-background: new 0 0 512 512" xml:space="preserve">
                                    <g>
                                        <path fill="#000000"
                                            d="M22.101 10.562 2.753 1.123A1.219 1.219 0 0 0 1 2.22v.035a2 2 0 0 0 .06.485l1.856 7.424a.5.5 0 0 0 .43.375l8.157.907a.559.559 0 0 1 0 1.11l-8.157.907a.5.5 0 0 0-.43.375L1.06 21.261a2 2 0 0 0-.06.485v.035a1.219 1.219 0 0 0 1.753 1.096L22.1 13.438a1.6 1.6 0 0 0 0-2.876z"
                                            data-original="#000000"></path>
                                    </g>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once(__DIR__ . '/../components/Blog/blog-item.php'); ?>

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
            testimonialLinks.forEach(function(link) {
                link.classList.remove("active");
            });

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

    let cart = document.querySelector(".cart");
    let icon_cart = cart.querySelector(".icon_cart");
    let close_cart = cart.querySelector(".close_cart");
    icon_cart.addEventListener("click", function() {
        cart.classList.toggle("toggle");
    });
    close_cart.addEventListener("click", function() {
        cart.classList.toggle("toggle");
    });
</script>

</body>

</html>