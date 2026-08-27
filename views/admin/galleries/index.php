<?php /** @var array $galleries */ ?>
<div class="toolbar">
    <a class="btn btn-primary" href="/admin/galleries/create">+ Galerie</a>
</div>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Date</th><th>Photos</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($galleries as $g): ?>
        <tr>
            <td><?= e($g['title']) ?></td>
            <td><?= e(format_date($g['event_date'] ?? null)) ?></td>
            <td><?= (int) ($g['image_count'] ?? 0) ?></td>
            <td><?= !empty($g['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/galleries/<?= (int) $g['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/galleries/<?= (int) $g['id'] ?>/delete" onsubmit="return confirm('Supprimer cette galerie ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$galleries): ?>
        <tr><td colspan="5">Aucune galerie. Ajoutez des albums photos pour la médiathèque.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
