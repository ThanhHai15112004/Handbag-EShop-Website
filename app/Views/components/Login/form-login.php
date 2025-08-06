<div class="login-container">
    <h2>Đăng nhập</h2>
    <form id="loginForm" action="<?= BASE_URL ?>login" method="POST">
        <input type="email" id="emailInput" name="email" placeholder="Email" required />
        <input type="password" id="passwordInput" name="password" placeholder="Mật khẩu" required />
        <div class="forgot">
            <a href="<?= BASE_URL ?>forgot-password">Quên mật khẩu?</a>
        </div>
        <button type="submit">Đăng nhập</button>
    </form>

    <div id="errorPopup" class="popup-error" style="display: none;">
        <span id="popupMessage"></span>
        <span class="close-btn" onclick="closePopup()">&times;</span>
    </div>

    <?php if (!empty($_SESSION['login_error'])): ?>
        <div class="error-popup" id="loginErrorPopup">
            <span><?= htmlspecialchars($_SESSION['login_error']) ?></span>
            <button onclick="closeLoginPopup()">×</button>
        </div>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>


    <div class="social-btn">
        <a class="google" href="https://www.google.com">Google</a>
        <a class="facebook" href="https://www.facebook.com">Facebook</a>
    </div>
    <div class="text-center">
        <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>register">Đăng ký ngay</a></p>
    </div>

</div>




<script>
function closePopup() {
    const popup = document.getElementById('errorPopup');
    if (popup) popup.style.display = 'none';
}

function closeLoginPopup() {
    const popup = document.getElementById('loginErrorPopup') || document.getElementById('loginErrorToast');
    if (popup) popup.style.display = 'none';
}

window.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById('errorPopup') ||
                  document.getElementById('loginErrorPopup') ||
                  document.getElementById('loginErrorToast');
    if (popup) {
        setTimeout(() => {
            popup.style.display = 'none';
        }, 5000);
    }
});

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPassword(password) {
    const hasMinLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    return hasMinLength && hasUpper && hasLower && hasSpecial;
}

function showPopup(message) {
    const popup = document.getElementById('errorPopup');
    const popupMessage = document.getElementById('popupMessage');
    if (popup && popupMessage) {
        popupMessage.textContent = message;
        popup.style.display = 'block';

        setTimeout(() => {
            popup.style.display = 'none';
        }, 5000);
    }
}

document.getElementById('loginForm').addEventListener('submit', function (e) {
    const email = document.getElementById('emailInput').value.trim();
    const password = document.getElementById('passwordInput').value;

    if (!isValidEmail(email)) {
        e.preventDefault();
        showPopup('Email không đúng định dạng (example@domain.com)');
        return;
    }

    if (!isValidPassword(password)) {
        e.preventDefault();
        showPopup('Mật khẩu phải ≥8 ký tự, có chữ hoa, chữ thường và ký tự đặc biệt');
        return;
    }
});
</script>
