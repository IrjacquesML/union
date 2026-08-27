<?php
/** @var ?array $leader */
/** @var array $positions */
/** @var array $departments */
/** @var array $associations */
/** @var array $committees */
$isEdit = $leader !== null;
?>
<form method="post" action="<?= $isEdit ? '/admin/leaders/' . (int) $leader['id'] : '/admin/leaders' ?>" enctype="multipart/form-data" class="form admin-form">
    <?= csrf_field() ?>
    <div class="form-grid">
        <div>
            <label>Prénom *</label>
            <input type="text" name="first_name" required value="<?= e($leader['first_name'] ?? '') ?>">
        </div>
        <div>
            <label>Nom *</label>
            <input type="text" name="last_name" required value="<?= e($leader['last_name'] ?? '') ?>">
        </div>
        <div>
            <label>Titre (Pr., Pasteur…)</label>
            <input type="text" name="title_prefix" value="<?= e($leader['title_prefix'] ?? '') ?>">
        </div>
        <div>
            <label>Genre</label>
            <select name="gender">
                <option value="">—</option>
                <?php foreach (['M','F','other'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($leader['gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= e($leader['email'] ?? '') ?>">
        </div>
        <div>
            <label>Téléphone</label>
            <input type="text" name="phone" value="<?= e($leader['phone'] ?? '') ?>">
        </div>
        <div>
            <label>Année d’ordination</label>
            <input type="number" name="ordination_year" min="1900" max="2100" value="<?= e((string) ($leader['ordination_year'] ?? '')) ?>">
        </div>
        <div>
            <label>Photo</label>
            <input type="file" name="photo" accept="image/*">
            <?php if (!empty($leader['photo'])): ?><img src="<?= e($leader['photo']) ?>" alt="" class="thumb"><?php endif; ?>
        </div>
    </div>
    <label>Biographie</label>
    <textarea name="bio" rows="5"><?= e($leader['bio'] ?? '') ?></textarea>
    <div class="checks">
        <label><input type="checkbox" name="is_pastor" value="1" <?= !empty($leader['is_pastor']) ? 'checked' : '' ?>> Pasteur</label>
        <label><input type="checkbox" name="is_published" value="1" <?= !$isEdit || !empty($leader['is_published']) ? 'checked' : '' ?>> Publié</label>
    </div>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
    <a href="/admin/leaders" class="btn btn-ghost">Retour</a>
</form>

<?php if ($isEdit): ?>
<hr>
<h2>Affectations & mandats</h2>
<p class="muted">Pour une nomination / transfert : cochez « Clôturer les mandats en cours » — l’historique est conservé, le statut passe à « Ancien dirigeant ».</p>

<form method="post" action="/admin/leaders/<?= (int) $leader['id'] ?>/assign" class="form admin-form">
    <?= csrf_field() ?>
    <div class="form-grid">
        <div>
            <label>Poste *</label>
            <select name="position_id" required>
                <?php foreach ($positions as $p): ?>
                    <option value="<?= (int) $p['id'] ?>"><?= e($p['title']) ?> (<?= e($p['level']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Périmètre *</label>
            <select name="scope_type" id="scope_type">
                <option value="union">Union</option>
                <option value="department">Département</option>
                <option value="association">Association</option>
                <option value="committee">Comité</option>
            </select>
        </div>
        <div data-scope="department">
            <label>Département</label>
            <select name="department_id">
                <option value="">—</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= (int) $d['id'] ?>"><?= e($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div data-scope="association">
            <label>Association</label>
            <select name="association_id">
                <option value="">—</option>
                <?php foreach ($associations as $a): ?>
                    <option value="<?= (int) $a['id'] ?>"><?= e($a['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div data-scope="committee">
            <label>Comité</label>
            <select name="committee_id">
                <option value="">—</option>
                <?php foreach ($committees as $c): ?>
                    <option value="<?= (int) $c['id'] ?>"><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Date de début *</label>
            <input type="date" name="start_date" required value="<?= e(date('Y-m-d')) ?>">
        </div>
    </div>
    <label><input type="checkbox" name="close_previous" value="1" checked> Clôturer les mandats en cours (recommandé lors d’une mutation)</label>
    <button type="submit" class="btn btn-primary">Ajouter / Transférer</button>
</form>

<table class="admin-table">
    <thead><tr><th>Poste</th><th>Périmètre</th><th>Statut</th><th>Période</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leader['assignments'] ?? [] as $a): ?>
        <tr>
            <td><?= e($a['position_title']) ?></td>
            <td><?= e($a['scope_name'] ?? $a['scope_type']) ?></td>
            <td><span class="badge <?= $a['status'] === 'current' ? 'badge-ok' : 'badge-muted' ?>"><?= $a['status'] === 'current' ? 'Actuel' : 'Ancien' ?></span></td>
            <td><?= e(format_date($a['start_date'])) ?> — <?= $a['end_date'] ? e(format_date($a['end_date'])) : '…' ?></td>
            <td>
                <?php if ($a['status'] === 'current'): ?>
                    <form method="post" action="/admin/leaders/<?= (int) $leader['id'] ?>/assignments/<?= (int) $a['id'] ?>/end" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="end_date" value="<?= e(date('Y-m-d')) ?>">
                        <button type="submit" class="link-danger">Clôturer</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
