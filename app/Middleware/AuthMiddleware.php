<?php
class AuthMiddleware
{
    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            return false;
        }

        if (!isset($_SESSION['user']['token_expiry']) || time() > $_SESSION['user']['token_expiry']) {
            unset($_SESSION['user']);
            return false;
        }

        return true;
    }
}
