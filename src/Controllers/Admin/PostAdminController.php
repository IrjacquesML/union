<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Department;
use App\Models\Post;

final class PostAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/posts/index', [
            'title' => 'Actualités',
            'posts' => Post::allWithMeta(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->formView(null, 'Nouvelle actualité');
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Post::uniqueSlug($data['title']);
        $data['author_id'] = Auth::id();

        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'posts');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }

        Post::create($data);
        $this->redirectWith('/admin/posts', 'success', 'Article créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $post = Post::find((int) $id);
        if (!$post) {
            $this->redirectWith('/admin/posts', 'error', 'Introuvable.');
        }
        $this->formView($post, 'Modifier l\'article');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'posts');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }
        Post::update((int) $id, $data);
        $this->redirectWith('/admin/posts', 'success', 'Article mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Post::delete((int) $id);
        $this->redirectWith('/admin/posts', 'success', 'Article supprimé.');
    }

    private function formView(?array $post, string $title): void
    {
        $this->adminView('admin/posts/form', [
            'title'       => $title,
            'post'        => $post,
            'departments' => Department::active(),
        ]);
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        $status = (string) $request->input('status', 'draft');
        return [
            'department_id' => $request->input('department_id') ? (int) $request->input('department_id') : null,
            'title'         => trim((string) $request->input('title')),
            'excerpt'       => trim((string) $request->input('excerpt')) ?: null,
            'body'          => (string) $request->input('body'),
            'status'        => $status,
            'published_at'  => $status === 'published'
                ? ($request->input('published_at') ?: date('Y-m-d H:i:s'))
                : null,
        ];
    }
}
