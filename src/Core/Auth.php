<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    private const USER_KEY = 'auth_user_id';

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if (!$user || !(bool) $user['is_active']) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        Session::regenerate();
        Session::set(self::USER_KEY, (int) $user['id']);
        User::touchLogin((int) $user['id']);

        return true;
    }

    public static function check(): bool
    {
        return Session::has(self::USER_KEY);
    }

    public static function id(): ?int
    {
        $id = Session::get(self::USER_KEY);
        return $id !== null ? (int) $id : null;
    }

    public static function user(): ?array
    {
        $id = self::id();
        return $id ? User::find($id) : null;
    }

    public static function logout(): void
    {
        Session::remove(self::USER_KEY);
        Session::regenerate();
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/admin/login');
        }
    }
}
