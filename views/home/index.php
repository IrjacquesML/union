<?php
/** @var string $tagline */
/** @var array $slides */
/** @var array $posts */
/** @var array $events */
/** @var array $departments */
/** @var array $leaders */
?>
<section class="hero-carousel" data-carousel aria-label="Diaporama d'accueil">
    <div class="carousel-track">
        <?php foreach ($slides as $index => $slide): ?>
            <article class="carousel-slide<?= $index === 0 ? ' is-active' : '' ?>"
                     style="--slide-image: url('<?= e($slide['image_path']) ?>')"
                     data-slide="<?= (int) $index ?>">
                <div class="carousel-overlay"></div>
                <div class="container carousel-content">
                    <?php \App\Core\View::partial('partials/adventist-logo', ['size' => 88, 'class' => 'carousel-logo']); ?>
                    <?php if (!empty($slide['title'])): ?>
                        <p class="hero-brand"><?= e($slide['title']) ?></p>
                    <?php else: ?>
                        <p class="hero-brand">Union du Congo Ouest</p>
                    <?php endif; ?>
                    <h1><?= e($slide['subtitle'] ?: ($tagline ?: 'Adventistes du 7e Jour — foi, mission et communauté.')) ?></h1>
                    <div class="hero-actions">
                        <?php if (!empty($slide['link_url'])): ?>
                            <a class="btn btn-primary" href="<?= e($slide['link_url']) ?>">
                                <?= e($slide['link_label'] ?: 'En savoir plus') ?>
                            </a>
                        <?php else: ?>
                            <a class="btn btn-primary" href="/pages/mission">Notre mission</a>
                        <?php endif; ?>
                        <a class="btn btn-outline" href="/dirigeants">La direction</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if (count($slides) > 1): ?>
        <button type="button" class="carousel-nav carousel-prev" data-carousel-prev aria-label="Slide précédent">‹</button>
        <button type="button" class="carousel-nav carousel-next" data-carousel-next aria-label="Slide suivant">›</button>
        <div class="carousel-dots" data-carousel-dots>
            <?php foreach ($slides as $index => $_): ?>
                <button type="button" class="carousel-dot<?= $index === 0 ? ' is-active' : '' ?>" data-carousel-goto="<?= (int) $index ?>" aria-label="Aller au slide <?= (int) $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section identity-band">
    <div class="container identity-inner">
        <?php \App\Core\View::partial('partials/adventist-logo', ['size' => 72, 'class' => 'identity-logo']); ?>
        <div>
            <h2>Église Adventiste du 7<sup>e</sup> Jour</h2>
            <p>L’Union du Congo Ouest (UCO) proclame l’Évangile éternel — un peuple de foi, d’espérance et de service à Kinshasa et dans l’Ouest du pays.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <header class="section-head">
            <h2>Direction de l’Union</h2>
            <p>Les dirigeants actuellement en mandat.</p>
        </header>
        <?php if ($leaders): ?>
            <div class="people-grid">
                <?php foreach ($leaders as $person): ?>
                    <article class="person">
                        <div class="person-photo">
                            <?php if (!empty($person['photo'])): ?>
                                <img src="<?= e($person['photo']) ?>" alt="">
                            <?php else: ?>
                                <span><?= e(mb_substr($person['first_name'], 0, 1) . mb_substr($person['last_name'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3>
                            <a href="/dirigeants/<?= e($person['slug']) ?>">
                                <?= e(trim(($person['title_prefix'] ?? '') . ' ' . $person['first_name'] . ' ' . $person['last_name'])) ?>
                            </a>
                        </h3>
                        <p><?= e($person['position_title']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="muted">Aucun dirigeant publié pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <header class="section-head">
            <h2>Départements</h2>
            <p>Ministères et services de l’Union.</p>
        </header>
        <div class="link-list">
            <?php foreach ($departments as $dept): ?>
                <a href="/departements/<?= e($dept['slug']) ?>"><?= e($dept['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div>
            <header class="section-head">
                <h2>Actualités</h2>
            </header>
            <?php foreach ($posts as $post): ?>
                <article class="news-item">
                    <time><?= e(format_date($post['published_at'])) ?></time>
                    <h3><a href="/actualites/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
                    <p><?= e(truncate($post['excerpt'] ?: $post['body'], 140)) ?></p>
                </article>
            <?php endforeach; ?>
            <?php if (!$posts): ?><p class="muted">Aucune actualité.</p><?php endif; ?>
            <a class="text-link" href="/actualites">Toutes les actualités →</a>
        </div>
        <div>
            <header class="section-head">
                <h2>À venir</h2>
            </header>
            <?php foreach ($events as $event): ?>
                <article class="event-item">
                    <time><?= e(format_datetime($event['starts_at'])) ?></time>
                    <h3><a href="/evenements/<?= e($event['slug']) ?>"><?= e($event['title']) ?></a></h3>
                    <?php if ($event['location']): ?><p><?= e($event['location']) ?></p><?php endif; ?>
                </article>
            <?php endforeach; ?>
            <?php if (!$events): ?><p class="muted">Aucun événement à venir.</p><?php endif; ?>
            <a class="text-link" href="/evenements">Calendrier →</a>
        </div>
    </div>
</section>
