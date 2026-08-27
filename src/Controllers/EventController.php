<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Event;

final class EventController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('events/index', [
            'title'  => 'Événements',
            'events' => Event::published(),
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $event = Event::findBySlug($slug);
        if (!$event || !(bool) $event['is_published']) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Événement introuvable']);
            return;
        }

        $this->view('events/show', [
            'title' => $event['title'],
            'event' => $event,
        ]);
    }
}
