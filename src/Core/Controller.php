<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function adminView(string $view, array $data = []): void
    {
        Auth::requireLogin();
        $data['authUser'] = Auth::user();
        View::render($view, $data, 'layouts/admin');
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    protected function validateCsrf(Request $request): void
    {
        if (!Csrf::validate($request->input('_csrf'))) {
            http_response_code(419);
            Session::flash('error', 'Jeton CSRF invalide. Veuillez réessayer.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin');
        }
    }

    /** Auth + CSRF pour les actions POST de l'admin. */
    protected function requireAdmin(Request $request): void
    {
        Auth::requireLogin();
        $this->validateCsrf($request);
    }

    protected function redirectWith(string $url, string $type, string $message): void
    {
        Session::flash($type, $message);
        redirect($url);
    }
}
