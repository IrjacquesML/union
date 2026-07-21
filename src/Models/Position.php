<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Position extends Model
{
    protected static string $table = 'positions';

    public static function active(): array
    {
        return static::fetchAll(
            'SELECT * FROM positions WHERE is_active = 1 ORDER BY sort_order, title'
        );
    }
}
