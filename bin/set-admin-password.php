<?php

declare(strict_types=1);

/**
 * Génère / met à jour le mot de passe admin.
 * Usage: php bin/set-admin-password.php "MonMotDePasseSecurise"
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Database;

$root = dirname(__DIR__);
if (is_file($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}

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
