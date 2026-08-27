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

    public function create(Request $request): void
    {
        $this->adminView('admin/pages/form', [
            'title' => 'Nouvelle page',
            'page'  => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Page::uniqueSlug($data['title']);
        $data['updated_by'] = Auth::id();

        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'pages');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }

        Page::create($data);
        $this->redirectWith('/admin/pages', 'success', 'Page créée.');
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
        $data = $this->payload($request);
        $data['updated_by'] = Auth::id();

        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'pages');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }

        Page::update((int) $id, $data);
        $this->redirectWith('/admin/pages', 'success', 'Page mise à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Page::delete((int) $id);
        $this->redirectWith('/admin/pages', 'success', 'Page supprimée.');
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'title'        => trim((string) $request->input('title')),
            'subtitle'     => trim((string) $request->input('subtitle')) ?: null,
            'body'         => (string) $request->input('body'),
            'is_published' => $request->input('is_published') ? 1 : 0,
            'sort_order'   => (int) $request->input('sort_order', 0),
        ];
    }
}
