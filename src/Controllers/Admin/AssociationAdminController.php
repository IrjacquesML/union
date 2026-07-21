<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\AssociationType;
use App\Models\Department;

final class AssociationAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/associations/index', [
            'title'        => 'Associations',
            'associations' => Association::allWithMeta(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->formView(null, 'Nouvelle association');
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Association::uniqueSlug($data['name']);
        Association::create($data);
        $this->redirectWith('/admin/associations', 'success', 'Association créée.');
    }

    public function edit(Request $request, string $id): void
    {
        $association = Association::find((int) $id);
        if (!$association) {
            $this->redirectWith('/admin/associations', 'error', 'Introuvable.');
        }
        $this->formView($association, 'Modifier l\'association');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Association::update((int) $id, $this->payload($request));
        $this->redirectWith('/admin/associations', 'success', 'Association mise à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        try {
            Association::delete((int) $id);
            $this->redirectWith('/admin/associations', 'success', 'Association supprimée.');
        } catch (\Throwable) {
            $this->redirectWith('/admin/associations', 'error', 'Impossible de supprimer : des éléments y sont liés.');
        }
    }

    private function formView(?array $association, string $title): void
    {
        $this->adminView('admin/associations/form', [
            'title'        => $title,
            'association'  => $association,
            'types'        => AssociationType::ordered(),
            'departments'  => Department::active(),
        ]);
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'association_type_id' => (int) $request->input('association_type_id'),
            'department_id'       => $request->input('department_id') ? (int) $request->input('department_id') : null,
            'name'                => trim((string) $request->input('name')),
            'short_name'          => trim((string) $request->input('short_name')) ?: null,
            'description'         => trim((string) $request->input('description')) ?: null,
            'email'               => trim((string) $request->input('email')) ?: null,
            'phone'               => trim((string) $request->input('phone')) ?: null,
            'meeting_info'        => trim((string) $request->input('meeting_info')) ?: null,
            'sort_order'          => (int) $request->input('sort_order', 0),
            'is_active'           => $request->input('is_active') ? 1 : 0,
        ];
    }
}
