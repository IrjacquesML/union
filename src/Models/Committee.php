<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Committee extends Model
{
    protected static string $table = 'committees';

    public static function active(): array
    {
        return static::fetchAll(
            'SELECT * FROM committees WHERE is_active = 1 ORDER BY name'
        );
    }

    public static function withMembers(int $id): ?array
    {
        $committee = static::find($id);
        if (!$committee) {
            return null;
        }
        $committee['members'] = CommitteeMember::forCommittee($id);
        return $committee;
    }
}
