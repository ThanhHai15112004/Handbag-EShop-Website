<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập - Basilico Fastfood</title>
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

    .login-container {
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

    .social-btn {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .social-btn a {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .google {
        background: #db4437;
    }

    .facebook {
        background: #3b5998;
    }

    .forgot {
        text-align: right;
        margin: 16px 0;
    }

    .forgot a,
    .text-center a {
        color: #d32f2f;
        text-decoration: none;
    }

    .forgot a:hover,
    .text-center a:hover {
        text-decoration: underline;
    }

    .text-center {
        text-align: center;
        margin-top: 15px;
    }

    .popup-error {
        position: relative;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #f44336;
        color: white;
        border-radius: 4px;
        font-size: 14px;
        animation: fadein 0.5s;
    }

    .popup-error .close-btn {
        position: absolute;
        top: 16px;
        right: 10px;
        border: none;
        background: transparent;
        color: white;
        font-size: 25px;
        cursor: pointer;
    }

    @keyframes fadein {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .error-popup {
        background-color: #f44336;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        margin: 15px auto 20px auto;
        position: relative;
        font-size: 14px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        animation: fadeIn 0.3s ease-in-out;
        text-align: center;
    }

    .error-popup span {
        display: block;
        padding-right: 30px;
    }

    .error-popup button {
        position: absolute;
        top: -4px;
        right: -178px;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 23px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }

    .error-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #f44336;
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        z-index: 9999;
        animation: slideDown 0.5s ease;
    }



    </style>
</head>



<body>
    <?php include_once(__DIR__ . '/../components/Login/form-login.php'); ?>
    
    
</body>

</html>