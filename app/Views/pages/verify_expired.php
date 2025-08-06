<!-- /Views/pages/verify_expired.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Xác thực thất bại</title>
    <style>
        body { font-family: Arial; padding: 40px; text-align: center; }
        .expired-box {
            border: 1px solid #e74c3c;
            padding: 30px;
            border-radius: 8px;
            background-color: #fce4e4;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="expired-box">
        <h2>⏰ Token đã hết hạn</h2>
        <p>Tài khoản của bạn chưa được xác thực trong thời gian quy định.</p>
        <p>Vui lòng đăng ký lại để sử dụng dịch vụ.</p>
        <a href="/public/register">Đăng ký lại</a>
    </div>
</body>
</html>
