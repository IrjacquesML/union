<?php
/** @var ?array $committee */
/** @var array $leaders */
/** @var array $positions */
?>
<form method="post" action="<?= $committee ? '/admin/committees/' . (int) $committee['id'] : '/admin/committees' ?>" class="form admin-form">
    <?= csrf_field() ?>
    <label>Nom *</label>
    <input type="text" name="name" required value="<?= e($committee['name'] ?? '') ?>">
    <label>Libellé de mandat</label>
    <input type="text" name="term_label" value="<?= e($committee['term_label'] ?? '') ?>" placeholder="ex: Mandat 2022-2027">
    <label>Description</label>
    <textarea name="description" rows="3"><?= e($committee['description'] ?? '') ?></textarea>
    <div class="form-grid">
        <div><label>Début</label><input type="date" name="start_date" value="<?= e($committee['start_date'] ?? '') ?>"></div>
        <div><label>Fin</label><input type="date" name="end_date" value="<?= e($committee['end_date'] ?? '') ?>"></div>
    </div>
    <label><input type="checkbox" name="is_active" value="1" <?= !$committee || !empty($committee['is_active']) ? 'checked' : '' ?>> Actif</label>
    <button class="btn btn-primary">Enregistrer</button>
</form>

<?php if ($committee): ?>
<hr>
<h2>Membres du comité</h2>
<form method="post" action="/admin/committees/<?= (int) $committee['id'] ?>/members" class="form admin-form">
    <?= csrf_field() ?>
    <div class="form-grid">
        <div>
            <label>Dirigeant</label>
            <?php if (empty($leaders)): ?>
                <p class="muted">Créez d’abord un dirigeant pour l’ajouter au comité.</p>
            <?php else: ?>
            <select name="leader_id" required>
                <?php foreach ($leaders as $l): ?>
                    <option value="<?= (int) $l['id'] ?>"><?= e($l['last_name'] . ' ' . $l['first_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
        </div>
        <div>
            <label>Poste</label>
            <select name="position_id" required>
                <?php foreach ($positions as $p): ?>
                    <option value="<?= (int) $p['id'] ?>"><?= e($p['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div><label>Date début</label><input type="date" name="start_date" value="<?= e(date('Y-m-d')) ?>" required></div>
    </div>
    <button class="btn btn-primary" <?= empty($leaders) ? 'disabled' : '' ?>>Ajouter le membre</button>
</form>
<table class="admin-table">
    <thead><tr><th>Membre</th><th>Poste</th><th>Statut</th><th>Période</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($committee['members'] ?? [] as $m): ?>
        <tr>
            <td><?= e(trim(($m['title_prefix'] ?? '') . ' ' . $m['first_name'] . ' ' . $m['last_name'])) ?></td>
            <td><?= e($m['position_title']) ?></td>
            <td><?= $m['status'] === 'current' ? 'Actuel' : 'Ancien' ?></td>
            <td><?= e(format_date($m['start_date'])) ?> — <?= $m['end_date'] ? e(format_date($m['end_date'])) : '…' ?></td>
            <td>
                <?php if ($m['status'] === 'current'): ?>
                    <form method="post" action="/admin/committees/<?= (int) $committee['id'] ?>/members/<?= (int) $m['id'] ?>/end" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="end_date" value="<?= e(date('Y-m-d')) ?>">
                        <button class="link-danger">Marquer ancien</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
