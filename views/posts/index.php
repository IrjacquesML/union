<?php /** @var array $posts */ ?>
<section class="page-hero"><div class="container"><h1>Actualités</h1></div></section>
<section class="section">
    <div class="container">
        <?php foreach ($posts as $post): ?>
            <article class="news-item">
                <time><?= e(format_date($post['published_at'])) ?></time>
                <h2><a href="/actualites/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h2>
                <p><?= e(truncate($post['excerpt'] ?: $post['body'], 180)) ?></p>
            </article>
        <?php endforeach; ?>
        <?php if (!$posts): ?><p class="muted">Aucune actualité.</p><?php endif; ?>
    </div>
</section>
