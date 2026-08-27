<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Belief extends Model
{
    protected static string $table = 'beliefs';

    public static function published(): array
    {
        return static::fetchAll(
            'SELECT * FROM beliefs WHERE is_published = 1 ORDER BY sort_order, number, title'
        );
    }
}
