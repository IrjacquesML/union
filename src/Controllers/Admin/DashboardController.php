<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Association;
use App\Models\ContactMessage;
use App\Models\Department;
use App\Models\Event;
use App\Models\Leader;
use App\Models\MediaItem;
use App\Models\Post;

final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $this->adminView('admin/dashboard/index', [
            'title' => 'Tableau de bord',
            'stats' => [
                'leaders'      => Leader::count(),
                'departments'  => Department::count(),
                'associations' => Association::count(),
                'posts'        => Post::count(),
                'events'       => Event::count(),
                'media'        => MediaItem::count(),
                'messages'     => ContactMessage::unreadCount(),
            ],
            'recentMessages' => array_slice(ContactMessage::latest(), 0, 5),
        ]);
    }
}
