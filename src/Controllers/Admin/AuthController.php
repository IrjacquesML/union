<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;

final class AuthController extends Controller
{
    public function loginForm(Request $request): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }

        $this->view('admin/auth/login', [
            'title' => 'Connexion administration',
        ], 'layouts/auth');
    }

    public function login(Request $request): void
    {
        $this->validateCsrf($request);

        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if (!Auth::attempt($email, $password)) {
            Session::set('_old', ['email' => $email]);
            $this->redirectWith('/admin/login', 'error', 'Identifiants incorrects.');
        }

        Session::remove('_old');
        redirect('/admin');
    }

    public function logout(Request $request): void
    {
        $this->requireAdmin($request);
        Auth::logout();
        $this->redirectWith('/admin/login', 'success', 'Vous êtes déconnecté.');
    }
}
