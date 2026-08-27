<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\Department;
use App\Models\MediaCategory;
use App\Models\MediaItem;

final class MediaAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/media/index', [
            'title' => 'Médias',
            'items' => MediaItem::allWithMeta(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->formView(null, 'Nouveau média');
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = MediaItem::uniqueSlug($data['title']);
        $data['created_by'] = Auth::id();

        if ($file = $request->file('file')) {
            $path = upload_file($file, 'media');
            if ($path) {
                $data['file_path'] = $path;
            }
        }
        if ($thumb = $request->file('thumbnail')) {
            $path = upload_file($thumb, 'media/thumbs');
            if ($path) {
                $data['thumbnail'] = $path;
            }
        }

        MediaItem::create($data);
        $this->redirectWith('/admin/media', 'success', 'Média créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $item = MediaItem::find((int) $id);
        if (!$item) {
            $this->redirectWith('/admin/media', 'error', 'Introuvable.');
        }
        $this->formView($item, 'Modifier le média');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);

        if ($file = $request->file('file')) {
            $path = upload_file($file, 'media');
            if ($path) {
                $data['file_path'] = $path;
            }
        }
        if ($thumb = $request->file('thumbnail')) {
            $path = upload_file($thumb, 'media/thumbs');
            if ($path) {
                $data['thumbnail'] = $path;
            }
        }

        MediaItem::update((int) $id, $data);
        $this->redirectWith('/admin/media', 'success', 'Média mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        MediaItem::delete((int) $id);
        $this->redirectWith('/admin/media', 'success', 'Média supprimé.');
    }

    private function formView(?array $item, string $title): void
    {
        $this->adminView('admin/media/form', [
            'title'       => $title,
            'item'        => $item,
            'categories'  => MediaCategory::all('sort_order ASC'),
            'departments' => Department::active(),
            'associations'=> Association::active(),
        ]);
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        $published = $request->input('is_published') ? 1 : 0;
        return [
            'category_id'    => $request->input('category_id') ? (int) $request->input('category_id') : null,
            'department_id'  => $request->input('department_id') ? (int) $request->input('department_id') : null,
            'association_id' => $request->input('association_id') ? (int) $request->input('association_id') : null,
            'type'           => (string) $request->input('type', 'other'),
            'title'          => trim((string) $request->input('title')),
            'description'    => trim((string) $request->input('description')) ?: null,
            'speaker'        => trim((string) $request->input('speaker')) ?: null,
            'external_url'   => trim((string) $request->input('external_url')) ?: null,
            'is_published'   => $published,
            'published_at'   => $published ? ($request->input('published_at') ?: date('Y-m-d H:i:s')) : null,
        ];
    }
}
