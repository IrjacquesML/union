<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Department extends Model
{
    protected static string $table = 'departments';

    public static function active(): array
    {
        return static::fetchAll(
            'SELECT * FROM departments WHERE is_active = 1 ORDER BY sort_order, name'
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne('SELECT * FROM departments WHERE slug = :slug LIMIT 1', ['slug' => $slug]);
    }
}
