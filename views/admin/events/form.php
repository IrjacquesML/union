<?php /** @var ?array $event */ /** @var array $departments */ /** @var array $associations */ ?>
<form method="post" action="<?= $event ? '/admin/events/' . (int) $event['id'] : '/admin/events' ?>" class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($event['title'] ?? '') ?>">
    <label>Description</label>
    <textarea name="description" rows="4"><?= e($event['description'] ?? '') ?></textarea>
    <label>Lieu</label>
    <input type="text" name="location" value="<?= e($event['location'] ?? '') ?>">
    <div class="form-grid">
        <div><label>Début *</label><input type="datetime-local" name="starts_at" required value="<?= e(isset($event['starts_at']) ? date('Y-m-d\TH:i', strtotime($event['starts_at'])) : '') ?>"></div>
        <div><label>Fin</label><input type="datetime-local" name="ends_at" value="<?= e(isset($event['ends_at']) && $event['ends_at'] ? date('Y-m-d\TH:i', strtotime($event['ends_at'])) : '') ?>"></div>
        <div>
            <label>Département</label>
            <select name="department_id"><option value="">—</option>
                <?php foreach ($departments as $d): ?><option value="<?= (int) $d['id'] ?>" <?= (int) ($event['department_id'] ?? 0) === (int) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Association</label>
            <select name="association_id"><option value="">—</option>
                <?php foreach ($associations as $a): ?><option value="<?= (int) $a['id'] ?>" <?= (int) ($event['association_id'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>><?= e($a['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <label><input type="checkbox" name="is_all_day" value="1" <?= !empty($event['is_all_day']) ? 'checked' : '' ?>> Journée entière</label>
    <label><input type="checkbox" name="is_published" value="1" <?= !empty($event['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
