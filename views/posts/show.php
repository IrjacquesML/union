<?php /** @var array $post */ ?>
<article>
    <section class="page-hero"><div class="container">
        <time><?= e(format_date($post['published_at'])) ?></time>
        <h1><?= e($post['title']) ?></h1>
    </div></section>
    <section class="section"><div class="container prose">
        <?php if (!empty($post['cover_image'])): ?>
            <img class="cover-img" src="<?= e($post['cover_image']) ?>" alt="">
        <?php endif; ?>
        <?= nl2br(e($post['body'])) ?>
    </div></section>
</article>
