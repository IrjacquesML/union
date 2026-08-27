<?php /** @var array $associations */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/associations/create">+ Association</a></div>
<table class="admin-table">
    <thead><tr><th>Nom</th><th>Type</th><th>Département</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($associations as $a): ?>
        <tr>
            <td><?= e($a['name']) ?></td>
            <td><?= e($a['type_label']) ?></td>
            <td><?= e($a['department_name'] ?? '—') ?></td>
            <td class="actions">
                <a href="/admin/associations/<?= (int) $a['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/associations/<?= (int) $a['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
