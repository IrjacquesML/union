<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, array{0: class-string, 1: string}>> */
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, array $handler): void
    {
        $path = rtrim($path, '/') ?: '/';
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            [$class, $action] = $handler;
            $controller = new $class();
            $controller->$action($request, ...array_values($params));
            return;
        }

        http_response_code(404);
        View::render('errors/404', ['title' => 'Page introuvable'], 'layouts/main');
    }
}
