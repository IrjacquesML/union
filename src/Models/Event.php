<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Event extends Model
{
    protected static string $table = 'events';

    public static function upcoming(int $limit = 10): array
    {
        return static::fetchAll(
            'SELECT * FROM events
             WHERE is_published = 1 AND starts_at >= NOW()
             ORDER BY starts_at ASC
             LIMIT ' . (int) $limit
        );
    }

    public static function published(): array
    {
        return static::fetchAll(
            'SELECT e.*, d.name AS department_name
             FROM events e
             LEFT JOIN departments d ON d.id = e.department_id
             WHERE e.is_published = 1
             ORDER BY e.starts_at ASC'
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne('SELECT * FROM events WHERE slug = :slug LIMIT 1', ['slug' => $slug]);
    }
}
