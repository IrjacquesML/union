<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\LeaderAssignment;

final class AssociationController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('associations/index', [
            'title'        => 'Associations & Groupes',
            'associations' => Association::active(),
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $association = Association::findBySlug($slug);
        if (!$association || !(bool) $association['is_active']) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Association introuvable']);
            return;
        }

        $this->view('associations/show', [
            'title'       => $association['name'],
            'association' => $association,
            'leaders'     => LeaderAssignment::currentForScope('association', (int) $association['id']),
        ]);
    }
}
