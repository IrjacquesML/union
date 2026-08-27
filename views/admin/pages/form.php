<?php /** @var ?array $page */ ?>
<form method="post"
      action="<?= $page ? '/admin/pages/' . (int) $page['id'] : '/admin/pages' ?>"
      enctype="multipart/form-data"
      class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($page['title'] ?? '') ?>">
    <label>Sous-titre</label>
    <input type="text" name="subtitle" value="<?= e($page['subtitle'] ?? '') ?>">
    <label>Contenu *</label>
    <textarea name="body" rows="12" required><?= e($page['body'] ?? '') ?></textarea>
    <div class="form-grid">
        <div>
            <label>Ordre</label>
            <input type="number" name="sort_order" value="<?= (int) ($page['sort_order'] ?? 0) ?>">
        </div>
        <div>
            <label>Image de couverture</label>
            <input type="file" name="cover_image" accept="image/*">
            <?php if (!empty($page['cover_image'])): ?>
                <img src="<?= e($page['cover_image']) ?>" alt="" class="thumb">
            <?php endif; ?>
        </div>
    </div>
    <label><input type="checkbox" name="is_published" value="1" <?= !$page || !empty($page['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary">Enregistrer</button>
    <a href="/admin/pages" class="btn btn-ghost">Retour</a>
</form>
