<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Belief;
use App\Models\Page;

final class PageController extends Controller
{
    public function show(Request $request, string $slug): void
    {
        $page = Page::findBySlug($slug);
        if (!$page) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Page introuvable']);
            return;
        }

        $this->view('pages/show', [
            'title' => $page['title'],
            'page'  => $page,
        ]);
    }

    public function beliefs(Request $request): void
    {
        $this->view('pages/beliefs', [
            'title'   => 'Croyances fondamentales',
            'beliefs' => Belief::published(),
        ]);
    }
}
