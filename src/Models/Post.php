<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Post extends Model
{
    protected static string $table = 'posts';

    public static function published(int $limit = 20): array
    {
        return static::fetchAll(
            'SELECT p.*, d.name AS department_name
             FROM posts p
             LEFT JOIN departments d ON d.id = p.department_id
             WHERE p.status = \'published\'
             ORDER BY p.published_at DESC
             LIMIT ' . (int) $limit
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne(
            'SELECT p.*, d.name AS department_name FROM posts p
             LEFT JOIN departments d ON d.id = p.department_id
             WHERE p.slug = :slug LIMIT 1',
            ['slug' => $slug]
        );
    }

    public static function allWithMeta(): array
    {
        return static::fetchAll(
            'SELECT p.*, d.name AS department_name FROM posts p
             LEFT JOIN departments d ON d.id = p.department_id
             ORDER BY p.created_at DESC'
        );
    }
}
