<?php /** @var array $beliefs */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/beliefs/create">+ Croyance</a></div>
<table class="admin-table">
    <thead><tr><th>N°</th><th>Titre</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($beliefs as $b): ?>
        <tr>
            <td><?= e((string) ($b['number'] ?? '—')) ?></td>
            <td><?= e($b['title']) ?></td>
            <td><?= !empty($b['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/beliefs/<?= (int) $b['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/beliefs/<?= (int) $b['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
