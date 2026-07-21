<?php /** @var array $post */ ?>
<article>
    <section class="page-hero"><div class="container">
        <time><?= e(format_date($post['published_at'])) ?></time>
        <h1><?= e($post['title']) ?></h1>
    </div></section>
    <section class="section"><div class="container prose"><?= nl2br(e($post['body'])) ?></div></section>
</article>
