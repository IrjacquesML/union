<?php /** @var array $page */ ?>
<form method="post" action="/admin/pages/<?= (int) $page['id'] ?>" class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($page['title']) ?>">
    <label>Sous-titre</label>
    <input type="text" name="subtitle" value="<?= e($page['subtitle'] ?? '') ?>">
    <label>Contenu *</label>
    <textarea name="body" rows="12" required><?= e($page['body']) ?></textarea>
    <label>Ordre</label>
    <input type="number" name="sort_order" value="<?= (int) $page['sort_order'] ?>">
    <label><input type="checkbox" name="is_published" value="1" <?= !empty($page['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
