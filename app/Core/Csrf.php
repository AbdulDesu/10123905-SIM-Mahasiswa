<?php
declare(strict_types=1);

namespace App\Core;

/** Token CSRF: memastikan POST berasal dari form milik aplikasi ini. */
final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function verify($token): bool
    {
        return is_string($token) && hash_equals(self::token(), $token);
    }
}
