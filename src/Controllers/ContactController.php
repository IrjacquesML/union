<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\ContactMessage;
use App\Models\SiteSetting;

final class ContactController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('contact/index', [
            'title' => 'Contact',
            'email' => SiteSetting::get('contact_email'),
            'phone' => SiteSetting::get('contact_phone'),
            'address' => SiteSetting::get('address'),
        ]);
    }

    public function store(Request $request): void
    {
        $this->validateCsrf($request);

        $name = trim((string) $request->input('name', ''));
        $email = trim((string) $request->input('email', ''));
        $subject = trim((string) $request->input('subject', ''));
        $message = trim((string) $request->input('message', ''));
        $phone = trim((string) $request->input('phone', ''));

        if ($name === '' || $email === '' || $subject === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::set('_old', $request->all());
            $this->redirectWith('/contact', 'error', 'Veuillez remplir correctement tous les champs obligatoires.');
        }

        ContactMessage::create([
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone !== '' ? $phone : null,
            'subject'    => $subject,
            'message'    => $message,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        Session::remove('_old');
        $this->redirectWith('/contact', 'success', 'Votre message a bien été envoyé. Merci.');
    }
}
