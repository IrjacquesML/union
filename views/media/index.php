<?php /** @var array $items */ /** @var array $galleries */ /** @var ?string $type */ ?>
<section class="page-hero"><div class="container"><h1>Médiathèque</h1><p>Sermons, vidéos, bulletins et documents.</p></div></section>
<section class="section">
    <div class="container">
        <div class="filters">
            <a href="/mediatheque" class="<?= !$type ? 'active' : '' ?>">Tout</a>
            <?php foreach (['sermon','video','audio','bulletin','document','photo'] as $t): ?>
                <a href="/mediatheque?type=<?= $t ?>" class="<?= $type === $t ? 'active' : '' ?>"><?= e(ucfirst($t)) ?></a>
            <?php endforeach; ?>
        </div>
        <div class="cards-plain">
            <?php foreach ($items as $item): ?>
                <a class="plain-link" href="/mediatheque/<?= e($item['slug']) ?>">
                    <small><?= e($item['type']) ?></small>
                    <h2><?= e($item['title']) ?></h2>
                    <p><?= e(truncate($item['description'] ?? '', 100)) ?></p>
                </a>
            <?php endforeach; ?>
            <?php if (!$items): ?><p class="muted">Aucun média publié.</p><?php endif; ?>
        </div>
        <?php if ($galleries): ?>
            <header class="section-head" style="margin-top:2.5rem">
                <h2>Galeries photos</h2>
            </header>
            <div class="cards-plain">
                <?php foreach ($galleries as $gallery): ?>
                    <a class="plain-link" href="/galeries/<?= e($gallery['slug']) ?>">
                        <small><?= (int) ($gallery['image_count'] ?? 0) ?> photo(s)</small>
                        <h2><?= e($gallery['title']) ?></h2>
                        <p><?= e(truncate($gallery['description'] ?? '', 100)) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
