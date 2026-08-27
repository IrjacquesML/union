<?php
/** @var array $leader */
/** @var array $assignments */
use App\Models\Leader;
?>
<section class="page-hero">
    <div class="container leader-hero">
        <div class="person-photo large">
            <?php if (!empty($leader['photo'])): ?>
                <img src="<?= e($leader['photo']) ?>" alt="">
            <?php else: ?>
                <span><?= e(mb_substr($leader['first_name'], 0, 1) . mb_substr($leader['last_name'], 0, 1)) ?></span>
            <?php endif; ?>
        </div>
        <div>
            <h1><?= e(Leader::fullName($leader)) ?></h1>
            <?php if (!empty($leader['is_pastor'])): ?><p class="badge">Pasteur</p><?php endif; ?>
        </div>
    </div>
</section>
<section class="section">
    <div class="container split">
        <div class="prose">
            <?= $leader['bio'] ? nl2br(e($leader['bio'])) : '<p class="muted">Biographie à venir.</p>' ?>
        </div>
        <aside>
            <h2>Historique des mandats</h2>
            <ul class="timeline">
                <?php foreach ($assignments as $a): ?>
                    <li class="<?= e($a['status']) ?>">
                        <strong><?= e($a['position_title']) ?></strong>
                        <span><?= e($a['scope_name'] ?? ucfirst((string) $a['scope_type'])) ?></span>
                        <span><?= e(format_date($a['start_date'])) ?> — <?= $a['end_date'] ? e(format_date($a['end_date'])) : 'en cours' ?></span>
                        <em><?= $a['status'] === 'current' ? 'Actuel' : 'Ancien dirigeant' ?></em>
                    </li>
                <?php endforeach; ?>
                <?php if (!$assignments): ?><li class="muted">Aucun mandat enregistré.</li><?php endif; ?>
            </ul>
        </aside>
    </div>
</section>
