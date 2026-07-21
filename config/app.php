<?php

declare(strict_types=1);

return [
    'name'   => $_ENV['APP_NAME'] ?? 'UCO',
    'env'    => $_ENV['APP_ENV'] ?? 'production',
    'debug'  => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url'    => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/'),
    'timezone' => 'Africa/Kinshasa',
    'session' => [
        'name' => $_ENV['SESSION_NAME'] ?? 'uco_session',
        'lifetime' => 7200,
    ],
    'upload_path' => dirname(__DIR__) . '/public/uploads',
    'upload_url'  => '/uploads',
];
