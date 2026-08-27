<?php

declare(strict_types=1);

$root = dirname(__DIR__);
require is_file($root . '/vendor/autoload.php')
    ? $root . '/vendor/autoload.php'
    : $root . '/src/autoload.php';

use App\Core\App;
use App\Core\View;

try {
    $app = new App();
    $app->run();
} catch (Throwable $e) {
    http_response_code(500);
    $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    try {
        View::render('errors/500', [
            'title'     => 'Erreur serveur',
            'debug'     => $debug,
            'exception' => $e,
        ], null);
    } catch (Throwable) {
        echo $debug ? htmlspecialchars($e->__toString(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : 'Une erreur est survenue.';
    }
}
