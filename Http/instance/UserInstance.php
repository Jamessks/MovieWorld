<?php

namespace Http\instance;

class UserInstance
{
    public static function loggedIn()
    {
        return $_SESSION['user'] ?? 0;
    }

    public static function id()
    {
        if (!$_SESSION) {
            return false;
        }
        return $_SESSION['user']['id'] ?? null;
    }

    public static function username()
    {
        if (!$_SESSION) {
            return false;
        }
        return $_SESSION['user']['username'] ?? null;
    }
}
