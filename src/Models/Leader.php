<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Leader extends Model
{
    protected static string $table = 'leaders';

    public static function findBySlug(string $slug): ?array
    {
        return static::fetchOne('SELECT * FROM leaders WHERE slug = :slug LIMIT 1', ['slug' => $slug]);
    }

    public static function published(): array
    {
        return static::fetchAll(
            'SELECT * FROM leaders WHERE is_published = 1 ORDER BY last_name, first_name'
        );
    }

    public static function currentWithAssignments(): array
    {
        return static::fetchAll('SELECT * FROM v_current_leaders ORDER BY last_name, first_name');
    }

    public static function withAssignments(int $id): ?array
    {
        $leader = static::find($id);
        if (!$leader) {
            return null;
        }
        $leader['assignments'] = LeaderAssignment::forLeader($id);
        return $leader;
    }

    public static function fullName(array $leader): string
    {
        $prefix = trim((string) ($leader['title_prefix'] ?? ''));
        $name = trim(($leader['first_name'] ?? '') . ' ' . ($leader['last_name'] ?? ''));
        return $prefix !== '' ? "{$prefix} {$name}" : $name;
    }
}
