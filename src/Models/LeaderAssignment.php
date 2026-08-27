<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class LeaderAssignment extends Model
{
    protected static string $table = 'leader_assignments';

    public static function forLeader(int $leaderId): array
    {
        return static::fetchAll(
            'SELECT a.*, p.title AS position_title, p.code AS position_code,
                    CASE a.scope_type
                        WHEN \'department\'  THEN d.name
                        WHEN \'association\' THEN ass.name
                        WHEN \'committee\'   THEN c.name
                        ELSE \'Union\'
                    END AS scope_name
             FROM leader_assignments a
             JOIN positions p ON p.id = a.position_id
             LEFT JOIN departments  d   ON a.scope_type = \'department\'  AND d.id   = a.scope_id
             LEFT JOIN associations ass ON a.scope_type = \'association\' AND ass.id = a.scope_id
             LEFT JOIN committees   c   ON a.scope_type = \'committee\'   AND c.id   = a.scope_id
             WHERE a.leader_id = :id
             ORDER BY a.start_date DESC',
            ['id' => $leaderId]
        );
    }

    public static function currentForScope(string $scopeType, ?int $scopeId = null): array
    {
        $sql = 'SELECT a.*, l.first_name, l.last_name, l.slug, l.photo, l.title_prefix,
                       p.title AS position_title
                FROM leader_assignments a
                JOIN leaders l ON l.id = a.leader_id
                JOIN positions p ON p.id = a.position_id
                WHERE a.status = \'current\' AND a.end_date IS NULL
                  AND a.scope_type = :scope_type AND l.is_published = 1';
        $params = ['scope_type' => $scopeType];

        if ($scopeId === null) {
            $sql .= ' AND a.scope_id IS NULL';
        } else {
            $sql .= ' AND a.scope_id = :scope_id';
            $params['scope_id'] = $scopeId;
        }

        $sql .= ' ORDER BY p.sort_order, l.last_name';
        return static::fetchAll($sql, $params);
    }

    /**
     * Clôture un mandat actuel puis crée le nouveau (conserve l'historique).
     */
    public static function transfer(
        int $leaderId,
        int $positionId,
        string $scopeType,
        ?int $scopeId,
        string $startDate,
        ?int $createdBy = null,
        string $status = 'current'
    ): int {
        $pdo = static::db();
        $pdo->beginTransaction();

        try {
            $close = $pdo->prepare(
                'UPDATE leader_assignments
                 SET status = \'former\', end_date = :end_date, updated_at = NOW()
                 WHERE leader_id = :leader_id AND status = \'current\' AND end_date IS NULL'
            );
            $close->execute([
                'end_date'  => $startDate,
                'leader_id' => $leaderId,
            ]);

            $id = static::create([
                'leader_id'   => $leaderId,
                'position_id' => $positionId,
                'scope_type'  => $scopeType,
                'scope_id'    => $scopeId,
                'status'      => $status,
                'start_date'  => $startDate,
                'end_date'    => null,
                'created_by'  => $createdBy,
            ]);

            $pdo->commit();
            return $id;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function endMandate(int $assignmentId, string $endDate): bool
    {
        return static::update($assignmentId, [
            'end_date' => $endDate,
            'status'   => 'former',
        ]);
    }
}
