<?php /** @var array $gallery */ ?>
<section class="page-hero">
    <div class="container">
        <p><a href="/mediatheque">← Médiathèque</a></p>
        <h1><?= e($gallery['title']) ?></h1>
        <?php if (!empty($gallery['event_date'])): ?>
            <p><?= e(format_date($gallery['event_date'])) ?></p>
        <?php endif; ?>
    </div>
</section>
<section class="section">
    <div class="container">
        <?php if (!empty($gallery['description'])): ?>
            <div class="prose"><?= nl2br(e($gallery['description'])) ?></div>
        <?php endif; ?>
        <div class="gallery-grid">
            <?php foreach ($gallery['images'] ?? [] as $img): ?>
                <a class="gallery-item" href="<?= e($img['file_path']) ?>" target="_blank" rel="noopener">
                    <img src="<?= e($img['file_path']) ?>" alt="<?= e($img['alt_text'] ?: ($img['caption'] ?: $gallery['title'])) ?>">
                    <?php if (!empty($img['caption'])): ?>
                        <span><?= e($img['caption']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if (empty($gallery['images'])): ?>
            <p class="muted">Aucune photo dans cette galerie.</p>
        <?php endif; ?>
    </div>
</section>
