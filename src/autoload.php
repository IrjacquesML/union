<?php

declare(strict_types=1);

/**
 * Autoload du projet (sans Composer).
 * Si vendor/autoload.php existe, index.php l'utilise à la place.
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $path = __DIR__ . '/' . $relative . '.php';
    if (is_file($path)) {
        require $path;
    }
});

require __DIR__ . '/Helpers/functions.php';
