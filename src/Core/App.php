<?php

declare(strict_types=1);

namespace App\Core;

final class App
{
    private Router $router;
    private array $config;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        load_env_file($root . '/.env');

        $this->config = require $root . '/config/app.php';
        date_default_timezone_set($this->config['timezone']);

        Session::start($this->config['session']);
        Database::getInstance(require $root . '/config/database.php');

        $this->router = new Router();
        $routes = require $root . '/config/routes.php';
        $routes($this->router);
    }

    public function run(): void
    {
        $request = new Request();
        $this->router->dispatch($request);
    }

    public function config(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? null;
    }
}
