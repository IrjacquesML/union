<?php /** @var ?array $item */ /** @var array $categories */ /** @var array $departments */ /** @var array $associations */ ?>
<form method="post" action="<?= $item ? '/admin/media/' . (int) $item['id'] : '/admin/media' ?>" enctype="multipart/form-data" class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($item['title'] ?? '') ?>">
    <div class="form-grid">
        <div>
            <label>Type</label>
            <select name="type">
                <?php foreach (['sermon','video','audio','photo','document','bulletin','other'] as $t): ?>
                    <option value="<?= $t ?>" <?= ($item['type'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Catégorie</label>
            <select name="category_id">
                <option value="">—</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (int) ($item['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Département</label>
            <select name="department_id">
                <option value="">—</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= (int) $d['id'] ?>" <?= (int) ($item['department_id'] ?? 0) === (int) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Association</label>
            <select name="association_id">
                <option value="">—</option>
                <?php foreach ($associations as $a): ?>
                    <option value="<?= (int) $a['id'] ?>" <?= (int) ($item['association_id'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>><?= e($a['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Intervenant</label>
            <input type="text" name="speaker" value="<?= e($item['speaker'] ?? '') ?>">
        </div>
        <div>
            <label>URL externe</label>
            <input type="url" name="external_url" value="<?= e($item['external_url'] ?? '') ?>" placeholder="https://youtube.com/...">
        </div>
    </div>
    <label>Description</label>
    <textarea name="description" rows="4"><?= e($item['description'] ?? '') ?></textarea>
    <label>Fichier</label>
    <input type="file" name="file">
    <?php if (!empty($item['file_path'])): ?><p class="muted">Fichier actuel : <?= e($item['file_path']) ?></p><?php endif; ?>
    <label>Miniature</label>
    <input type="file" name="thumbnail" accept="image/*">
    <?php if (!empty($item['thumbnail'])): ?><img src="<?= e($item['thumbnail']) ?>" alt="" class="thumb"><?php endif; ?>
    <label><input type="checkbox" name="is_published" value="1" <?= !empty($item['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
