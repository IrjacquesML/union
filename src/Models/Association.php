<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Association extends Model
{
    protected static string $table = 'associations';

    public static function active(): array
    {
        return static::fetchAll(
            'SELECT a.*, t.label AS type_label, d.name AS department_name
             FROM associations a
             JOIN association_types t ON t.id = a.association_type_id
             LEFT JOIN departments d ON d.id = a.department_id
             WHERE a.is_active = 1
             ORDER BY t.sort_order, a.sort_order, a.name'
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne(
            'SELECT a.*, t.label AS type_label
             FROM associations a
             JOIN association_types t ON t.id = a.association_type_id
             WHERE a.slug = :slug LIMIT 1',
            ['slug' => $slug]
        );
    }

    public static function allWithMeta(): array
    {
        return static::fetchAll(
            'SELECT a.*, t.label AS type_label, d.name AS department_name
             FROM associations a
             JOIN association_types t ON t.id = a.association_type_id
             LEFT JOIN departments d ON d.id = a.department_id
             ORDER BY a.name'
        );
    }
}
