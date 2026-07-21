<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ContactMessage extends Model
{
    protected static string $table = 'contact_messages';

    public static function unreadCount(): int
    {
        return static::count('is_read = 0');
    }

    public static function latest(): array
    {
        return static::all('created_at DESC');
    }
}
