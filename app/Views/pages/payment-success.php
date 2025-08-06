<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>

<?php require_once __DIR__ . '/../../Models/Carts/CartApi.php';?>



<style>
    .success-container {
        max-width: 600px;
        margin: 150px auto;
        padding: 40px;
        background: #ffffff;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    .success-container h2 {
        color: #28a745;
        margin-bottom: 20px;
    }

    .success-container p {
        font-size: 16px;
        margin-bottom: 30px;
    }

    .success-container .btn {
        display: inline-block;
        margin: 10px;
        padding: 12px 20px;
        font-size: 15px;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-home {
        background-color: #007bff;
    }

    .btn-home:hover {
        background-color: #0056b3;
    }

    .btn-order {
        background-color: #28a745;
    }

    .btn-order:hover {
        background-color: #1e7e34;
    }
</style>

<div class="success-container">
    <h2>🎉 Thanh toán thành công!</h2>
    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được xử lý.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-home">🏠 Về trang chủ</a>
    <a href="<?= BASE_URL ?>menu-restaurant" class="btn btn-order">🍔 Đặt món tiếp</a>
</div>




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
</script>
</body>

</html>