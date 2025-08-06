<div class="register-container">
    <h2>Đăng ký tài khoản</h2>
    <form id="registerForm" action="<?= BASE_URL ?>register" method="POST">
        <input type="text" name="fullname" placeholder="Họ và tên" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Mật khẩu" required />
        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required />
        <button type="submit">Đăng ký</button>
    </form>
    <div class="text-center">
        <p>Đã có tài khoản? <a href="<?= BASE_URL ?>login">Đăng nhập</a></p>
    </div>

    <!-- Popup lỗi JS -->
    <div id="errorPopup" class="error-popup" style="display: none;">
        <span id="errorMessage"></span>
        <button onclick="closePopup()">×</button>
    </div>

    <!-- Popup lỗi từ PHP (nếu có) -->
    <?php if (!empty($_SESSION['register_error'])): ?>
        <div id="registerErrorPopup" class="error-popup">
            <span><?= htmlspecialchars($_SESSION['register_error']) ?></span>
            <button onclick="closeRegisterPopup()">×</button>
        </div>
        <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

    

</div>

<script>
function closeRegisterPopup() {
    const popup = document.getElementById("registerErrorPopup");
    if (popup) popup.style.display = "none";
}

// ✅ Nếu popup tồn tại, tự động ẩn sau 5 giây
window.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById("registerErrorPopup");
    if (popup) {
        setTimeout(() => popup.style.display = "none", 5000);
    }
});
</script>


<script>

function closeRegisterPopup() {
    const popup = document.getElementById("registerErrorPopup");
    if (popup) popup.style.display = "none";
}

function closePopup() {
    document.getElementById("errorPopup").style.display = "none";
    document.getElementById("errorMessage").innerText = "";
}


function isValidFullname(name) {
    const words = name.trim().split(/\s+/);
    const hasTwoWords = words.length >= 2;
    const validChars = /^[A-Za-zÀ-Ỵà-ỵ\s]+$/.test(name);
    return hasTwoWords && validChars;
}

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPassword(pw) {
    const hasMinLength = pw.length >= 8;
    const hasUpper = /[A-Z]/.test(pw);
    const hasLower = /[a-z]/.test(pw);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(pw);
    return hasMinLength && hasUpper && hasLower && hasSpecial;
}

function showError(message) {
    const popup = document.getElementById("errorPopup");
    const msg = document.getElementById("errorMessage");
    msg.innerText = message;
    popup.style.display = "block";
}

document.getElementById("registerForm").addEventListener("submit", function (e) {
    const fullname = this.fullname.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;


    if (!isValidFullname(fullname)) {
        e.preventDefault();
        showError("Họ và tên phải có ít nhất 2 từ và không chứa số/ký tự đặc biệt.");
        return;
    }

    if (!isValidEmail(email)) {
        e.preventDefault();
        showError("Email không đúng định dạng (example@domain.com).");
        return;
    }

    if (!isValidPassword(password)) {
        e.preventDefault();
        showError("Mật khẩu phải ≥8 ký tự, có chữ hoa, chữ thường và ký tự đặc biệt.");
        return;
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        showError("Mật khẩu và Nhập lại mật khẩu không khớp.");
        return;
    }
});
</script>
