<?php

namespace App\Security;

class Csrf {
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a random token
        $token = bin2hex(random_bytes(32));

        // Store the token in the session
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    public static function validateToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the token exists and matches the one in the session
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
