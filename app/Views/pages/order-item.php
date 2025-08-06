<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>

<?php
require_once __DIR__ . '/../../Models/Carts/CartApi.php';


$appliedPromotion = $_SESSION['applied_promotion'] ?? null;
$discountAmount = 0;

if ($appliedPromotion) {
    $discountAmount = $appliedPromotion['discount_amount'] ?? 0;
}
?>

<style>
.payment-method-card:hover {
    background-color: #f9f9f9;
    border-width: 2px;
}
</style>


<section class="order-section"
    style="padding: 40px 20px; max-width: 750px; margin: 100px auto; background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 12px; padding: 30px;">

    <h2 style="margin-bottom: 30px; font-weight: 600; color: red; text-align:center;">Giỏ hàng của bạn</h2>

    <?php if (!empty($cartItems)) : ?>
    <?php foreach ($cartItems as $item) : ?>
    <div class="d-flex justify-content-between align-items-center"
        style="padding: 15px 0; border-bottom: 1px solid #dee2e6;">

        <div class="d-flex align-items-center">
            <img src="<?= htmlspecialchars($item['image_url'] ?? 'default.png') ?>"
                alt="<?= htmlspecialchars($item['name']) ?>"
                style="width:60px; height:60px; object-fit:cover; border-radius:5px; margin-right:15px;">

            <div>
                <h6 style="margin: 0 0 5px 0; font-size: 16px; font-weight: 600; color:black;">
                    <?= htmlspecialchars($item['name']) ?>
                </h6>
                <p style="margin: 0; color: #6c757d; font-size: 14px;"><?= number_format($item['price']) ?>₫</p>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary btn-sm decrease-btn" data-id="<?= $item['id'] ?>"
                style="font-size: 14px; padding: 5px 10px;">-</button>

            <span style="margin: 0 10px; min-width: 20px; text-align: center;"><?= $item['quantity'] ?></span>

            <button class="btn btn-outline-secondary btn-sm increase-btn" data-id="<?= $item['id'] ?>"
                style="font-size: 14px; padding: 5px 10px;">+</button>

            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $item['id'] ?>"
                style="margin-left: 12px; padding: 5px 8px;">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else : ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>

    <hr style="margin: 40px 0;">

    <form method="post" action="<?= BASE_URL ?>order">

        <div class="row mb-3">
            <div class="col-md-6" style="margin-bottom: 15px;">
                <label style="font-weight: 500;">Email *</label>
                <input type="email" name="email" class="form-control" required
                    style="margin-top: 5px; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
            </div>

            <div class="col-md-6" style="margin-bottom: 15px;">
                <label style="font-weight: 500;">Phone *</label>
                <input type="tel" name="phone" class="form-control" required
                    style="margin-top: 5px; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
            </div>

            <div class="row mb-3">
                <div class="col-12" style="margin-bottom: 15px;">
                    <label style="font-weight: 500;">Địa chỉ nhận hàng *</label>
                    <input type="text" name="address" class="form-control" required
                        style="margin-top: 5px; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                </div>
            </div>
        </div>

        <!-- Ô chọn hoặc nhập ưu đãi -->
        <input type="hidden" name="promotion_code" id="voucher_code" value="">
        <div style="margin-bottom: 25px;">
            <label style="font-weight: 500;">Ưu đãi</label>
            <div class="voucher-select-box d-flex justify-content-between align-items-center" data-bs-toggle="modal"
                data-bs-target="#voucherModal"
                style="padding: 12px 15px; background-color: #f8f9fa; border-radius: 6px; border: 1px solid #ced4da; cursor: pointer;">

                <div class="d-flex align-items-center" style="color: #dc3545;">
                    <i class="fa fa-tags" style="margin-right: 8px;"></i>
                    <span>Chọn hoặc nhập ưu đãi</span>
                </div>
                <i class="fa fa-chevron-right"></i>
            </div>
        </div>

        <!-- Ô chọn phương thức thanh toán -->
        <div style="margin-bottom: 25px;">
            <label style="font-weight: 500;">Phương thức thanh toán *</label>
            <input type="hidden" name="payment_method" id="payment_method" value="banking">

            <div class="payment-method-select d-flex justify-content-between align-items-center" data-bs-toggle="modal"
                data-bs-target="#paymentMethodModal"
                style="padding: 12px 15px; background-color: #f8f9fa; border-radius: 6px; border: 1px solid #ced4da; cursor: pointer;">

                <div class="d-flex align-items-center" style="color: #black; font-weight: 600;">
                    <i class="fa fa-credit-card" style="margin-right: 8px;"></i>
                    <span id="selected-payment-method-text">Chuyển khoản ngân hàng</span>
                </div>
                <i class="fa fa-chevron-right"></i>
            </div>
        </div>

        <div class="text-end mb-3" style="font-size: 16px;">
            <?php
                $subtotal = $totals['total_price'] ?? 0;
                $discount = $totals['discount'] ?? 0; 
                $total    = $subtotal - $discount;
            ?>

            <p style="margin-bottom: 6px; color:black;">
                Tạm tính: <strong><?= number_format($subtotal) ?>₫</strong>
            </p>

            <p style="margin-bottom: 6px; color:black;">
                Giảm giá: <strong><?= number_format($discount) ?>₫</strong>
            </p>

            <p style="margin-top: 10px; color:black;">
                Tổng: <strong style="color: #dc3545;"><?= number_format($total > 0 ? $total : 0) ?>₫</strong>
            </p>
        </div>





        <div class="alert alert-info small"
            style="font-size: 14px; padding: 15px; border-radius: 6px; border-left: 5px solid #0dcaf0; background-color: #eaf7fb;">
            <strong>CHUYỂN KHOẢN QUA QR CODE</strong><br>
            => Nhập Email > Ấn <b>Đặt Hàng</b> > Thanh Toán > Nhận Hàng (đơn hàng sẽ gửi trực tiếp về email).
        </div>

        <div class="text-center" style="margin-top: 30px;">
            <button type="submit" class="btn btn-danger" style="padding: 10px 40px; font-size: 16px; font-weight: 600;">
                ĐẶT HÀNG
            </button>
        </div>
    </form>
</section>

<!-- Modal chọn phương thức thanh toán (style như modal ưu đãi) -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 overflow-hidden">
            <div class="modal-header" style="background: #fff; border-bottom: 1px solid #eee;">
                <h5 class="modal-title fw-bold text-danger">Phương thức thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body" style="background: #fff; padding: 16px 20px;">

                <!-- Option 1 -->
                <div class="border border-primary rounded-3 d-flex justify-content-between align-items-center p-3 mb-3 payment-method-card"
                    data-method="banking" style="cursor: pointer;">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">💳 Chuyển khoản ngân hàng</h6>
                        <small class="text-muted">Thanh toán qua mã QR hoặc chuyển khoản</small>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm apply-payment-btn">Áp dụng</button>
                </div>

                <!-- Option 2 -->
                <div class="border border-success rounded-3 d-flex justify-content-between align-items-center p-3 mb-3 payment-method-card"
                    data-method="cash" style="cursor: pointer;">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">💵 Thanh toán khi nhận hàng (COD)</h6>
                        <small class="text-muted">Trả tiền mặt khi nhận hàng</small>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm apply-payment-btn">Áp dụng</button>
                </div>

                <!-- Option 3 -->
                <div class="border border-info rounded-3 d-flex justify-content-between align-items-center p-3 payment-method-card"
                    data-method="visa" style="cursor: pointer;">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">💳 Thanh toán bằng Visa</h6>
                        <small class="text-muted">Dùng thẻ Visa hoặc MasterCard</small>
                    </div>
                    <button type="button" class="btn btn-outline-info btn-sm apply-payment-btn">Áp dụng</button>
                </div>

            </div>
        </div>
    </div>
</div>



<script>
document.querySelectorAll('.apply-payment-btn').forEach(button => {
    button.addEventListener('click', function() {
        const parent = this.closest('.payment-method-card');
        const method = parent.getAttribute('data-method');
        const label = parent.querySelector('h6').innerText.trim();

        // Gán vào hidden input và hiển thị lại ngoài trang
        document.getElementById('payment_method').value = method;
        document.getElementById('selected-payment-method-text').textContent = label;

        // Đóng modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentMethodModal'));
        modal.hide();
    });
});
</script>


<!-- Modal chọn ưu đãi -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"
            style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">

            <!-- Header -->
            <div class="modal-header" style="background-color: #f8f9fa;">
                <h5 class="modal-title" id="voucherModalLabel" style="font-weight: 600; color: red;">
                    Chọn mã giảm giá
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 20px;">

                <!-- Nhập mã thủ công -->
                <form method="get" action="<?= BASE_URL ?>apply-promotion" class="input-group mb-4">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã giảm giá"
                        style="border-radius: 6px 0 0 6px;" required>
                    <input type="hidden" name="order_id" value="<?= $orderId ?>">
                    <button type="submit" class="btn btn-outline-primary"
                        style="border-radius: 0 6px 6px 0; font-weight: 500;">Áp dụng</button>
                </form>

                <!-- Danh sách mã có sẵn -->
                <div class="voucher-list">
                    <?php if (!empty($validPromotions)) : ?>
                    <?php foreach ($validPromotions as $promo): ?>
                    <div class="voucher-item d-flex justify-content-between align-items-center mb-3" style="background-color: #fff; border: 1px solid #dee2e6; 
                                        border-left: 5px solid #ff4d4f; padding: 16px; border-radius: 8px;">
                        <div>
                            <div style="font-weight: bold; text-transform: uppercase;">
                                <?= htmlspecialchars($promo['code']) ?>
                            </div>
                            <div style="color: #6c757d; font-size: 0.875rem;">
                                <?= $promo['discount_type'] === 'amount' 
                                            ? 'Giảm trực tiếp ' . number_format($promo['value']) . '₫'
                                            : 'Giảm ' . $promo['value'] . '%' ?>
                            </div>
                            <div style="color: #0d6efd; font-size: 0.875rem;">
                                Hạn sử dụng: <?= date('d-m-Y', strtotime($promo['end_date'])) ?>
                            </div>
                        </div>

                        <!-- Button áp dụng -->
                        <a href="<?= BASE_URL ?>apply-promotion?code=<?= $promo['code'] ?>&order_id=<?= $orderId ?>"
                            class="btn btn-sm btn-outline-success">
                            Áp dụng
                        </a>

                    </div>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <p>Hiện không có mã giảm giá nào khả dụng.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" style="font-weight: 600;">
                    Đóng
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelectorAll('.voucher-item .btn-outline-success').forEach(button => {
    button.addEventListener('click', function() {
        const voucherItem = this.closest('.voucher-item');
        const code = voucherItem.querySelector('div div').innerText.trim();

        // Gửi tới PromotionController
        window.location.href = '<?= BASE_URL ?>apply-promotion?code=' + encodeURIComponent(code);
    });
});
</script>


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