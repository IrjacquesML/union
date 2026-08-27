<?php /** @var array $departments */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/departments/create">+ Département</a></div>
<table class="admin-table">
    <thead><tr><th>Nom</th><th>Actif</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($departments as $d): ?>
        <tr>
            <td><?= e($d['name']) ?></td>
            <td><?= !empty($d['is_active']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/departments/<?= (int) $d['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/departments/<?= (int) $d['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
