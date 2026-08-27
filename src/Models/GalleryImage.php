<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class GalleryImage extends Model
{
    protected static string $table = 'gallery_images';

    public static function forGallery(int $galleryId): array
    {
        return static::fetchAll(
            'SELECT * FROM gallery_images WHERE gallery_id = :id ORDER BY sort_order ASC, id ASC',
            ['id' => $galleryId]
        );
    }
}
