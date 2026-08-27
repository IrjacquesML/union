<?php /** @var array $event */ ?>
<section class="page-hero"><div class="container"><h1><?= e($event['title']) ?></h1></div></section>
<section class="section">
    <div class="container prose">
        <?php if (!empty($event['cover_image'])): ?>
            <img class="cover-img" src="<?= e($event['cover_image']) ?>" alt="">
        <?php endif; ?>
        <p><strong>Date :</strong> <?= e(format_datetime($event['starts_at'])) ?><?php if ($event['ends_at']): ?> — <?= e(format_datetime($event['ends_at'])) ?><?php endif; ?></p>
        <?php if ($event['location']): ?><p><strong>Lieu :</strong> <?= e($event['location']) ?></p><?php endif; ?>
        <?= nl2br(e($event['description'] ?? '')) ?>
    </div>
</section>
