<?php /** @var array $items */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/media/create">+ Média</a></div>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Type</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['title']) ?></td>
            <td><?= e($item['type']) ?></td>
            <td><?= !empty($item['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/media/<?= (int) $item['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/media/<?= (int) $item['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
