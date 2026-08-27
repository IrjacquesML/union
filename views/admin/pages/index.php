<?php /** @var array $pages */ ?>
<div class="toolbar">
    <a class="btn btn-primary" href="/admin/pages/create">+ Page</a>
</div>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Slug</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= e($page['title']) ?></td>
            <td><?= e($page['slug']) ?></td>
            <td><?= !empty($page['is_published']) ? 'Oui' : 'Non' ?></td>
            <td class="actions">
                <a href="/admin/pages/<?= (int) $page['id'] ?>/edit">Modifier</a>
                <form method="post" action="/admin/pages/<?= (int) $page['id'] ?>/delete" onsubmit="return confirm('Supprimer cette page ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
