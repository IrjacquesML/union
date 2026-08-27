<?php
/** @var string $tagline */
/** @var array $slides */
/** @var array $posts */
/** @var array $mediaItems */
/** @var array $galleries */
/** @var array $events */
/** @var array $departments */
/** @var array $leaders */
$mediaTypeLabels = [
    'sermon'   => 'Sermon',
    'video'    => 'Vidéo',
    'audio'    => 'Audio',
    'bulletin' => 'Bulletin',
    'document' => 'Document',
    'photo'    => 'Photo',
];
?>
<section class="hero-carousel" data-carousel aria-label="Diaporama d'accueil">
    <div class="carousel-track">
        <?php foreach ($slides as $index => $slide): ?>
            <article class="carousel-slide<?= $index === 0 ? ' is-active' : '' ?>" data-slide="<?= (int) $index ?>">
                <div class="carousel-media-frame">
                    <img class="carousel-media"
                         src="<?= e($slide['image_path']) ?>"
                         alt="<?= e($slide['title'] ?: '') ?>"
                         sizes="100vw"
                         decoding="async"
                         draggable="false"
                         <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                         onerror="this.hidden=true">
                </div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <div class="container">
                        <?php if (!empty($slide['title'])): ?>
                            <p class="hero-brand"><?= e($slide['title']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($slide['subtitle']) || $tagline): ?>
                            <h1><?= e($slide['subtitle'] ?: $tagline) ?></h1>
                        <?php endif; ?>
                        <div class="hero-actions">
                            <?php if (!empty($slide['link_url'])): ?>
                                <a class="btn btn-primary" href="<?= e($slide['link_url']) ?>">
                                    <?= e($slide['link_label'] ?: 'En savoir plus') ?>
                                </a>
                            <?php endif; ?>
                            <a class="btn btn-outline" href="/dirigeants">La direction</a>
                        </div>
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
        <?php \App\Core\View::partial('partials/adventist-logo', ['size' => 64, 'class' => 'identity-logo']); ?>
        <div>
            <h2>Église Adventiste du 7<sup>e</sup> Jour</h2>
            <p>L’Union du Congo Ouest (UCO) proclame l’Évangile éternel — un peuple de foi, d’espérance et de service à Kinshasa et dans l’Ouest du pays.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <header class="section-head section-head-row">
            <div>
                <h2>Actualités</h2>
                <p>Les dernières nouvelles de l’Union.</p>
            </div>
            <a class="text-link" href="/actualites">Toutes les actualités →</a>
        </header>
        <?php if ($posts): ?>
            <div class="teaser-grid">
                <?php foreach ($posts as $post): ?>
                    <a class="teaser-card" href="/actualites/<?= e($post['slug']) ?>">
                        <?php if (!empty($post['cover_image'])): ?>
                            <img class="teaser-card-media" src="<?= e($post['cover_image']) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <div class="teaser-placeholder">Actualité</div>
                        <?php endif; ?>
                        <div class="teaser-card-body">
                            <time><?= e(format_date($post['published_at'])) ?></time>
                            <h3><?= e($post['title']) ?></h3>
                            <p><?= e(truncate($post['excerpt'] ?: $post['body'], 120)) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="muted">Aucune actualité publiée pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <header class="section-head section-head-row">
            <div>
                <h2>Médias</h2>
                <p>Sermons, vidéos, documents et galeries.</p>
            </div>
            <a class="text-link" href="/mediatheque">Toute la médiathèque →</a>
        </header>
        <?php if ($mediaItems || $galleries): ?>
            <div class="teaser-grid">
                <?php foreach ($mediaItems as $item): ?>
                    <?php
                    $thumb = (string) ($item['thumbnail'] ?? '');
                    if ($thumb === '' && ($yt = youtube_id($item['external_url'] ?? null))) {
                        $thumb = 'https://img.youtube.com/vi/' . $yt . '/hqdefault.jpg';
                    }
                    $typeLabel = $mediaTypeLabels[$item['type'] ?? ''] ?? ucfirst((string) ($item['type'] ?? 'Média'));
                    ?>
                    <a class="teaser-card" href="/mediatheque/<?= e($item['slug']) ?>">
                        <?php if ($thumb !== ''): ?>
                            <img class="teaser-card-media" src="<?= e($thumb) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <div class="teaser-placeholder"><?= e($typeLabel) ?></div>
                        <?php endif; ?>
                        <div class="teaser-card-body">
                            <small><?= e($typeLabel) ?></small>
                            <h3><?= e($item['title']) ?></h3>
                            <p><?= e(truncate($item['description'] ?? '', 110)) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php foreach ($galleries as $gallery): ?>
                    <a class="teaser-card" href="/galeries/<?= e($gallery['slug']) ?>">
                        <?php if (!empty($gallery['cover_image'])): ?>
                            <img class="teaser-card-media" src="<?= e($gallery['cover_image']) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <div class="teaser-placeholder">Galerie</div>
                        <?php endif; ?>
                        <div class="teaser-card-body">
                            <small><?= (int) ($gallery['image_count'] ?? 0) ?> photo(s)</small>
                            <h3><?= e($gallery['title']) ?></h3>
                            <p><?= e(truncate($gallery['description'] ?? '', 110)) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="muted">Aucun média publié pour le moment.</p>
        <?php endif; ?>
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
    <div class="container">
        <header class="section-head section-head-row">
            <div>
                <h2>À venir</h2>
                <p>Les prochains rendez-vous.</p>
            </div>
            <a class="text-link" href="/evenements">Calendrier →</a>
        </header>
        <?php foreach ($events as $event): ?>
            <article class="event-item">
                <time><?= e(format_datetime($event['starts_at'])) ?></time>
                <h3><a href="/evenements/<?= e($event['slug']) ?>"><?= e($event['title']) ?></a></h3>
                <?php if ($event['location']): ?><p><?= e($event['location']) ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (!$events): ?><p class="muted">Aucun événement à venir.</p><?php endif; ?>
    </div>
</section>
