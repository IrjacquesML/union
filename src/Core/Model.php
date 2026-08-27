<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::getInstance()->pdo();
    }

    public static function find(int $id): ?array
    {
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id LIMIT 1';
        $stmt = static::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $sql = 'SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy;
        return static::db()->query($sql)->fetchAll();
    }

    public static function create(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn(string $c): string => ':' . $c, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::$table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = static::db()->prepare($sql);
        static::bindValues($stmt, $data);
        $stmt->execute();
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            static::$table,
            implode(', ', $sets),
            static::$primaryKey
        );
        $stmt = static::db()->prepare($sql);
        static::bindValues($stmt, $data);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /** @param array<string, mixed> $data */
    protected static function bindValues(\PDOStatement $stmt, array $data): void
    {
        foreach ($data as $key => $value) {
            $param = ':' . $key;
            if ($value === null) {
                $stmt->bindValue($param, null, PDO::PARAM_NULL);
            } elseif (is_bool($value)) {
                $stmt->bindValue($param, $value ? 1 : 0, PDO::PARAM_INT);
            } elseif (is_int($value)) {
                $stmt->bindValue($param, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($param, $value, PDO::PARAM_STR);
            }
        }
    }

    public static function delete(int $id): bool
    {
        $sql = 'DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = :id';
        $stmt = static::db()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public static function count(string $where = '1=1', array $params = []): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . static::$table . ' WHERE ' . $where;
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** @param array<string, mixed> $params */
    protected static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** @param array<string, mixed> $params */
    protected static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = slugify($text);
        $slug = $base;
        $i = 1;
        while (true) {
            $sql = 'SELECT ' . static::$primaryKey . ' FROM ' . static::$table . ' WHERE slug = :slug';
            $params = ['slug' => $slug];
            if ($ignoreId !== null) {
                $sql .= ' AND ' . static::$primaryKey . ' <> :id';
                $params['id'] = $ignoreId;
            }
            $sql .= ' LIMIT 1';
            if (!static::fetchOne($sql, $params)) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}
