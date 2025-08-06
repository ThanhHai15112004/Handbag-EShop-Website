<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<?php include_once(__DIR__ . '/../components/Cart/cart.php'); ?>

<?php require_once __DIR__ . '/../../Models/Carts/CartApi.php';?>


<style>
    body {
        background-color: #f7f9fa;
    }

    .payment-container {
        max-width: 960px;
        margin: 100px auto;
        font-family: Arial, sans-serif;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .payment-header {
        padding: 20px 30px;
        border-bottom: 1px solid #eee;
    }

    .payment-header h2 {
        color: #28a745;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
    }

    .payment-info {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 30px;
        gap: 20px;
    }

    .info-left, .info-right {
        flex: 1;
        min-width: 320px;
    }

    .info-left p, .info-right p {
        font-size: 15px;
        margin-bottom: 6px;
    }

    .info-left span, .info-right span {
        font-weight: bold;
    }

    .qr-box {
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 20px;
        background-color: #fff;
        text-align: center;
    }

    .qr-box img {
        width: 260px;
        border: 2px solid #ddd;
        padding: 6px;
        border-radius: 8px;
        margin: 10px 0;
    }

    .qr-download {
        margin-top: 10px;
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 8px 14px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }

    .qr-download:hover {
        background-color: #0056b3;
    }

    .bank-info {
        padding: 20px;
        background: #fff5f5;
        border: 1px solid #f3dada;
        border-radius: 10px;
    }

    .bank-info h4 {
        color: #cc0000;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .bank-info .highlight {
        font-weight: bold;
        color: red;
    }

    .status {
        text-align: center;
        padding-top: 10px;
        color: red;
        font-size: 15px;
    }

    .product-list {
        padding: 20px 30px;
        border-top: 1px solid #eee;
    }

    .product-list table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    .product-list th,
    .product-list td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .product-list th {
        text-align: left;
        background: #f5f5f5;
    }

    .product-list td:last-child,
    .product-list th:last-child {
        text-align: right;
    }

    .spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid #ccc;
        border-top: 2px solid red;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-right: 6px;
        vertical-align: middle;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

</style>

<section class="payment-container">
    <div class="payment-header">
        <h2>✔ Cảm ơn bạn. Đơn hàng của bạn đã được nhận.</h2>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div><strong>MÃ ĐƠN HÀNG:</strong> <?= $order['id_orders'] ?></div>
            <div><strong>NGÀY:</strong> <?= date('d/m/Y', strtotime($invoice['issued_at'])) ?></div>
        </div>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div><strong>TỔNG CỘNG:</strong> <span style="color:red"><?= number_format($amount) ?>₫</span></div>
            <div><strong>EMAIL:</strong> <?= $_SESSION['user']['email'] ?? '' ?></div>
        </div>
        <div><strong>PHƯƠNG THỨC THANH TOÁN:</strong> Chuyển khoản qua QR Code</div>
    </div>

    <div class="payment-info">
        <!-- QR CODE + HƯỚNG DẪN -->
        <div class="qr-box info-left">
            <p><strong>Bước 1:</strong> Mở Ví điện tử/Ngân hàng</p>
            <p><strong>Bước 2:</strong> Chọn <span>📷</span> và quét mã</p>
            <img src="<?= $vietQrUrl ?>" alt="QR thanh toán">
            <p><strong>Bước 3:</strong> Xác Nhận Chuyển Khoản</p>
            <a href="<?= $vietQrUrl ?>" download class="qr-download">⬇ Tải xuống Qrcode</a>
        </div>

        <!-- THÔNG TIN NGÂN HÀNG -->
        <div class="bank-info info-right">
            <p style="font-size: 13px; margin-bottom: 6px; color: #555;">
                Hỗ trợ Ví điện tử MoMo/ZaloPay<br>Hoặc ứng dụng ngân hàng để chuyển khoản nhanh 24/7
            </p>
            <h4>THÔNG TIN CHUYỂN KHOẢN</h4>
            <p>Ngân hàng: <strong>TCB</strong> - Ngân hàng Kỹ Thương Việt Nam Techcombank - TCB</p>
            <p>Số tài khoản: <strong><?= $accountNo ?></strong></p>
            <p>Chủ tài khoản: <strong><?= $accountName ?></strong></p>
            <p>Số tiền: <span class="highlight"><?= number_format($amount) ?>₫</span></p>
            <p>Nội dung thanh toán: <span class="highlight"><?= $description ?></span></p>
            <p style="background:#eee; padding: 8px; border-radius: 6px; font-size: 13px;">
                Đơn hàng sẽ tự động xử lý sau khi thanh toán hoàn tất.
            </p>
            <div class="status">
                <span class="spinner"></span> Đang chờ xác nhận giao dịch
            </div>

        </div>
    </div>

    <!-- DANH SÁCH SẢN PHẨM -->
    <div class="product-list">
        <h3>SẢN PHẨM</h3>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>TỔNG</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] ?? [] as $item): ?>
                    <tr>
                        <td><?= $item['product_name'] ?> × <?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity']) ?>₫</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Tổng số phụ:</strong></td>
                    <td><strong><?= number_format($amount) ?>₫</strong></td>
                </tr>
                <tr>
                    <td><strong>Tổng cộng:</strong></td>
                    <td><strong style="color:red"><?= number_format($amount) ?>₫</strong></td>
                </tr>
                <tr>
                    <td>Phương thức thanh toán:</td>
                    <td>Chuyển khoản qua QR Code</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- ******************************* -->
<!-- test -->
<div style="text-align: center; margin: 30px;">
    <a href="<?= BASE_URL ?>mock-payment-success?invoice_id=<?= $invoice['id_invoice'] ?>"
       class="btn btn-success"
       style="display:inline-block; padding:12px 20px; background:#28a745; color:#fff;
              border-radius:6px; text-decoration:none; font-size:16px;">
        ✅ Tôi đã thanh toán
    </a>
</div>
<!-- ******************************* -->



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