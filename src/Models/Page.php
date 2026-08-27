<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Page extends Model
{
    protected static string $table = 'pages';

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne(
            'SELECT * FROM pages WHERE slug = :slug AND is_published = 1 LIMIT 1',
            ['slug' => $slug]
        );
    }

    public static function published(): array
    {
        return static::fetchAll(
            'SELECT * FROM pages WHERE is_published = 1 ORDER BY sort_order, title'
        );
    }
}
