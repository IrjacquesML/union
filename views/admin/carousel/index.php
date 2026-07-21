<?php /** @var array $slides */ ?>
<div class="toolbar">
    <a class="btn btn-primary" href="/admin/carousel/create">+ Ajouter une image</a>
</div>
<p class="muted">Ces images défilent automatiquement sur la page d’accueil. Ordre : plus petit = affiché en premier.</p>
<table class="admin-table">
    <thead>
        <tr>
            <th>Aperçu</th>
            <th>Titre</th>
            <th>Ordre</th>
            <th>Actif</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($slides as $slide): ?>
        <tr>
            <td><img src="<?= e($slide['image_path']) ?>" alt="" class="carousel-thumb"></td>
            <td>
                <strong><?= e($slide['title'] ?: 'Sans titre') ?></strong><br>
                <small><?= e($slide['subtitle'] ?? '') ?></small>
            </td>
            <td><?= (int) $slide['sort_order'] ?></td>
            <td><?= !empty($slide['is_active']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/carousel/<?= (int) $slide['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/carousel/<?= (int) $slide['id'] ?>/delete" onsubmit="return confirm('Supprimer ce slide ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$slides): ?>
        <tr><td colspan="5">Aucun slide. Ajoutez des images pour le carousel d’accueil.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
