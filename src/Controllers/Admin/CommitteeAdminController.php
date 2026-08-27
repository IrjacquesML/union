<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\Leader;
use App\Models\Position;

final class CommitteeAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/committees/index', [
            'title'      => 'Comités',
            'committees' => Committee::all('name ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->adminView('admin/committees/form', [
            'title'     => 'Nouveau comité',
            'committee' => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Committee::uniqueSlug($data['name']);
        $id = Committee::create($data);
        $this->redirectWith('/admin/committees/' . $id . '/edit', 'success', 'Comité créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $committee = Committee::withMembers((int) $id);
        if (!$committee) {
            $this->redirectWith('/admin/committees', 'error', 'Introuvable.');
        }
        $this->adminView('admin/committees/form', [
            'title'     => 'Modifier le comité',
            'committee' => $committee,
            'leaders'   => Leader::all('last_name ASC'),
            'positions' => Position::active(),
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Committee::update((int) $id, $this->payload($request));
        $this->redirectWith('/admin/committees/' . $id . '/edit', 'success', 'Comité mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        try {
            Committee::delete((int) $id);
            $this->redirectWith('/admin/committees', 'success', 'Comité supprimé.');
        } catch (\Throwable) {
            $this->redirectWith('/admin/committees', 'error', 'Impossible de supprimer : des membres y sont liés.');
        }
    }

    public function addMember(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $leaderId = (int) $request->input('leader_id');
        $positionId = (int) $request->input('position_id');
        if ($leaderId < 1 || $positionId < 1) {
            $this->redirectWith('/admin/committees/' . $id . '/edit', 'error', 'Choisissez un dirigeant et un poste.');
        }
        CommitteeMember::create([
            'committee_id' => (int) $id,
            'leader_id'    => $leaderId,
            'position_id'  => $positionId,
            'status'       => 'current',
            'start_date'   => (string) $request->input('start_date', date('Y-m-d')),
            'sort_order'   => (int) $request->input('sort_order', 0),
        ]);
        $this->redirectWith('/admin/committees/' . $id . '/edit', 'success', 'Membre ajouté.');
    }

    public function endMember(Request $request, string $id, string $memberId): void
    {
        $this->requireAdmin($request);
        CommitteeMember::endMembership((int) $memberId, (string) $request->input('end_date', date('Y-m-d')));
        $this->redirectWith('/admin/committees/' . $id . '/edit', 'success', 'Membre marqué comme ancien (historique conservé).');
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'name'        => trim((string) $request->input('name')),
            'description' => trim((string) $request->input('description')) ?: null,
            'term_label'  => trim((string) $request->input('term_label')) ?: null,
            'start_date'  => $request->input('start_date') ?: null,
            'end_date'    => $request->input('end_date') ?: null,
            'is_active'   => $request->input('is_active') ? 1 : 0,
        ];
    }
}
