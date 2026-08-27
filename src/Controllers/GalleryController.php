<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Gallery;

final class GalleryController extends Controller
{
    public function show(Request $request, string $slug): void
    {
        $gallery = Gallery::findPublishedBySlug($slug);
        if (!$gallery) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Galerie introuvable']);
            return;
        }

        $this->view('galleries/show', [
            'title'   => $gallery['title'],
            'gallery' => $gallery,
        ]);
    }
}
