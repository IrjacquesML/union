<?php /** @var array $pages */ ?>
<table class="admin-table">
    <thead><tr><th>Titre</th><th>Slug</th><th>Publié</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= e($page['title']) ?></td>
            <td><?= e($page['slug']) ?></td>
            <td><?= !empty($page['is_published']) ? 'Oui' : 'Non' ?></td>
            <td><a href="/admin/pages/<?= (int) $page['id'] ?>/edit">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
