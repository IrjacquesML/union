<?php /** @var array $page */ ?>
<section class="page-hero">
    <div class="container">
        <h1><?= e($page['title']) ?></h1>
        <?php if (!empty($page['subtitle'])): ?>
            <p><?= e($page['subtitle']) ?></p>
        <?php endif; ?>
    </div>
</section>
<section class="section">
    <div class="container prose">
        <?php if (!empty($page['cover_image'])): ?>
            <img class="cover-img" src="<?= e($page['cover_image']) ?>" alt="">
        <?php endif; ?>
        <?= nl2br(e($page['body'])) ?>
    </div>
</section>
<nav class="container page-subnav">
    <a href="/pages/histoire">Histoire</a>
    <a href="/pages/vision">Vision</a>
    <a href="/pages/mission">Mission</a>
    <a href="/croyances">Croyances</a>
</nav>
