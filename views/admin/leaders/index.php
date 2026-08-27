<?php /** @var array $leaders */ ?>
<div class="toolbar">
    <a class="btn btn-primary" href="/admin/leaders/create">+ Nouveau dirigeant</a>
</div>
<table class="admin-table">
    <thead><tr><th>Nom</th><th>Pasteur</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leaders as $l): ?>
        <tr>
            <td><?= e(trim(($l['title_prefix'] ?? '') . ' ' . $l['first_name'] . ' ' . $l['last_name'])) ?></td>
            <td><?= !empty($l['is_pastor']) ? 'Oui' : 'Non' ?></td>
            <td><?= !empty($l['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/leaders/<?= (int) $l['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/leaders/<?= (int) $l['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
