<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Belief;

final class BeliefAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/beliefs/index', [
            'title'   => 'Croyances',
            'beliefs' => Belief::all('sort_order ASC, number ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/beliefs/form', [
            'title'  => 'Nouvelle croyance',
            'belief' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        try {
            Belief::create($this->payload($request));
        } catch (\Throwable) {
            $this->redirectWith('/admin/beliefs/create', 'error', 'Impossible d’enregistrer : numéro déjà utilisé ou données invalides.');
        }
        $this->redirectWith('/admin/beliefs', 'success', 'Croyance créée.');
    }

    public function edit(Request $request, string $id): void
    {
        $belief = Belief::find((int) $id);
        if (!$belief) {
            $this->redirectWith('/admin/beliefs', 'error', 'Introuvable.');
        }
        $this->adminView('admin/beliefs/form', [
            'title'  => 'Modifier la croyance',
            'belief' => $belief,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        try {
            Belief::update((int) $id, $this->payload($request));
        } catch (\Throwable) {
            $this->redirectWith('/admin/beliefs/' . $id . '/edit', 'error', 'Impossible d’enregistrer : numéro déjà utilisé ou données invalides.');
        }
        $this->redirectWith('/admin/beliefs', 'success', 'Croyance mise à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Belief::delete((int) $id);
        $this->redirectWith('/admin/beliefs', 'success', 'Croyance supprimée.');
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'number'       => $request->input('number') !== '' ? (int) $request->input('number') : null,
            'title'        => trim((string) $request->input('title')),
            'summary'      => trim((string) $request->input('summary')) ?: null,
            'body'         => trim((string) $request->input('body')) ?: null,
            'sort_order'   => (int) $request->input('sort_order', 0),
            'is_published' => $request->input('is_published') ? 1 : 0,
        ];
    }
}
