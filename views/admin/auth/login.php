<form method="post" action="/admin/login" class="form">
    <h1>Administration UCO</h1>
    <p class="muted">Connectez-vous pour gérer le contenu.</p>
    <?= csrf_field() ?>
    <label>Email</label>
    <input type="email" name="email" required value="<?= old('email') ?>" autofocus>
    <label>Mot de passe</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>
