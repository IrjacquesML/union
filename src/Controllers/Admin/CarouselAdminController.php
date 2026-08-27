<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
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
            $this->failForm('/admin/carousel/create', $request, 'Choisissez une image (JPG, PNG, WEBP ou GIF).');
        }

        $uploaded = upload_image($file, 'carousel');
        if (!isset($uploaded['path'])) {
            $this->failForm('/admin/carousel/create', $request, $uploaded['error'] ?? 'Image invalide.');
        }

        $data['image_path'] = $uploaded['path'];
        Session::remove('_old');
        CarouselSlide::create($data);
        $this->redirectWith('/admin/carousel', 'success', 'Image ajoutée au carousel d’accueil.');
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
            $uploaded = upload_image($file, 'carousel');
            if (!isset($uploaded['path'])) {
                $this->failForm('/admin/carousel/' . $id . '/edit', $request, $uploaded['error'] ?? 'Image invalide.');
            }
            $data['image_path'] = $uploaded['path'];
        }

        Session::remove('_old');
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

    private function failForm(string $url, Request $request, string $message): never
    {
        Session::set('_old', $request->all());
        $this->redirectWith($url, 'error', $message);
    }
}
