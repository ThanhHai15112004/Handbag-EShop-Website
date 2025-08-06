<?php
require_once __DIR__ . '/../../../Models/Carts/CartApi.php';

require_once __DIR__ . '/../../../helpers/auth_helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartApi = new CartApi();
$cartItems = $cartApi->getCart();
$totals = $cartApi->getTotals();
?>

<div class="mini-cart-overlay"
    style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9998;">
</div>

<div class="mini-cart-popup"
    style="display:none; position:fixed; top:0; right:0; width:350px; height:100vh; background:#fff; box-shadow:-2px 0 10px rgba(0,0,0,0.1); z-index:9999;">
    <div class="mini-cart-header" style="padding:15px; border-bottom:1px solid #eee; background:#f8f8f8;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h5 style="margin:0; color:#d0021b; font-weight:bold;">Giỏ hàng</h5>
            <button class="close-mini-cart"
                style="background:none; border:none; font-size:20px; cursor:pointer;">×</button>
        </div>
    </div>

    <div class="mini-cart-body" style="padding:15px; height:calc(100vh - 200px); overflow-y:auto;">
        <?php if (empty($cartItems)): ?>
        <div class="mini-cart-empty" style="text-align:center; color:#888; padding:40px 0;">
            Giỏ hàng của bạn đang trống!
        </div>
        <?php else: ?>
        <div class="mini-cart-list">
            <?php foreach ($cartItems as $item): ?>
            <div
                style="display:flex; align-items:center; padding:10px; border:1px solid #eee; border-radius:8px; margin-bottom:10px; position:relative;">
                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                    style="width:50px; height:50px; border-radius:5px; object-fit:cover; margin-right:10px;">

                <div style="flex:1;">
                    <div style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></div>
                    <div style="color:#d0021b; font-weight:bold;">
                        <?= number_format($item['price']) ?>đ
                    </div>

                    <div class="quantity-control" style="margin-top:5px;">
                        <button class="btn btn-sm btn-light decrease-qty" data-id="<?= $item['id'] ?>"
                            style="padding:3px 8px;">−</button>
                        <span class="mx-2"><?= $item['quantity'] ?></span>
                        <button class="btn btn-sm btn-light increase-qty" data-id="<?= $item['id'] ?>"
                            style="padding:3px 8px;">+</button>
                    </div>
                </div>

                <!-- ✅ Nút xoá -->
                <button class="btn btn-sm btn-danger remove-from-cart" data-id="<?= $item['id'] ?>"
                    style="position:absolute; top:40px; right:20px; padding:2px 6px; font-size:14px;">
                    🗑
                </button>
            </div>

            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>

    <div class="mini-cart-footer" style="padding:15px; border-top:1px solid #eee; background:#f8f8f8;">
        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
            <span style="font-weight:500;">Tổng cộng:</span>
            <span class="mini-cart-total" style="color:#d0021b; font-weight:bold;">
                <?= number_format($totals['total_price']) ?>đ
            </span>
        </div>

        <a href="<?= BASE_URL ?>order" class="btn btn-danger w-100">Thanh toán</a>




        <a href="<?= BASE_URL ?>menu-restaurant" class="btn btn-outline-secondary w-100"
            style="padding:10px; border:1px solid #ddd; border-radius:5px; background:white; color:#666;">
            Tiếp tục mua hàng
        </a>
    </div>
</div>

<div class="modal fade" id="loginRequiredModal" style="z-index:999999" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Yêu cầu đăng nhập</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Đóng"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Bạn cần có tài khoản để thực hiện thanh toán.</p>
                <a href="<?= BASE_URL ?>login" class="btn btn-danger w-100">Đăng nhập ngay</a>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const openCartBtn = document.querySelector('.open-mini-cart');
    const closeCartBtn = document.querySelector('.close-mini-cart');
    const overlay = document.querySelector('.mini-cart-overlay');
    const popup = document.querySelector('.mini-cart-popup');

    if (openCartBtn && overlay && popup) {
        openCartBtn.addEventListener('click', () => {
            overlay.style.display = 'block';
            popup.style.display = 'block';
        });

        overlay.addEventListener('click', () => {
            overlay.style.display = 'none';
            popup.style.display = 'none';
        });

        closeCartBtn?.addEventListener('click', () => {
            overlay.style.display = 'none';
            popup.style.display = 'none';
        });
    }
});
</script>

<script>
const isLoggedIn = <?= isLoggedInWithValidToken() ? 'true' : 'false' ?>;

document.getElementById('checkout-button').addEventListener('click', function(e) {
    e.preventDefault();

    if (isLoggedIn) {
        window.location.href = '<?= BASE_URL ?>order';
    } else {
        const loginModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
        loginModal.show();
    }
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            window.location.href = `<?= BASE_URL ?>cart-increase?id=${id}`;
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            window.location.href = `<?= BASE_URL ?>cart-decrease?id=${id}`;
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = btn.dataset.id;
            window.location.href = `<?= BASE_URL ?>cart-remove?id=${id}`;
        });
    });
});
</script>