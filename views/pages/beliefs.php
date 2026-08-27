<?php /** @var array $beliefs */ ?>
<section class="page-hero">
    <div class="container">
        <h1>Croyances fondamentales</h1>
        <p>Les doctrines qui fondent la foi adventiste.</p>
    </div>
</section>
<section class="section">
    <div class="container belief-list">
        <?php foreach ($beliefs as $belief): ?>
            <article class="belief">
                <?php if ($belief['number']): ?><span class="belief-num"><?= (int) $belief['number'] ?></span><?php endif; ?>
                <div>
                    <h2><?= e($belief['title']) ?></h2>
                    <?php if ($belief['summary']): ?><p><?= e($belief['summary']) ?></p><?php endif; ?>
                    <?php if ($belief['body']): ?><div class="prose"><?= nl2br(e($belief['body'])) ?></div><?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (!$beliefs): ?><p class="muted">Contenu à venir — ajoutez les croyances depuis l’administration.</p><?php endif; ?>
    </div>
</section>
