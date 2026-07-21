<?php /** @var array $stats */ /** @var array $recentMessages */ ?>
<div class="stat-grid">
    <?php foreach ([
        'leaders' => 'Dirigeants',
        'departments' => 'Départements',
        'associations' => 'Associations',
        'posts' => 'Actualités',
        'events' => 'Événements',
        'media' => 'Médias',
        'messages' => 'Messages non lus',
    ] as $key => $label): ?>
        <div class="stat">
            <strong><?= (int) ($stats[$key] ?? 0) ?></strong>
            <span><?= e($label) ?></span>
        </div>
    <?php endforeach; ?>
</div>
<h2>Messages récents</h2>
<table class="admin-table">
    <thead><tr><th>Date</th><th>Nom</th><th>Sujet</th><th>Lu</th></tr></thead>
    <tbody>
    <?php foreach ($recentMessages as $m): ?>
        <tr>
            <td><?= e(format_datetime($m['created_at'])) ?></td>
            <td><?= e($m['name']) ?></td>
            <td><?= e($m['subject']) ?></td>
            <td><?= $m['is_read'] ? 'Oui' : 'Non' ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$recentMessages): ?><tr><td colspan="4">Aucun message.</td></tr><?php endif; ?>
    </tbody>
</table>
