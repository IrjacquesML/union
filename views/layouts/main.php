<?php
/** @var string $content */
/** @var string $title */
use App\Models\SiteSetting;
$siteName = SiteSetting::get('site_name', 'UCO');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? $siteName) ?> — <?= e($siteName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="/">
            <?php \App\Core\View::partial('partials/adventist-logo', ['size' => 48, 'class' => 'brand-logo']); ?>
            <span class="brand-text">
                <strong>Union du Congo Ouest</strong>
                <small>Adventistes du 7<sup>e</sup> Jour</small>
            </span>
        </a>
        <button class="nav-toggle" type="button" aria-label="Menu" data-nav-toggle>☰</button>
        <nav class="site-nav" data-nav>
            <a href="/" class="<?= is_active_path('/') ? 'active' : '' ?>">Accueil</a>
            <a href="/pages/histoire" class="<?= is_active_path('/pages') ? 'active' : '' ?>">Église</a>
            <a href="/dirigeants" class="<?= is_active_path('/dirigeants') ? 'active' : '' ?>">Direction</a>
            <a href="/departements" class="<?= is_active_path('/departements') ? 'active' : '' ?>">Départements</a>
            <a href="/associations" class="<?= is_active_path('/associations') ? 'active' : '' ?>">Associations</a>
            <a href="/mediatheque" class="<?= is_active_path('/mediatheque') ? 'active' : '' ?>">Médias</a>
            <a href="/actualites" class="<?= is_active_path('/actualites') ? 'active' : '' ?>">Actualités</a>
            <a href="/contact" class="<?= is_active_path('/contact') ? 'active' : '' ?>">Contact</a>
        </nav>
    </div>
</header>

<main>
    <?php if ($msg = flash('success')): ?>
        <div class="flash flash-success container"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="flash flash-error container"><?= e($msg) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <strong><?= e($siteName) ?></strong>
            <p><?= e((string) SiteSetting::get('site_tagline', '')) ?></p>
        </div>
        <div>
            <p><?= e((string) SiteSetting::get('address', 'Kinshasa, RDC')) ?></p>
            <p><?= e((string) SiteSetting::get('contact_email', '')) ?></p>
        </div>
        <div>
            <a href="/croyances">Croyances</a>
            <a href="/evenements">Événements</a>
            <a href="/admin/login">Administration</a>
        </div>
    </div>
    <div class="container footer-copy">
        <small>&copy; <?= date('Y') ?> UCO — Tous droits réservés</small>
    </div>
</footer>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
