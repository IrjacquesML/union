<?php /** @var array $events */ ?>
<section class="page-hero"><div class="container"><h1>Événements</h1></div></section>
<section class="section">
    <div class="container">
        <?php foreach ($events as $event): ?>
            <article class="event-item">
                <time><?= e(format_datetime($event['starts_at'])) ?></time>
                <h2><a href="/evenements/<?= e($event['slug']) ?>"><?= e($event['title']) ?></a></h2>
                <?php if ($event['location']): ?><p><?= e($event['location']) ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (!$events): ?><p class="muted">Aucun événement.</p><?php endif; ?>
    </div>
</section>
