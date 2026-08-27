<?php /** @var array $posts */ ?>
<div class="toolbar"><a class="btn btn-primary" href="/admin/posts/create">+ Actualité</a></div>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Statut</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= e($post['title']) ?></td>
            <td><?= e($post['status']) ?></td>
            <td><?= e(format_date($post['published_at'])) ?></td>
            <td class="actions">
                <a href="/admin/posts/<?= (int) $post['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/posts/<?= (int) $post['id'] ?>/delete" onsubmit="return confirm('Supprimer ?');"><?= csrf_field() ?><button class="link-danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
