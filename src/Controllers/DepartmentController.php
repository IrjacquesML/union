<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Department;
use App\Models\LeaderAssignment;

final class DepartmentController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('departments/index', [
            'title'       => 'Départements',
            'departments' => Department::active(),
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $department = Department::findBySlug($slug);
        if (!$department || !(bool) $department['is_active']) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Département introuvable']);
            return;
        }

        $this->view('departments/show', [
            'title'      => $department['name'],
            'department' => $department,
            'leaders'    => LeaderAssignment::currentForScope('department', (int) $department['id']),
        ]);
    }
}
