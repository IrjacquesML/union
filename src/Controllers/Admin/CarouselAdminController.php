<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\CarouselSlide;

final class CarouselAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/carousel/index', [
            'title'  => 'Carousel accueil',
            'slides' => CarouselSlide::allOrdered(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/carousel/form', [
            'title' => 'Nouvelle image carousel',
            'slide' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);

        $file = $request->file('image');
        if (!$file) {
            $this->redirectWith('/admin/carousel/create', 'error', 'Une image est obligatoire.');
        }

        $path = upload_file($file, 'carousel');
        if (!$path) {
            $this->redirectWith('/admin/carousel/create', 'error', 'Format d\'image non accepté.');
        }

        $data['image_path'] = $path;
        CarouselSlide::create($data);
        $this->redirectWith('/admin/carousel', 'success', 'Slide ajouté au carousel.');
    }

    public function edit(Request $request, string $id): void
    {
        $slide = CarouselSlide::find((int) $id);
        if (!$slide) {
            $this->redirectWith('/admin/carousel', 'error', 'Slide introuvable.');
        }

        $this->adminView('admin/carousel/form', [
            'title' => 'Modifier le slide',
            'slide' => $slide,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $slide = CarouselSlide::find((int) $id);
        if (!$slide) {
            $this->redirectWith('/admin/carousel', 'error', 'Slide introuvable.');
        }

        $data = $this->payload($request);

        if ($file = $request->file('image')) {
            $path = upload_file($file, 'carousel');
            if ($path) {
                $data['image_path'] = $path;
            }
        }

        CarouselSlide::update((int) $id, $data);
        $this->redirectWith('/admin/carousel', 'success', 'Slide mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        CarouselSlide::delete((int) $id);
        $this->redirectWith('/admin/carousel', 'success', 'Slide supprimé.');
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'title'      => trim((string) $request->input('title')) ?: null,
            'subtitle'   => trim((string) $request->input('subtitle')) ?: null,
            'link_url'   => trim((string) $request->input('link_url')) ?: null,
            'link_label' => trim((string) $request->input('link_label')) ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->input('is_active') ? 1 : 0,
        ];
    }
}
