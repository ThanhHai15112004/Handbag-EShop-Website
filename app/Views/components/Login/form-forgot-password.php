<div class="container">
        <h2>Quên mật khẩu</h2>
        <form action="forgot_process.php" method="POST">
            <input type="email" name="email" placeholder="Nhập email của bạn" required />
            <button type="submit">Gửi yêu cầu</button>
        </form>
        <div class="text-center">
            <p><a href="<?= BASE_URL ?>login">Quay lại đăng nhập</a></p>
        </div>
    </div>