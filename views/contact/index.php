<?php /** @var string|null $email */ /** @var string|null $phone */ /** @var string|null $address */ ?>
<section class="page-hero"><div class="container"><h1>Contact</h1><p>Écrivez à l’Union du Congo Ouest.</p></div></section>
<section class="section">
    <div class="container split">
        <div>
            <p><?= e((string) $address) ?></p>
            <?php if ($email): ?><p><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p><?php endif; ?>
            <?php if ($phone): ?><p><?= e($phone) ?></p><?php endif; ?>
        </div>
        <form method="post" action="/contact" class="form">
            <?= csrf_field() ?>
            <label>Nom *</label>
            <input type="text" name="name" required value="<?= old('name') ?>">
            <label>Email *</label>
            <input type="email" name="email" required value="<?= old('email') ?>">
            <label>Téléphone</label>
            <input type="text" name="phone" value="<?= old('phone') ?>">
            <label>Sujet *</label>
            <input type="text" name="subject" required value="<?= old('subject') ?>">
            <label>Message *</label>
            <textarea name="message" rows="6" required><?= old('message') ?></textarea>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
</section>
