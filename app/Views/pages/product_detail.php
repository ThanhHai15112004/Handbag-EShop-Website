<?php
require_once __DIR__ . '/../../Controllers/ProductController.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$productController = new ProductController();
$product = $productController->showProductDetail($productId);
$images = $productController->getProductImages($productId);
$subImages = $productController->getSubImages($product->productId);
$relatedProducts = $productController->showProductsByCategory($product->categoryId);
$relatedProducts = array_filter($relatedProducts, fn($p) => $p->productId !== $product->productId);

if (count($relatedProducts) === 0) {
    $relatedProducts = $productController->showProductList(); // fallback
}


?>

<?php include_once(__DIR__ . '/../includes/header.php'); ?>


<?php include_once(__DIR__ . '/../components/Product_Detail/order_online_banner.php'); ?>

<?php include_once(__DIR__ . '/../components/Product_Detail/product_detail_item.php'); ?>

<?php include_once(__DIR__ . '/../components/Product_Detail/additionalproduct.php'); ?>

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
<script src="app/js/jquery.magnific-popup.min.js"></script>
<script src="app/js/gallery.js"></script>
<script>
var image = document.getElementsByClassName("parallax");
new simpleParallax(image);

var image = document.getElementsByClassName("thumbnail");
new simpleParallax(image);
</script>
<script>
document.querySelectorAll(".menu-tab li").forEach(function (thumbnail) {
    thumbnail.addEventListener("click", function () {
        const newSrc = this.getAttribute("data-src");
        const mainImage = document.querySelector(".content-inner.active .image img");

        // Cập nhật ảnh chính
        if (mainImage && newSrc) {
            mainImage.src = newSrc;
        }

        // Bỏ class active cũ và thêm class active mới
        document.querySelectorAll(".menu-tab li").forEach(function (li) {
            li.classList.remove("active");
        });
        this.classList.add("active");
    });
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