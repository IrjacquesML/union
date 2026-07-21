<?php
/** @var array $unionLeaders */
/** @var array $current */
/** @var array $allLeaders */
?>
<section class="page-hero">
    <div class="container">
        <h1>Direction & Dirigeants</h1>
        <p>Pasteurs et responsables de l’Union du Congo Ouest.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <h2>Direction de l’Union</h2>
        <div class="people-grid">
            <?php foreach ($unionLeaders as $person): ?>
                <article class="person">
                    <div class="person-photo">
                        <?php if (!empty($person['photo'])): ?>
                            <img src="<?= e($person['photo']) ?>" alt="">
                        <?php else: ?>
                            <span><?= e(mb_substr($person['first_name'], 0, 1) . mb_substr($person['last_name'], 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3><a href="/dirigeants/<?= e($person['slug']) ?>"><?= e(trim(($person['title_prefix'] ?? '') . ' ' . $person['first_name'] . ' ' . $person['last_name'])) ?></a></h3>
                    <p><?= e($person['position_title']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section section-alt">
    <div class="container">
        <h2>Tous les dirigeants publiés</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Nom</th><th>Pasteur</th></tr></thead>
                <tbody>
                <?php foreach ($allLeaders as $leader): ?>
                    <tr>
                        <td><a href="/dirigeants/<?= e($leader['slug']) ?>"><?= e(trim(($leader['title_prefix'] ?? '') . ' ' . $leader['first_name'] . ' ' . $leader['last_name'])) ?></a></td>
                        <td><?= !empty($leader['is_pastor']) ? 'Oui' : '—' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
