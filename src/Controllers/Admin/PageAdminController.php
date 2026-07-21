<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Page;

final class PageAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/pages/index', [
            'title' => 'Pages institutionnelles',
            'pages' => Page::all('sort_order ASC'),
        ]);
    }

    public function edit(Request $request, string $id): void
    {
        $page = Page::find((int) $id);
        if (!$page) {
            $this->redirectWith('/admin/pages', 'error', 'Introuvable.');
        }
        $this->adminView('admin/pages/form', [
            'title' => 'Modifier : ' . $page['title'],
            'page'  => $page,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Page::update((int) $id, [
            'title'        => trim((string) $request->input('title')),
            'subtitle'     => trim((string) $request->input('subtitle')) ?: null,
            'body'         => (string) $request->input('body'),
            'is_published' => $request->input('is_published') ? 1 : 0,
            'sort_order'   => (int) $request->input('sort_order', 0),
            'updated_by'   => Auth::id(),
        ]);
        $this->redirectWith('/admin/pages', 'success', 'Page mise à jour.');
    }
}
