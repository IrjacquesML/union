<?php

declare(strict_types=1);

/**
 * Génère / met à jour le mot de passe admin.
 * Usage: php bin/set-admin-password.php "MonMotDePasseSecurise"
 */

$root = dirname(__DIR__);
require is_file($root . '/vendor/autoload.php')
    ? $root . '/vendor/autoload.php'
    : $root . '/src/autoload.php';

use App\Core\Database;

load_env_file($root . '/.env');

$password = $argv[1] ?? null;
if (!$password || strlen($password) < 8) {
    fwrite(STDERR, "Usage: php bin/set-admin-password.php \"MotDePasse(min 8 car.)\"\n");
    exit(1);
}

$config = require $root . '/config/database.php';
$pdo = Database::getInstance($config)->pdo();
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE email = :email');
$stmt->execute([
    'hash'  => $hash,
    'email' => 'admin@uco.local',
]);

echo "Mot de passe mis à jour pour admin@uco.local\n";
