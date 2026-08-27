<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CommitteeMember extends Model
{
    protected static string $table = 'committee_members';

    public static function forCommittee(int $committeeId): array
    {
        return static::fetchAll(
            'SELECT cm.*, l.first_name, l.last_name, l.photo, l.title_prefix, l.slug,
                    p.title AS position_title
             FROM committee_members cm
             JOIN leaders l ON l.id = cm.leader_id
             JOIN positions p ON p.id = cm.position_id
             WHERE cm.committee_id = :id
             ORDER BY cm.status ASC, cm.sort_order, l.last_name',
            ['id' => $committeeId]
        );
    }

    public static function endMembership(int $id, string $endDate): bool
    {
        return static::update($id, [
            'end_date' => $endDate,
            'status'   => 'former',
        ]);
    }
}
