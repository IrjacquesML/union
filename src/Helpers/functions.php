<?php

declare(strict_types=1);

use App\Core\Csrf;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function url(string $path = ''): string
{
    $base = rtrim($_ENV['APP_URL'] ?? '', '/');
    $path = '/' . ltrim($path, '/');
    return $base . ($path === '/' ? '' : $path);
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function csrf_field(): string
{
    return Csrf::field();
}

function old(string $key, mixed $default = ''): string
{
    $flash = \App\Core\Session::get('_old', []);
    return e((string) ($flash[$key] ?? $default));
}

function flash(string $key): ?string
{
    $val = \App\Core\Session::flash($key);
    return $val !== null ? (string) $val : null;
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text) ?? '');
    return trim($text, '-') ?: 'item-' . bin2hex(random_bytes(3));
}

function format_date(?string $date, string $format = 'd/m/Y'): string
{
    if (!$date) {
        return '';
    }
    $dt = date_create($date);
    return $dt ? $dt->format($format) : '';
}

function format_datetime(?string $date): string
{
    return format_date($date, 'd/m/Y H:i');
}

/** Convertit une valeur datetime-local HTML (2026-07-21T14:30) en DATETIME MySQL. */
function normalize_datetime(?string $value): ?string
{
    $value = trim((string) $value);
    if ($value === '') {
        return null;
    }
    $value = str_replace('T', ' ', $value);
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $value)) {
        $value .= ':00';
    }
    $dt = date_create($value);
    return $dt ? $dt->format('Y-m-d H:i:s') : null;
}

function upload_file(array $file, string $subdir = 'misc'): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp3', 'mp4', 'doc', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    $dir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subdir, '/');
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = $dir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return '/uploads/' . trim($subdir, '/') . '/' . $filename;
}

function truncate(?string $text, int $length = 160): string
{
    $text = strip_tags((string) $text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '…';
}

function is_active_path(string $path): bool
{
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($path === '/') {
        return $current === '/';
    }
    return str_starts_with($current, $path);
}
