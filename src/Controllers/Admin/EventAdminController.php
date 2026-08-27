<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\Department;
use App\Models\Event;

final class EventAdminController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/events/index', [
            'title'  => 'Événements',
            'events' => Event::all('starts_at DESC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->formView(null, 'Nouvel événement');
    }

    public function store(Request $request): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        $data['slug'] = Event::uniqueSlug($data['title']);
        $data['created_by'] = Auth::id();
        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'events');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }
        Event::create($data);
        $this->redirectWith('/admin/events', 'success', 'Événement créé.');
    }

    public function edit(Request $request, string $id): void
    {
        $event = Event::find((int) $id);
        if (!$event) {
            $this->redirectWith('/admin/events', 'error', 'Introuvable.');
        }
        $this->formView($event, 'Modifier l\'événement');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        $data = $this->payload($request);
        if ($cover = $request->file('cover_image')) {
            $path = upload_file($cover, 'events');
            if ($path) {
                $data['cover_image'] = $path;
            }
        }
        Event::update((int) $id, $data);
        $this->redirectWith('/admin/events', 'success', 'Événement mis à jour.');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin($request);
        Event::delete((int) $id);
        $this->redirectWith('/admin/events', 'success', 'Événement supprimé.');
    }

    private function formView(?array $event, string $title): void
    {
        $this->adminView('admin/events/form', [
            'title'        => $title,
            'event'        => $event,
            'departments'  => Department::active(),
            'associations' => Association::active(),
        ]);
    }

    /** @return array<string, mixed> */
    private function payload(Request $request): array
    {
        return [
            'department_id'  => $request->input('department_id') ? (int) $request->input('department_id') : null,
            'association_id' => $request->input('association_id') ? (int) $request->input('association_id') : null,
            'title'          => trim((string) $request->input('title')),
            'description'    => trim((string) $request->input('description')) ?: null,
            'location'       => trim((string) $request->input('location')) ?: null,
            'starts_at'      => normalize_datetime((string) $request->input('starts_at')) ?? date('Y-m-d H:i:s'),
            'ends_at'        => normalize_datetime($request->input('ends_at') ? (string) $request->input('ends_at') : null),
            'is_all_day'     => $request->input('is_all_day') ? 1 : 0,
            'is_published'   => $request->input('is_published') ? 1 : 0,
        ];
    }
}
