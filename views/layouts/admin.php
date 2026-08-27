<?php
/** @var string $content */
/** @var string $title */
/** @var array|null $authUser */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin') ?> — UCO Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin-body">
<aside class="admin-sidebar">
    <div class="admin-brand">
        <?php \App\Core\View::partial('partials/adventist-logo', ['size' => 28, 'class' => 'admin-brand-logo']); ?>
        UCO Admin
    </div>
    <nav>
        <a href="/admin" class="<?= is_active_path('/admin', true) ? 'is-active' : '' ?>">Tableau de bord</a>
        <a href="/admin/carousel" class="<?= is_active_path('/admin/carousel') ? 'is-active' : '' ?>">Carousel accueil</a>
        <a href="/admin/leaders" class="<?= is_active_path('/admin/leaders') ? 'is-active' : '' ?>">Dirigeants</a>
        <a href="/admin/departments" class="<?= is_active_path('/admin/departments') ? 'is-active' : '' ?>">Départements</a>
        <a href="/admin/associations" class="<?= is_active_path('/admin/associations') ? 'is-active' : '' ?>">Associations</a>
        <a href="/admin/committees" class="<?= is_active_path('/admin/committees') ? 'is-active' : '' ?>">Comités</a>
        <a href="/admin/posts" class="<?= is_active_path('/admin/posts') ? 'is-active' : '' ?>">Actualités</a>
        <a href="/admin/events" class="<?= is_active_path('/admin/events') ? 'is-active' : '' ?>">Événements</a>
        <a href="/admin/media" class="<?= is_active_path('/admin/media') ? 'is-active' : '' ?>">Médias</a>
        <a href="/admin/galleries" class="<?= is_active_path('/admin/galleries') ? 'is-active' : '' ?>">Galeries</a>
        <a href="/admin/pages" class="<?= is_active_path('/admin/pages') ? 'is-active' : '' ?>">Pages</a>
        <a href="/admin/beliefs" class="<?= is_active_path('/admin/beliefs') ? 'is-active' : '' ?>">Croyances</a>
        <a href="/admin/settings" class="<?= is_active_path('/admin/settings') ? 'is-active' : '' ?>">Paramètres</a>
        <a href="/" target="_blank">Voir le site</a>
    </nav>
</aside>
<div class="admin-main">
    <header class="admin-topbar">
        <h1><?= e($title ?? '') ?></h1>
        <div class="admin-user">
            <span><?= e(($authUser['first_name'] ?? '') . ' ' . ($authUser['last_name'] ?? '')) ?></span>
            <form method="post" action="/admin/logout" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-ghost">Déconnexion</button>
            </form>
        </div>
    </header>
    <div class="admin-content">
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>
<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
