<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string
    {
        if (!Session::has(self::TOKEN_KEY)) {
            Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        }

        return (string) Session::get(self::TOKEN_KEY);
    }

    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="_csrf" value="' . e($token) . '">';
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = Session::get(self::TOKEN_KEY);
        if (!$token || !$sessionToken) {
            return false;
        }

        return hash_equals((string) $sessionToken, $token);
    }
}
