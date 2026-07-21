<?php /** @var ?array $association */ /** @var array $types */ /** @var array $departments */ ?>
<form method="post" action="<?= $association ? '/admin/associations/' . (int) $association['id'] : '/admin/associations' ?>" class="form admin-form">
    <?= csrf_field() ?>
    <label>Nom *</label>
    <input type="text" name="name" required value="<?= e($association['name'] ?? '') ?>">
    <label>Nom court</label>
    <input type="text" name="short_name" value="<?= e($association['short_name'] ?? '') ?>">
    <label>Type *</label>
    <select name="association_type_id" required>
        <?php foreach ($types as $t): ?>
            <option value="<?= (int) $t['id'] ?>" <?= (int) ($association['association_type_id'] ?? 0) === (int) $t['id'] ? 'selected' : '' ?>><?= e($t['label']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Département lié</label>
    <select name="department_id">
        <option value="">—</option>
        <?php foreach ($departments as $d): ?>
            <option value="<?= (int) $d['id'] ?>" <?= (int) ($association['department_id'] ?? 0) === (int) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Description</label>
    <textarea name="description" rows="4"><?= e($association['description'] ?? '') ?></textarea>
    <label>Infos réunion</label>
    <input type="text" name="meeting_info" value="<?= e($association['meeting_info'] ?? '') ?>">
    <div class="form-grid">
        <div><label>Email</label><input type="email" name="email" value="<?= e($association['email'] ?? '') ?>"></div>
        <div><label>Téléphone</label><input type="text" name="phone" value="<?= e($association['phone'] ?? '') ?>"></div>
        <div><label>Ordre</label><input type="number" name="sort_order" value="<?= e((string) ($association['sort_order'] ?? 0)) ?>"></div>
    </div>
    <label><input type="checkbox" name="is_active" value="1" <?= !$association || !empty($association['is_active']) ? 'checked' : '' ?>> Actif</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
