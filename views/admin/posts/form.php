<?php /** @var ?array $post */ /** @var array $departments */ ?>
<form method="post" action="<?= $post ? '/admin/posts/' . (int) $post['id'] : '/admin/posts' ?>" enctype="multipart/form-data" class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($post['title'] ?? '') ?>">
    <label>Extrait</label>
    <textarea name="excerpt" rows="2"><?= e($post['excerpt'] ?? '') ?></textarea>
    <label>Contenu *</label>
    <textarea name="body" rows="10" required><?= e($post['body'] ?? '') ?></textarea>
    <div class="form-grid">
        <div>
            <label>Statut</label>
            <select name="status">
                <?php foreach (['draft','published','archived'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($post['status'] ?? 'draft') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Département</label>
            <select name="department_id"><option value="">—</option>
                <?php foreach ($departments as $d): ?><option value="<?= (int) $d['id'] ?>" <?= (int) ($post['department_id'] ?? 0) === (int) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div><label>Image de couverture</label><input type="file" name="cover_image" accept="image/*"></div>
    </div>
    <button class="btn btn-primary">Enregistrer</button>
</form>
