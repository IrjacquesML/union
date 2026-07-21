<?php /** @var array $item */ ?>
<section class="page-hero"><div class="container"><small><?= e($item['type']) ?></small><h1><?= e($item['title']) ?></h1></div></section>
<section class="section">
    <div class="container prose">
        <?php if ($item['speaker']): ?><p><strong>Intervenant :</strong> <?= e($item['speaker']) ?></p><?php endif; ?>
        <?= nl2br(e($item['description'] ?? '')) ?>
        <?php if ($item['external_url']): ?>
            <p><a class="btn btn-primary" href="<?= e($item['external_url']) ?>" target="_blank" rel="noopener">Voir / écouter</a></p>
        <?php endif; ?>
        <?php if ($item['file_path']): ?>
            <p><a class="btn btn-outline" href="<?= e($item['file_path']) ?>" download>Télécharger</a></p>
        <?php endif; ?>
    </div>
</section>
