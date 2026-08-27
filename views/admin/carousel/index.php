<?php /** @var array $slides */ ?>
<div class="toolbar">
    <a class="btn btn-primary" href="/admin/carousel/create">+ Ajouter une image</a>
</div>
<p class="muted">Ces photos défilent sur la page d’accueil. L’ordre le plus petit s’affiche en premier.</p>

<?php if (!$slides): ?>
    <p>Aucune image pour le moment. <a href="/admin/carousel/create">Ajoutez la première photo</a>.</p>
<?php else: ?>
<div class="slide-cards">
    <?php foreach ($slides as $slide): ?>
        <article class="slide-card">
            <img src="<?= e($slide['image_path']) ?>" alt="<?= e($slide['title'] ?? '') ?>">
            <div class="slide-card-body">
                <strong><?= e($slide['title'] ?: 'Sans titre') ?></strong>
                <small><?= e($slide['subtitle'] ?? '') ?></small>
                <p class="muted">Ordre <?= (int) $slide['sort_order'] ?> · <?= !empty($slide['is_active']) ? 'Visible' : 'Masqué' ?></p>
                <div class="actions">
                    <a href="/admin/carousel/<?= (int) $slide['id'] ?>/edit">Modifier</a>
                    <form method="post" action="/admin/carousel/<?= (int) $slide['id'] ?>/delete" onsubmit="return confirm('Supprimer cette image du carousel ?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="link-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
