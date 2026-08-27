<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class SiteSetting extends Model
{
    protected static string $table = 'site_settings';

    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::fetchOne(
            'SELECT setting_value FROM site_settings WHERE setting_key = :key LIMIT 1',
            ['key' => $key]
        );
        return $row['setting_value'] ?? $default;
    }

    public static function allKeyed(): array
    {
        $rows = static::all('setting_key ASC');
        $out = [];
        foreach ($rows as $row) {
            $out[$row['setting_key']] = $row;
        }
        return $out;
    }

    public static function set(string $key, ?string $value): void
    {
        $existing = static::fetchOne(
            'SELECT id FROM site_settings WHERE setting_key = :key LIMIT 1',
            ['key' => $key]
        );
        if ($existing) {
            static::update((int) $existing['id'], ['setting_value' => $value]);
            return;
        }
        static::create([
            'setting_key'   => $key,
            'setting_value' => $value,
            'value_type'    => 'string',
        ]);
    }
}
