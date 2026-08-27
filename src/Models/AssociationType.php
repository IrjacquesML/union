<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class AssociationType extends Model
{
    protected static string $table = 'association_types';

    public static function ordered(): array
    {
        return static::all('sort_order ASC, label ASC');
    }
}
