<?php /** @var ?array $slide */ ?>
<form method="post"
      action="<?= $slide ? '/admin/carousel/' . (int) $slide['id'] : '/admin/carousel' ?>"
      enctype="multipart/form-data"
      class="form admin-form">
    <?= csrf_field() ?>

    <label>Titre</label>
    <input type="text" name="title" value="<?= e($slide['title'] ?? '') ?>" placeholder="ex: Bienvenue à l'UCO">

    <label>Sous-titre</label>
    <input type="text" name="subtitle" value="<?= e($slide['subtitle'] ?? '') ?>" placeholder="Court texte affiché sous le titre">

    <label>Image <?= $slide ? '' : '*' ?></label>
    <input type="file" name="image" accept="image/*" <?= $slide ? '' : 'required' ?>>
    <?php if (!empty($slide['image_path'])): ?>
        <img src="<?= e($slide['image_path']) ?>" alt="" class="carousel-preview">
    <?php endif; ?>

    <div class="form-grid">
        <div>
            <label>Lien (URL)</label>
            <input type="text" name="link_url" value="<?= e($slide['link_url'] ?? '') ?>" placeholder="/pages/mission">
        </div>
        <div>
            <label>Libellé du bouton</label>
            <input type="text" name="link_label" value="<?= e($slide['link_label'] ?? '') ?>" placeholder="En savoir plus">
        </div>
        <div>
            <label>Ordre d’affichage</label>
            <input type="number" name="sort_order" value="<?= e((string) ($slide['sort_order'] ?? 0)) ?>">
        </div>
    </div>

    <label>
        <input type="checkbox" name="is_active" value="1" <?= !$slide || !empty($slide['is_active']) ? 'checked' : '' ?>>
        Actif (visible sur l’accueil)
    </label>

    <button type="submit" class="btn btn-primary"><?= $slide ? 'Enregistrer' : 'Ajouter au carousel' ?></button>
    <a href="/admin/carousel" class="btn btn-ghost">Retour</a>
</form>
