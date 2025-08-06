<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4CAF50, #81C784);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            text-align: center;
        }

        .message-box {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
        }

        a {
            color: #ffeb3b;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2><?= $message ?></h2>
    </div>
</body>
</html>
