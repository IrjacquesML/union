<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): ?array
    {
        return static::fetchOne(
            'SELECT u.*, r.code AS role_code, r.label AS role_label
             FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE u.email = :email LIMIT 1',
            ['email' => $email]
        );
    }

    public static function find(int $id): ?array
    {
        return static::fetchOne(
            'SELECT u.*, r.code AS role_code, r.label AS role_label
             FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE u.id = :id LIMIT 1',
            ['id' => $id]
        );
    }

    public static function touchLogin(int $id): void
    {
        static::update($id, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    public static function allWithRoles(): array
    {
        return static::fetchAll(
            'SELECT u.*, r.label AS role_label FROM users u
             JOIN roles r ON r.id = u.role_id ORDER BY u.last_name'
        );
    }
}
