<?php /** @var array $association */ /** @var array $leaders */ ?>
<section class="page-hero"><div class="container"><small><?= e($association['type_label']) ?></small><h1><?= e($association['name']) ?></h1></div></section>
<section class="section">
    <div class="container">
        <div class="prose"><?= nl2br(e($association['description'] ?? 'Description à venir.')) ?></div>
        <?php if ($association['meeting_info']): ?><p><strong>Réunions :</strong> <?= e($association['meeting_info']) ?></p><?php endif; ?>
        <h2>Responsables</h2>
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
