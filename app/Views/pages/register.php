<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký - Basilico Fastfood</title>
    <?php require_once __DIR__ . '/../../configs/config.php'; ?>
    <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        ?>

    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #ff5722, #ff9800);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .register-container {
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 450px;
    }

    h2 {
        text-align: center;
        color: #d32f2f;
        margin-bottom: 30px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 14px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 12px;
    }

    button {
        width: 100%;
        padding: 14px;
        background: #d32f2f;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background: #b71c1c;
    }

    .text-center {
        text-align: center;
        margin-top: 15px;
    }

    a {
        color: #d32f2f;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
    .error-popup {
        background-color: #f44336;
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        margin: 15px 0;
        position: relative;
        font-size: 14px;
        animation: fadeIn 0.3s ease-in-out;
    }

    .error-popup button {
        position: absolute;
        top: -5px;
        right: -204px;
        border: none;
        background: transparent;
        color: white;
        font-size: 22px;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    </style>
</head>

<body>
    <?php include_once(__DIR__ . '/../components/Login/form-register.php'); ?>
</body>

</html>