<?php /** @var array $associations */ ?>
<section class="page-hero"><div class="container"><h1>Associations & Groupes</h1></div></section>
<section class="section">
    <div class="container cards-plain">
        <?php foreach ($associations as $a): ?>
            <a class="plain-link" href="/associations/<?= e($a['slug']) ?>">
                <small><?= e($a['type_label']) ?></small>
                <h2><?= e($a['name']) ?></h2>
                <p><?= e(truncate($a['description'] ?? '', 120)) ?></p>
            </a>
        <?php endforeach; ?>
        <?php if (!$associations): ?><p class="muted">Aucune association publiée.</p><?php endif; ?>
    </div>
</section>
