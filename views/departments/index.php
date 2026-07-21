<?php /** @var array $departments */ ?>
<section class="page-hero"><div class="container"><h1>Départements</h1><p>Ministères de l’Union.</p></div></section>
<section class="section">
    <div class="container cards-plain">
        <?php foreach ($departments as $d): ?>
            <a class="plain-link" href="/departements/<?= e($d['slug']) ?>">
                <h2><?= e($d['name']) ?></h2>
                <p><?= e(truncate($d['description'] ?? $d['mission'] ?? '', 120)) ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>
