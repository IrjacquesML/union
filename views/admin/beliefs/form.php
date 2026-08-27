<?php /** @var ?array $belief */ ?>
<form method="post" action="<?= $belief ? '/admin/beliefs/' . (int) $belief['id'] : '/admin/beliefs' ?>" class="form admin-form">
    <?= csrf_field() ?>
    <div class="form-grid">
        <div><label>Numéro</label><input type="number" name="number" min="1" max="28" value="<?= e((string) ($belief['number'] ?? '')) ?>"></div>
        <div><label>Ordre</label><input type="number" name="sort_order" value="<?= e((string) ($belief['sort_order'] ?? 0)) ?>"></div>
    </div>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($belief['title'] ?? '') ?>">
    <label>Résumé</label>
    <textarea name="summary" rows="3"><?= e($belief['summary'] ?? '') ?></textarea>
    <label>Texte</label>
    <textarea name="body" rows="6"><?= e($belief['body'] ?? '') ?></textarea>
    <label><input type="checkbox" name="is_published" value="1" <?= !$belief || !empty($belief['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>
