<?php /** @var array $committees */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/committees/create">+ Comité</a></div>
<table class="admin-table">
    <thead><tr><th>Nom</th><th>Mandat</th><th>Actif</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($committees as $c): ?>
        <tr>
            <td><?= e($c['name']) ?></td>
            <td><?= e($c['term_label'] ?? '—') ?></td>
            <td><?= !empty($c['is_active']) ? 'Oui' : 'Non' ?></td>
            <td><a href="/admin/committees/<?= (int) $c['id'] ?>/edit">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
