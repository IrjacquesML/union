<?php /** @var array $events */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/events/create">+ Événement</a></div>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Début</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($events as $event): ?>
        <tr>
            <td><?= e($event['title']) ?></td>
            <td><?= e(format_datetime($event['starts_at'])) ?></td>
            <td><?= !empty($event['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/events/<?= (int) $event['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/events/<?= (int) $event['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
