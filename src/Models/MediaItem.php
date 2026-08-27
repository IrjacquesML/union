<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class MediaItem extends Model
{
    protected static string $table = 'media_items';

    public static function published(?string $type = null): array
    {
        $sql = 'SELECT m.*, c.name AS category_name
                FROM media_items m
                LEFT JOIN media_categories c ON c.id = m.category_id
                WHERE m.is_published = 1';
        $params = [];
        if ($type) {
            $sql .= ' AND m.type = :type';
            $params['type'] = $type;
        }
        $sql .= ' ORDER BY m.published_at DESC, m.id DESC';
        return static::fetchAll($sql, $params);
    }

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne(
            'SELECT m.*, c.name AS category_name FROM media_items m
             LEFT JOIN media_categories c ON c.id = m.category_id
             WHERE m.slug = :slug LIMIT 1',
            ['slug' => $slug]
        );
    }

    public static function allWithMeta(): array
    {
        return static::fetchAll(
            'SELECT m.*, c.name AS category_name FROM media_items m
             LEFT JOIN media_categories c ON c.id = m.category_id
             ORDER BY m.created_at DESC'
        );
    }
}
