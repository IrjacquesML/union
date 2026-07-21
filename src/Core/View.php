<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = dirname(__DIR__, 2) . '/views/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException("Vue introuvable : {$view}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = dirname(__DIR__, 2) . '/views/' . str_replace('.', '/', $layout) . '.php';
        if (!is_file($layoutFile)) {
            throw new \RuntimeException("Layout introuvable : {$layout}");
        }

        require $layoutFile;
    }

    public static function partial(string $partial, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $file = dirname(__DIR__, 2) . '/views/' . str_replace('.', '/', $partial) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
}
