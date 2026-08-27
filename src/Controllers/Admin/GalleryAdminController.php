<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Gallery;
use App\Models\GalleryImage;

final class GalleryAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/galleries/index', [
            'title'     => 'Galeries photos',
            'galleries' => Gallery::allWithCount(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/galleries/form', [
            'title'   => 'Nouvelle galerie',
            'gallery' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Gallery::uniqueSlug($data['title']);

        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'galleries');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }

        $id = Gallery::create($data);
        $this->redirectWith('/admin/galleries/' . $id . '/edit', 'success', 'Galerie créée.');
    }

    public function edit(Request $request, string $id): void
    {
        $gallery = Gallery::withImages((int) $id);
        if (!$gallery) {
            $this->redirectWith('/admin/galleries', 'error', 'Introuvable.');
        }
        $this->adminView('admin/galleries/form', [
            'title'   => 'Modifier la galerie',
            'gallery' => $gallery,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $gallery = Gallery::find((int) $id);
        if (!$gallery) {
            $this->redirectWith('/admin/galleries', 'error', 'Introuvable.');
        }

        $data = $this->payload($request);
        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'galleries');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }

        Gallery::update((int) $id, $data);
        $this->redirectWith('/admin/galleries/' . $id . '/edit', 'success', 'Galerie mise à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Gallery::delete((int) $id);
        $this->redirectWith('/admin/galleries', 'success', 'Galerie supprimée.');
    }

    public function addImages(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $gallery = Gallery::find((int) $id);
        if (!$gallery) {
            $this->redirectWith('/admin/galleries', 'error', 'Introuvable.');
        }

        $added = 0;
        foreach (uploaded_files('images') as $file) {
            $path = upload_file($file, 'galleries');
            if (!$path) {
                continue;
            }
            GalleryImage::create([
                'gallery_id' => (int) $id,
                'file_path'  => $path,
                'caption'    => trim((string) $request->input('caption')) ?: null,
                'alt_text'   => trim((string) $request->input('alt_text')) ?: null,
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);
            $added++;
        }

        if ($added === 0) {
            $this->redirectWith('/admin/galleries/' . $id . '/edit', 'error', 'Aucun fichier image valide.');
        }

        $this->redirectWith('/admin/galleries/' . $id . '/edit', 'success', $added . ' image(s) ajoutée(s).');
    }

    public function deleteImage(Request $request, string $id, string $imageId): void
    {
        $this->requireAdmin($request);
        GalleryImage::delete((int) $imageId);
        $this->redirectWith('/admin/galleries/' . $id . '/edit', 'success', 'Image retirée.');
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'title'        => trim((string) $request->input('title')),
            'description'  => trim((string) $request->input('description')) ?: null,
            'event_date'   => $request->input('event_date') ?: null,
            'is_published' => $request->input('is_published') ? 1 : 0,
        ];
    }
}
