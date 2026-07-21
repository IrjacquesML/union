<?php

declare(strict_types=1);

/**
 * Routeur serveur PHP intégré :
 * php -S localhost:8000 -t public public/router.php
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = str_replace(['\\', "\0"], '', $path);
$file = realpath(__DIR__ . $path);

// Empêche toute sortie hors du dossier public
if ($file !== false && str_starts_with($file, realpath(__DIR__)) && is_file($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css; charset=UTF-8',
        'js'   => 'application/javascript; charset=UTF-8',
        'svg'  => 'image/svg+xml',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif'  => 'image/gif',
        'ico'  => 'image/x-icon',
        'pdf'  => 'application/pdf',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'map'  => 'application/json',
    ];

    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
        header('Content-Length: ' . (string) filesize($file));
        header('Cache-Control: public, max-age=86400');
        readfile($file);
        exit;
    }

    // Laisse le serveur intégré servir les autres fichiers
    return false;
}

require __DIR__ . '/index.php';
