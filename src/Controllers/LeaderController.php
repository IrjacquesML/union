<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Leader;
use App\Models\LeaderAssignment;

final class LeaderController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('leaders/index', [
            'title'         => 'Direction & Dirigeants',
            'current'       => Leader::currentWithAssignments(),
            'unionLeaders'  => LeaderAssignment::currentForScope('union'),
            'allLeaders'    => Leader::published(),
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $leader = Leader::findBySlug($slug);
        if (!$leader || !(bool) $leader['is_published']) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Dirigeant introuvable']);
            return;
        }

        $this->view('leaders/show', [
            'title'       => Leader::fullName($leader),
            'leader'      => $leader,
            'assignments' => LeaderAssignment::forLeader((int) $leader['id']),
        ]);
    }
}
