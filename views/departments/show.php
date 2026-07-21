<?php /** @var array $department */ /** @var array $leaders */ ?>
<section class="page-hero"><div class="container"><h1><?= e($department['name']) ?></h1></div></section>
<section class="section">
    <div class="container">
        <div class="prose"><?= nl2br(e($department['description'] ?? $department['mission'] ?? 'Description à venir.')) ?></div>
        <h2>Responsables actuels</h2>
        <div class="people-grid">
            <?php foreach ($leaders as $person): ?>
                <article class="person">
                    <h3><a href="/dirigeants/<?= e($person['slug']) ?>"><?= e(trim(($person['title_prefix'] ?? '') . ' ' . $person['first_name'] . ' ' . $person['last_name'])) ?></a></h3>
                    <p><?= e($person['position_title']) ?></p>
                </article>
            <?php endforeach; ?>
            <?php if (!$leaders): ?><p class="muted">Aucun responsable assigné.</p><?php endif; ?>
        </div>
    </div>
</section>
