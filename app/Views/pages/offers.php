<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php include_once(__DIR__ . '/../components/Offers/banner-special-offer.php'); ?>
<?php include_once(__DIR__ . '/../components/Offers/slide-special-offer.php'); ?>
<?php include_once(__DIR__ . '/../components/Offers/slider-offer.php'); ?>
<?php include_once(__DIR__ . '/../components/Offers/online-offer-item.php'); ?>
<?php include_once(__DIR__ . '/../components/Offers/offline-offer-item.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>
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
<script>
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