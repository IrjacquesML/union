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
            'SELECT g.*,
                    (SELECT COUNT(*) FROM gallery_images gi WHERE gi.gallery_id = g.id) AS image_count
             FROM galleries g
             WHERE g.is_published = 1
             ORDER BY g.event_date DESC, g.id DESC'
        );
    }

    public static function allWithCount(): array
    {
        return static::fetchAll(
            'SELECT g.*,
                    (SELECT COUNT(*) FROM gallery_images gi WHERE gi.gallery_id = g.id) AS image_count
             FROM galleries g
             ORDER BY g.event_date DESC, g.id DESC'
        );
    }

    public static function withImages(int $id): ?array
    {
        $gallery = static::find($id);
        if (!$gallery) {
            return null;
        }
        $gallery['images'] = GalleryImage::forGallery($id);
        return $gallery;
    }

    public static function findPublishedBySlug(string $slug): ?array
    {
        $gallery = static::fetchOne(
            'SELECT * FROM galleries WHERE slug = :slug AND is_published = 1 LIMIT 1',
            ['slug' => $slug]
        );
        if (!$gallery) {
            return null;
        }
        $gallery['images'] = GalleryImage::forGallery((int) $gallery['id']);
        return $gallery;
    }
}
