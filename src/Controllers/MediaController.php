<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Gallery;
use App\Models\MediaItem;

final class MediaController extends Controller
{
    public function index(Request $request): void
    {
        $type = $request->input('type');
        $type = is_string($type) && $type !== '' ? $type : null;

        $this->view('media/index', [
            'title'     => 'Médiathèque',
            'items'     => MediaItem::published($type),
            'galleries' => Gallery::published(),
            'type'      => $type,
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $item = MediaItem::findBySlug($slug);
        if (!$item || !(bool) $item['is_published']) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Média introuvable']);
            return;
        }

        $this->view('media/show', [
            'title' => $item['title'],
            'item'  => $item,
        ]);
    }
}
