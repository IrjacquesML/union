<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CarouselSlide extends Model
{
    protected static string $table = 'carousel_slides';

    public static function active(): array
    {
        return static::fetchAll(
            'SELECT * FROM carousel_slides
             WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function allOrdered(): array
    {
        return static::all('sort_order ASC, id ASC');
    }
}
