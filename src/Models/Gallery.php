<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Gallery extends Model
{
    protected static string $table = 'galleries';

    public static function published(): array
    {
        return static::fetchAll(
            'SELECT * FROM galleries WHERE is_published = 1 ORDER BY event_date DESC, id DESC'
        );
    }
}
