<?php

namespace Core\Csrf;

class CsrfToken
{
    public static function generateToken($formId)
    {

        $token = bin2hex(random_bytes(32));

        $_SESSION['csrf_tokens'][$formId] = $token;

        return $token;
    }

    public static function validateToken($formId, $token)
    {
        if (isset($_SESSION['csrf_tokens'][$formId]) && $_SESSION['csrf_tokens'][$formId] == $token) {
            unset($_SESSION['csrf_tokens'][$formId]);
            return true;
        }

        return false;
    }

    public static function validateApiToken($formId, $token)
    {
        return isset($_SESSION['csrf_tokens'][$formId]) && $_SESSION['csrf_tokens'][$formId] === $token;
    }
}
