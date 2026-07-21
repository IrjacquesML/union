<?php /** @var ?array $department */ ?>
<form method="post" action="<?= $department ? '/admin/departments/' . (int) $department['id'] : '/admin/departments' ?>" class="form admin-form">
    <?= csrf_field() ?>
    <label>Nom *</label>
    <input type="text" name="name" required value="<?= e($department['name'] ?? '') ?>">
    <label>Nom court</label>
    <input type="text" name="short_name" value="<?= e($department['short_name'] ?? '') ?>">
    <label>Description</label>
    <textarea name="description" rows="4"><?= e($department['description'] ?? '') ?></textarea>
    <label>Mission</label>
    <textarea name="mission" rows="3"><?= e($department['mission'] ?? '') ?></textarea>
    <div class="form-grid">
        <div><label>Email</label><input type="email" name="email" value="<?= e($department['email'] ?? '') ?>"></div>
        <div><label>Téléphone</label><input type="text" name="phone" value="<?= e($department['phone'] ?? '') ?>"></div>
        <div><label>Ordre</label><input type="number" name="sort_order" value="<?= e((string) ($department['sort_order'] ?? 0)) ?>"></div>
    </div>
    <label><input type="checkbox" name="is_active" value="1" <?= !$department || !empty($department['is_active']) ? 'checked' : '' ?>> Actif</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
