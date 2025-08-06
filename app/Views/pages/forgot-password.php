<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quên mật khẩu - Basilico</title>
    <?php require_once __DIR__ . '/../../configs/config.php'; ?>
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

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #d32f2f;
            margin-bottom: 30px;
        }

        input[type="email"] {
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
    </style>
</head>

<body>
    <?php include_once(__DIR__ . '/../components/Login/form-forgot-password.php'); ?>
</body>

</html>