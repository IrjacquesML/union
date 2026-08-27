<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\Committee;
use App\Models\Department;
use App\Models\Leader;
use App\Models\LeaderAssignment;
use App\Models\Position;

final class LeaderAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/leaders/index', [
            'title'   => 'Dirigeants',
            'leaders' => Leader::all('last_name ASC, first_name ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/leaders/form', [
            'title'  => 'Nouveau dirigeant',
            'leader' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['first_name'] . ' ' . $data['last_name']);

        if ($photo = $request->file('photo')) {
            $path = upload_file($photo, 'leaders');
            if ($path) {
                $data['photo'] = $path;
            }
        }

        $id = Leader::create($data);
        $this->redirectWith('/admin/leaders/' . $id . '/edit', 'success', 'Dirigeant créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $leader = Leader::withAssignments((int) $id);
        if (!$leader) {
            $this->redirectWith('/admin/leaders', 'error', 'Dirigeant introuvable.');
        }

        $this->adminView('admin/leaders/form', [
            'title'       => 'Modifier : ' . Leader::fullName($leader),
            'leader'      => $leader,
            'positions'   => Position::active(),
            'departments' => Department::active(),
            'associations'=> Association::active(),
            'committees'  => Committee::all('name ASC'),
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $leader = Leader::find((int) $id);
        if (!$leader) {
            $this->redirectWith('/admin/leaders', 'error', 'Dirigeant introuvable.');
        }

        $data = $this->validated($request);
        if ($photo = $request->file('photo')) {
            $path = upload_file($photo, 'leaders');
            if ($path) {
                $data['photo'] = $path;
            }
        }

        Leader::update((int) $id, $data);
        $this->redirectWith('/admin/leaders/' . $id . '/edit', 'success', 'Dirigeant mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        try {
            Leader::delete((int) $id);
            $this->redirectWith('/admin/leaders', 'success', 'Dirigeant supprimé.');
        } catch (\Throwable) {
            $this->redirectWith('/admin/leaders', 'error', 'Impossible de supprimer : des mandats sont liés.');
        }
    }

    public function assign(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $leaderId = (int) $id;

        $positionId = (int) $request->input('position_id');
        if ($positionId < 1) {
            $this->redirectWith('/admin/leaders/' . $id . '/edit', 'error', 'Choisissez un poste.');
        }
        $scopeType = (string) $request->input('scope_type', 'union');
        $startDate = (string) $request->input('start_date', date('Y-m-d'));
        $closePrevious = (bool) $request->input('close_previous', false);

        $scopeId = match ($scopeType) {
            'department'  => (int) $request->input('department_id') ?: null,
            'association' => (int) $request->input('association_id') ?: null,
            'committee'   => (int) $request->input('committee_id') ?: null,
            default       => null,
        };

        if ($closePrevious) {
            LeaderAssignment::transfer(
                $leaderId,
                $positionId,
                $scopeType,
                $scopeId,
                $startDate,
                Auth::id()
            );
            $msg = 'Nouveau mandat créé. Les mandats précédents ont été clôturés (historique conservé).';
        } else {
            LeaderAssignment::create([
                'leader_id'   => $leaderId,
                'position_id' => $positionId,
                'scope_type'  => $scopeType,
                'scope_id'    => $scopeId,
                'status'      => 'current',
                'start_date'  => $startDate,
                'created_by'  => Auth::id(),
            ]);
            $msg = 'Affectation ajoutée.';
        }

        $this->redirectWith('/admin/leaders/' . $id . '/edit', 'success', $msg);
    }

    public function endAssignment(Request $request, string $id, string $assignmentId): void
    {
        $this->requireAdmin($request);
        $endDate = (string) $request->input('end_date', date('Y-m-d'));
        LeaderAssignment::endMandate((int) $assignmentId, $endDate);
        $this->redirectWith('/admin/leaders/' . $id . '/edit', 'success', 'Mandat clôturé. Le dirigeant est désormais « ancien » pour ce poste.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return [
            'first_name'      => trim((string) $request->input('first_name')),
            'last_name'       => trim((string) $request->input('last_name')),
            'title_prefix'    => trim((string) $request->input('title_prefix')) ?: null,
            'gender'          => $request->input('gender') ?: null,
            'bio'             => trim((string) $request->input('bio')) ?: null,
            'email'           => trim((string) $request->input('email')) ?: null,
            'phone'           => trim((string) $request->input('phone')) ?: null,
            'ordination_year' => $request->input('ordination_year') ?: null,
            'is_pastor'       => $request->input('is_pastor') ? 1 : 0,
            'is_published'    => $request->input('is_published') ? 1 : 0,
        ];
    }

    private function uniqueSlug(string $name): string
    {
        $base = slugify($name);
        $slug = $base;
        $i = 1;
        while (Leader::findBySlug($slug)) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
