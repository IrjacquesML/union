<?php

declare(strict_types=1);

use App\Controllers\Admin\AssociationAdminController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\BeliefAdminController;
use App\Controllers\Admin\CarouselAdminController;
use App\Controllers\Admin\CommitteeAdminController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DepartmentAdminController;
use App\Controllers\Admin\EventAdminController;
use App\Controllers\Admin\GalleryAdminController;
use App\Controllers\Admin\LeaderAdminController;
use App\Controllers\Admin\MediaAdminController;
use App\Controllers\Admin\PageAdminController;
use App\Controllers\Admin\PostAdminController;
use App\Controllers\Admin\SettingAdminController;
use App\Controllers\AssociationController;
use App\Controllers\ContactController;
use App\Controllers\DepartmentController;
use App\Controllers\EventController;
use App\Controllers\GalleryController;
use App\Controllers\HomeController;
use App\Controllers\LeaderController;
use App\Controllers\MediaController;
use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Core\Router;

return static function (Router $router): void {
    // Front
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/pages/{slug}', [PageController::class, 'show']);
    $router->get('/croyances', [PageController::class, 'beliefs']);
    $router->get('/dirigeants', [LeaderController::class, 'index']);
    $router->get('/dirigeants/{slug}', [LeaderController::class, 'show']);
    $router->get('/departements', [DepartmentController::class, 'index']);
    $router->get('/departements/{slug}', [DepartmentController::class, 'show']);
    $router->get('/associations', [AssociationController::class, 'index']);
    $router->get('/associations/{slug}', [AssociationController::class, 'show']);
    $router->get('/mediatheque', [MediaController::class, 'index']);
    $router->get('/galeries/{slug}', [GalleryController::class, 'show']);
    $router->get('/mediatheque/{slug}', [MediaController::class, 'show']);
    $router->get('/actualites', [PostController::class, 'index']);
    $router->get('/actualites/{slug}', [PostController::class, 'show']);
    $router->get('/evenements', [EventController::class, 'index']);
    $router->get('/evenements/{slug}', [EventController::class, 'show']);
    $router->get('/contact', [ContactController::class, 'index']);
    $router->post('/contact', [ContactController::class, 'store']);

    // Admin auth
    $router->get('/admin/login', [AuthController::class, 'loginForm']);
    $router->post('/admin/login', [AuthController::class, 'login']);
    $router->post('/admin/logout', [AuthController::class, 'logout']);

    // Admin dashboard
    $router->get('/admin', [DashboardController::class, 'index']);

    // Carousel accueil
    $router->get('/admin/carousel', [CarouselAdminController::class, 'index']);
    $router->get('/admin/carousel/create', [CarouselAdminController::class, 'create']);
    $router->post('/admin/carousel', [CarouselAdminController::class, 'store']);
    $router->get('/admin/carousel/{id}/edit', [CarouselAdminController::class, 'edit']);
    $router->post('/admin/carousel/{id}', [CarouselAdminController::class, 'update']);
    $router->post('/admin/carousel/{id}/delete', [CarouselAdminController::class, 'destroy']);

    // Galeries
    $router->get('/admin/galleries', [GalleryAdminController::class, 'index']);
    $router->get('/admin/galleries/create', [GalleryAdminController::class, 'create']);
    $router->post('/admin/galleries', [GalleryAdminController::class, 'store']);
    $router->get('/admin/galleries/{id}/edit', [GalleryAdminController::class, 'edit']);
    $router->post('/admin/galleries/{id}', [GalleryAdminController::class, 'update']);
    $router->post('/admin/galleries/{id}/delete', [GalleryAdminController::class, 'destroy']);
    $router->post('/admin/galleries/{id}/images', [GalleryAdminController::class, 'addImages']);
    $router->post('/admin/galleries/{id}/images/{imageId}/delete', [GalleryAdminController::class, 'deleteImage']);

    // Leaders
    $router->get('/admin/leaders', [LeaderAdminController::class, 'index']);
    $router->get('/admin/leaders/create', [LeaderAdminController::class, 'create']);
    $router->post('/admin/leaders', [LeaderAdminController::class, 'store']);
    $router->get('/admin/leaders/{id}/edit', [LeaderAdminController::class, 'edit']);
    $router->post('/admin/leaders/{id}', [LeaderAdminController::class, 'update']);
    $router->post('/admin/leaders/{id}/delete', [LeaderAdminController::class, 'destroy']);
    $router->post('/admin/leaders/{id}/assign', [LeaderAdminController::class, 'assign']);
    $router->post('/admin/leaders/{id}/assignments/{assignmentId}/end', [LeaderAdminController::class, 'endAssignment']);

    // Departments
    $router->get('/admin/departments', [DepartmentAdminController::class, 'index']);
    $router->get('/admin/departments/create', [DepartmentAdminController::class, 'create']);
    $router->post('/admin/departments', [DepartmentAdminController::class, 'store']);
    $router->get('/admin/departments/{id}/edit', [DepartmentAdminController::class, 'edit']);
    $router->post('/admin/departments/{id}', [DepartmentAdminController::class, 'update']);
    $router->post('/admin/departments/{id}/delete', [DepartmentAdminController::class, 'destroy']);

    // Associations
    $router->get('/admin/associations', [AssociationAdminController::class, 'index']);
    $router->get('/admin/associations/create', [AssociationAdminController::class, 'create']);
    $router->post('/admin/associations', [AssociationAdminController::class, 'store']);
    $router->get('/admin/associations/{id}/edit', [AssociationAdminController::class, 'edit']);
    $router->post('/admin/associations/{id}', [AssociationAdminController::class, 'update']);
    $router->post('/admin/associations/{id}/delete', [AssociationAdminController::class, 'destroy']);

    // Media
    $router->get('/admin/media', [MediaAdminController::class, 'index']);
    $router->get('/admin/media/create', [MediaAdminController::class, 'create']);
    $router->post('/admin/media', [MediaAdminController::class, 'store']);
    $router->get('/admin/media/{id}/edit', [MediaAdminController::class, 'edit']);
    $router->post('/admin/media/{id}', [MediaAdminController::class, 'update']);
    $router->post('/admin/media/{id}/delete', [MediaAdminController::class, 'destroy']);

    // Events
    $router->get('/admin/events', [EventAdminController::class, 'index']);
    $router->get('/admin/events/create', [EventAdminController::class, 'create']);
    $router->post('/admin/events', [EventAdminController::class, 'store']);
    $router->get('/admin/events/{id}/edit', [EventAdminController::class, 'edit']);
    $router->post('/admin/events/{id}', [EventAdminController::class, 'update']);
    $router->post('/admin/events/{id}/delete', [EventAdminController::class, 'destroy']);

    // Posts
    $router->get('/admin/posts', [PostAdminController::class, 'index']);
    $router->get('/admin/posts/create', [PostAdminController::class, 'create']);
    $router->post('/admin/posts', [PostAdminController::class, 'store']);
    $router->get('/admin/posts/{id}/edit', [PostAdminController::class, 'edit']);
    $router->post('/admin/posts/{id}', [PostAdminController::class, 'update']);
    $router->post('/admin/posts/{id}/delete', [PostAdminController::class, 'destroy']);

    // Pages & beliefs
    $router->get('/admin/pages', [PageAdminController::class, 'index']);
    $router->get('/admin/pages/create', [PageAdminController::class, 'create']);
    $router->post('/admin/pages', [PageAdminController::class, 'store']);
    $router->get('/admin/pages/{id}/edit', [PageAdminController::class, 'edit']);
    $router->post('/admin/pages/{id}', [PageAdminController::class, 'update']);
    $router->post('/admin/pages/{id}/delete', [PageAdminController::class, 'destroy']);
    $router->get('/admin/beliefs', [BeliefAdminController::class, 'index']);
    $router->get('/admin/beliefs/create', [BeliefAdminController::class, 'create']);
    $router->post('/admin/beliefs', [BeliefAdminController::class, 'store']);
    $router->get('/admin/beliefs/{id}/edit', [BeliefAdminController::class, 'edit']);
    $router->post('/admin/beliefs/{id}', [BeliefAdminController::class, 'update']);
    $router->post('/admin/beliefs/{id}/delete', [BeliefAdminController::class, 'destroy']);

    // Committees
    $router->get('/admin/committees', [CommitteeAdminController::class, 'index']);
    $router->get('/admin/committees/create', [CommitteeAdminController::class, 'create']);
    $router->post('/admin/committees', [CommitteeAdminController::class, 'store']);
    $router->get('/admin/committees/{id}/edit', [CommitteeAdminController::class, 'edit']);
    $router->post('/admin/committees/{id}', [CommitteeAdminController::class, 'update']);
    $router->post('/admin/committees/{id}/delete', [CommitteeAdminController::class, 'destroy']);
    $router->post('/admin/committees/{id}/members', [CommitteeAdminController::class, 'addMember']);
    $router->post('/admin/committees/{id}/members/{memberId}/end', [CommitteeAdminController::class, 'endMember']);

    // Settings
    $router->get('/admin/settings', [SettingAdminController::class, 'index']);
    $router->post('/admin/settings', [SettingAdminController::class, 'update']);
    $router->post('/admin/messages/{id}/read', [SettingAdminController::class, 'markMessageRead']);
};
