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
            <td class="actions">
                <a href="/admin/committees/<?= (int) $c['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/committees/<?= (int) $c['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
