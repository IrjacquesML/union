<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Post;

final class PostController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('posts/index', [
            'title' => 'Actualités',
            'posts' => Post::published(50),
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $post = Post::findBySlug($slug);
        if (!$post || $post['status'] !== 'published') {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Article introuvable']);
            return;
        }

        $this->view('posts/show', [
            'title' => $post['title'],
            'post'  => $post,
        ]);
    }
}
