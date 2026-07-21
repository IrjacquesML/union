<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Department;

final class DepartmentAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/departments/index', [
            'title'       => 'Départements',
            'departments' => Department::all('sort_order ASC, name ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/departments/form', [
            'title'      => 'Nouveau département',
            'department' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Department::uniqueSlug($data['name']);
        Department::create($data);
        $this->redirectWith('/admin/departments', 'success', 'Département créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $department = Department::find((int) $id);
        if (!$department) {
            $this->redirectWith('/admin/departments', 'error', 'Introuvable.');
        }
        $this->adminView('admin/departments/form', [
            'title'      => 'Modifier le département',
            'department' => $department,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Department::update((int) $id, $this->payload($request));
        $this->redirectWith('/admin/departments', 'success', 'Département mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        try {
            Department::delete((int) $id);
            $this->redirectWith('/admin/departments', 'success', 'Département supprimé.');
        } catch (\Throwable) {
            $this->redirectWith('/admin/departments', 'error', 'Impossible de supprimer : des éléments y sont liés.');
        }
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'name'        => trim((string) $request->input('name')),
            'short_name'  => trim((string) $request->input('short_name')) ?: null,
            'description' => trim((string) $request->input('description')) ?: null,
            'mission'     => trim((string) $request->input('mission')) ?: null,
            'email'       => trim((string) $request->input('email')) ?: null,
            'phone'       => trim((string) $request->input('phone')) ?: null,
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->input('is_active') ? 1 : 0,
        ];
    }
}
