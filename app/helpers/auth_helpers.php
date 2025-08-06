<?php
// app/helpers/auth_helpers.php

function isLoggedInWithValidToken(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $expiry = $_SESSION['user']['token_expiry'] ?? 0;

    return time() < $expiry;
}
