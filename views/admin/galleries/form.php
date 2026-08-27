<?php /** @var ?array $gallery */ ?>
<form method="post"
      action="<?= $gallery ? '/admin/galleries/' . (int) $gallery['id'] : '/admin/galleries' ?>"
      enctype="multipart/form-data"
      class="form admin-form">
    <?= csrf_field() ?>
    <label>Titre *</label>
    <input type="text" name="title" required value="<?= e($gallery['title'] ?? '') ?>">
    <label>Description</label>
    <textarea name="description" rows="3"><?= e($gallery['description'] ?? '') ?></textarea>
    <div class="form-grid">
        <div>
            <label>Date de l’événement</label>
            <input type="date" name="event_date" value="<?= e($gallery['event_date'] ?? '') ?>">
        </div>
        <div>
            <label>Image de couverture</label>
            <input type="file" name="cover_image" accept="image/*">
            <?php if (!empty($gallery['cover_image'])): ?>
                <img src="<?= e($gallery['cover_image']) ?>" alt="" class="thumb">
            <?php endif; ?>
        </div>
    </div>
    <label><input type="checkbox" name="is_published" value="1" <?= !$gallery || !empty($gallery['is_published']) ? 'checked' : '' ?>> Publié</label>
    <button class="btn btn-primary"><?= $gallery ? 'Enregistrer' : 'Créer' ?></button>
    <a href="/admin/galleries" class="btn btn-ghost">Retour</a>
</form>

<?php if ($gallery): ?>
<hr>
<h2>Photos de la galerie</h2>
<form method="post" action="/admin/galleries/<?= (int) $gallery['id'] ?>/images" enctype="multipart/form-data" class="form admin-form">
    <?= csrf_field() ?>
    <label>Ajouter des images</label>
    <input type="file" name="images[]" accept="image/*" multiple required>
    <label>Légende (optionnel, appliquée aux fichiers de ce lot)</label>
    <input type="text" name="caption" value="">
    <button class="btn btn-primary">Ajouter</button>
</form>
<div class="gallery-admin-grid">
    <?php foreach ($gallery['images'] ?? [] as $img): ?>
        <figure>
            <img src="<?= e($img['file_path']) ?>" alt="<?= e($img['alt_text'] ?? '') ?>">
            <figcaption>
                <?= e($img['caption'] ?: 'Sans légende') ?>
                <form method="post" action="/admin/galleries/<?= (int) $gallery['id'] ?>/images/<?= (int) $img['id'] ?>/delete" onsubmit="return confirm('Retirer cette photo ?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-danger">Supprimer</button>
                </form>
            </figcaption>
        </figure>
    <?php endforeach; ?>
</div>
<?php if (empty($gallery['images'])): ?>
    <p class="muted">Aucune photo pour le moment.</p>
<?php endif; ?>
<?php endif; ?>
