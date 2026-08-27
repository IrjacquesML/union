<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\ContactMessage;
use App\Models\SiteSetting;

final class SettingAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/settings/index', [
            'title'    => 'Paramètres du site',
            'settings' => SiteSetting::allKeyed(),
            'messages' => ContactMessage::latest(),
        ]);
    }

    public function update(Request $request): void
    {
        $this->requireAdmin($request);
        $keys = [
            'site_name', 'site_tagline', 'contact_email', 'contact_phone',
            'address', 'social_facebook', 'social_youtube',
        ];
        foreach ($keys as $key) {
            if ($request->input($key) !== null) {
                SiteSetting::set($key, trim((string) $request->input($key)));
            }
        }
        $this->redirectWith('/admin/settings', 'success', 'Paramètres enregistrés.');
    }

    public function markMessageRead(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        ContactMessage::update((int) $id, ['is_read' => 1]);
        $this->redirectWith('/admin/settings', 'success', 'Message marqué comme lu.');
    }
}
