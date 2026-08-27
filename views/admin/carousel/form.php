<?php
/** @var ?array $slide */
$old = \App\Core\Session::get('_old');
$isActive = is_array($old)
    ? !empty($old['is_active'])
    : (!$slide || !empty($slide['is_active']));
?>
<p class="muted">L’image occupe tout le bandeau d’accueil. Format paysage recommandé : 1920 × 1080 px, JPG ou PNG, 8 Mo max.</p>
<form method="post"
      action="<?= $slide ? '/admin/carousel/' . (int) $slide['id'] : '/admin/carousel' ?>"
      enctype="multipart/form-data"
      class="form admin-form carousel-form">
    <?= csrf_field() ?>

    <div class="carousel-form-grid">
        <div>
            <span class="field-label">Image <?= $slide ? '' : '*' ?></span>
            <label class="dropzone" data-dropzone>
                <input type="file"
                       name="image"
                       accept="image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif"
                       <?= $slide ? '' : 'required' ?>
                       data-image-input>
                <img src="<?= e($slide['image_path'] ?? '') ?>"
                     alt=""
                     class="dropzone-preview"
                     data-image-preview
                     <?= empty($slide['image_path']) ? 'hidden' : '' ?>>
                <span class="dropzone-hint" data-dropzone-hint>Cliquez ou déposez une photo ici</span>
            </label>
        </div>
        <div class="carousel-form-fields">
            <label>Titre</label>
            <input type="text" name="title" value="<?= old('title', $slide['title'] ?? '') ?>" placeholder="ex: Bienvenue à l'UCO">

            <label>Sous-titre</label>
            <input type="text" name="subtitle" value="<?= old('subtitle', $slide['subtitle'] ?? '') ?>" placeholder="Court texte sous le titre">

            <label>Lien du bouton (URL)</label>
            <input type="text" name="link_url" value="<?= old('link_url', $slide['link_url'] ?? '') ?>" placeholder="/pages/mission">

            <label>Libellé du bouton</label>
            <input type="text" name="link_label" value="<?= old('link_label', $slide['link_label'] ?? '') ?>" placeholder="En savoir plus">

            <label>Ordre d’affichage</label>
            <input type="number" name="sort_order" value="<?= old('sort_order', (string) ($slide['sort_order'] ?? 0)) ?>">

            <label class="check-inline">
                <input type="checkbox" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?>>
                Visible sur l’accueil
            </label>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $slide ? 'Enregistrer' : 'Ajouter l’image au carousel' ?></button>
        <a href="/admin/carousel" class="btn btn-ghost">Retour</a>
    </div>
</form>
<?php \App\Core\Session::remove('_old'); ?>
