<?php
require_once __DIR__ . '/../../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../vendor/PHPMailer/src/Exception.php';

require_once __DIR__ . '/../Models/Carts/CartDatabaseStorage.php';
require_once __DIR__ . '/../Models/Auth/AuthModel.php';
require_once __DIR__ . '/../configs/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function login(): void
    {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception("Vui lòng nhập đầy đủ email và mật khẩu.");
            }

            $user = $this->authModel->checkLogin($email, $password);

            if (!$user) {
                throw new Exception("Email hoặc mật khẩu không chính xác.");
            }

            if (!$user['is_active']) {
                throw new Exception("Tài khoản của bạn chưa được kích hoạt.");
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $sessionCart = $_SESSION['cart'] ?? [];

            if (!empty($sessionCart)) {
                $cartDb = new CartDatabaseStorage((int)$user['id_accounts']);


                foreach ($sessionCart as $item) {
                    if (isset($item['id'], $item['price'], $item['quantity']))  {
                        $cartDb->addItem([
                            'id' => $item['id'],
                            'name' => $item['name'],       // optional
                            'price' => $item['price'],
                            'quantity' => $item['quantity']
                        ]);
                    }
                       
                }

                // Xóa session cart sau khi merge
                unset($_SESSION['cart']);
            }

            $_SESSION['user'] = [
                'id' => $user['id_accounts'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'token_expiry' => time() + TOKEN_LIFETIME_SECONDS
            ];

            $this->authModel->updateLastLogin($user['id_accounts']);

            $_SESSION['login_success'] = 'Đăng nhập thành công!';

            $redirectUrl = $user['role'] === 'admin'
                ?  BASE_URL . "admin"
                :  BASE_URL . "";
            header("Location: $redirectUrl");
            exit;

        } catch (Exception $e) {
            $_SESSION['login_error'] = $e->getMessage();
            header("Location: " . BASE_URL . "login");
            exit;
        }
    }




    public function register(): void
    {
        try {
            $fullName = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
                throw new Exception("Vui lòng nhập đầy đủ thông tin.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email không hợp lệ.");
            }

            if ($password !== $confirmPassword) {
                throw new Exception("Mật khẩu không khớp.");
            }

            if ($this->authModel->findUserByEmail($email)) {
                throw new Exception("Email đã tồn tại.");
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $token = bin2hex(random_bytes(16));

            $this->authModel->createUser([
                'username' => explode('@', $email)[0],
                'password' => $hashedPassword,
                'email' => $email,
                'full_name' => $fullName,
                'role' => 'user',
                'is_active' => 0,
                'verification_token' => $token,
                'token_expiry' => date('Y-m-d H:i:s', time() + 300),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->sendVerificationEmail($email, $fullName, $token);

            require_once __DIR__ . '/../Views/pages/check_email.php';
            exit;

        } catch (Exception $e) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['register_error'] = $e->getMessage();
            header("Location: " . BASE_URL . "register");
            exit;
        }
    }



    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();    
        }

        session_unset();
        session_destroy();

        // Đánh dấu logout thành công
        header("Location: " . BASE_URL . "?logout=success");
        exit;
    }



    private function sendVerificationEmail(string $email, string $name, string $token): void
    {

        require_once __DIR__ . '/../../app/configs/config.php'; 
        
        $verifyUrl = BASE_URL . "/verify?token=" . urlencode($token);

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);


        try {
                $mail->isSMTP();
                $mail->Host = MAIL_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = MAIL_USERNAME;
                $mail->Password = MAIL_PASSWORD;
                $mail->SMTPSecure = MAIL_ENCRYPTION;
                $mail->Port = MAIL_PORT;

                $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Xác thực tài khoản FastFood - Hoàn tất đăng ký của bạn';

                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #333;'>
                        <h2 style='color: #d0021b;'>Xin chào $name,</h2>
                        <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>FastFood Shop</strong>!</p>
                        <p>Để hoàn tất quá trình đăng ký và bắt đầu sử dụng dịch vụ, vui lòng xác thực tài khoản của bạn bằng cách nhấn vào nút bên dưới:</p>
                        <div style='margin: 20px 0; text-align: center;'>
                            <a href='$verifyUrl' style='background-color: #d0021b; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;'>
                                Xác thực tài khoản ngay
                            </a>
                        </div>
                        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
                        <hr style='margin: 20px 0;'>
                        <p style='font-size: 13px; color: #999;'>Email này được gửi từ hệ thống tự động của FastFood Shop. Vui lòng không trả lời email này.</p>
                    </div>
                ";

                $mail->AltBody = "Chào $name,\n\nVui lòng xác thực tài khoản tại FastFood Shop bằng cách truy cập: $verifyUrl\n\nNếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua.";

                $mail->send();
            } catch (Exception $e) {
                error_log("Lỗi gửi email: " . $mail->ErrorInfo);
            }

    }




    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? null;
        $message = '';

        if ($token) {
            $user = $this->authModel->findUserByToken($token);

            if ($user) {
                $now = new DateTime();
                $expiry = new DateTime($user['token_expiry']);

                if ($now > $expiry) {
                    require_once __DIR__ . '/../Views/pages/verify_expired.php';
                    exit;
                } else {
                    $this->authModel->activateUser($user['id_accounts']);
                    $message = '✅ Tài khoản đã được xác thực thành công! Bạn có thể <a href="' . BASE_URL . '/login">đăng nhập</a>.';
                }

            } else {
                $message = '❌ Token không hợp lệ hoặc tài khoản đã xác thực.';
            }

        } else {
            $message = '⚠️ Không có token được cung cấp.';
        }

        require_once __DIR__ . '/../Views/pages/verify_result.php';
    }





}