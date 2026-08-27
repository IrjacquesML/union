<?php

declare(strict_types=1);

use App\Core\Csrf;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function load_env_file(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    if (class_exists(\Dotenv\Dotenv::class)) {
        \Dotenv\Dotenv::createImmutable(dirname($path))->safeLoad();
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($key === '' || str_starts_with($key, '#')) {
            continue;
        }
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if (getenv($key) === false) {
            putenv($key . '=' . $value);
        }
        $_ENV[$key] ??= $value;
        $_SERVER[$key] ??= $value;
    }
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
    $map = [
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
        'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i',
        'î' => 'i', 'ï' => 'i', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ö' => 'o', 'œ' => 'oe', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y',
        'ÿ' => 'y', 'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a', 'Å' => 'a',
        'Æ' => 'ae', 'Ç' => 'c', 'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'Ì' => 'i',
        'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'Ñ' => 'n', 'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o',
        'Õ' => 'o', 'Ö' => 'o', 'Œ' => 'oe', 'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u',
        'Ý' => 'y',
    ];
    $text = strtr($text, $map);
    $translit = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if (is_string($translit) && $translit !== '') {
        $text = $translit;
    }
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

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'mp3', 'mp4', 'doc', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    $dir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subdir, '/');
    if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
        return null;
    }
    if (!is_writable($dir)) {
        return null;
    }

    $filename = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = $dir . '/' . $filename;

    $moved = is_uploaded_file($file['tmp_name']) && move_uploaded_file($file['tmp_name'], $dest);
    if (!$moved && is_uploaded_file($file['tmp_name']) && @copy($file['tmp_name'], $dest)) {
        @unlink($file['tmp_name']);
        $moved = is_file($dest);
    }
    if (!$moved) {
        return null;
    }

    return '/uploads/' . trim($subdir, '/') . '/' . $filename;
}

function upload_error_message(int $code): string
{
    $limit = (string) ini_get('upload_max_filesize');

    return match ($code) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale autorisée (' . $limit . ').',
        UPLOAD_ERR_PARTIAL => 'Le téléchargement a été interrompu. Réessayez.',
        UPLOAD_ERR_NO_FILE => 'Aucun fichier reçu.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d’écrire le fichier sur le disque.',
        UPLOAD_ERR_EXTENSION => 'Une extension PHP a bloqué l’envoi.',
        default => 'Échec du téléchargement (code ' . $code . ').',
    };
}

/**
 * Enregistre une image et renvoie ['path' => ...] ou ['error' => ...].
 *
 * @param array<string, mixed> $file
 * @return array{path?: string, error?: string}
 */
function upload_image(array $file, string $subdir = 'carousel'): array
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error !== UPLOAD_ERR_OK) {
        return ['error' => upload_error_message($error)];
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        return ['error' => 'Formats acceptés : JPG, PNG, WEBP ou GIF.'];
    }

    if ((int) ($file['size'] ?? 0) > 8 * 1024 * 1024) {
        return ['error' => 'Image trop lourde (maximum 8 Mo).'];
    }

    $path = upload_file($file, $subdir);
    if (!$path) {
        return ['error' => 'Impossible d’enregistrer l’image. Vérifiez que le dossier public/uploads est accessible en écriture.'];
    }

    return ['path' => $path];
}

/**
 * Normalise $_FILES pour un champ simple ou multiple (images[]).
 *
 * @return list<array<string, mixed>>
 */
function uploaded_files(string $key): array
{
    if (!isset($_FILES[$key])) {
        return [];
    }

    $file = $_FILES[$key];
    if (!is_array($file['name'])) {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [];
        }
        return [$file];
    }

    $out = [];
    foreach ($file['name'] as $i => $name) {
        if (($file['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        $out[] = [
            'name'     => $file['name'][$i],
            'type'     => $file['type'][$i] ?? '',
            'tmp_name' => $file['tmp_name'][$i],
            'error'    => $file['error'][$i],
            'size'     => $file['size'][$i] ?? 0,
        ];
    }

    return $out;
}

function youtube_id(?string $url): ?string
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $url, $m)) {
        return $m[1];
    }
    return null;
}

function truncate(?string $text, int $length = 160): string
{
    $text = strip_tags((string) $text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '…';
}

function is_active_path(string $path, bool $exact = false): bool
{
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if ($path === '/' || $exact) {
        return $current === $path;
    }
    return str_starts_with($current, $path);
}
