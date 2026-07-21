<?php /** @var array $settings */ /** @var array $messages */ ?>
<form method="post" action="/admin/settings" class="form admin-form">
    <?= csrf_field() ?>
    <?php
    $fields = [
        'site_name' => 'Nom du site',
        'site_tagline' => 'Slogan',
        'contact_email' => 'Email',
        'contact_phone' => 'Téléphone',
        'address' => 'Adresse',
        'social_facebook' => 'Facebook',
        'social_youtube' => 'YouTube',
    ];
    foreach ($fields as $key => $label):
        $val = $settings[$key]['setting_value'] ?? '';
    ?>
        <label><?= e($label) ?></label>
        <?php if ($key === 'address'): ?>
            <textarea name="<?= e($key) ?>" rows="2"><?= e($val) ?></textarea>
        <?php else: ?>
            <input type="text" name="<?= e($key) ?>" value="<?= e($val) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <button class="btn btn-primary">Enregistrer</button>
</form>

<hr>
<h2>Messages de contact</h2>
<table class="admin-table">
    <thead><tr><th>Date</th><th>De</th><th>Sujet</th><th>Message</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($messages as $m): ?>
        <tr class="<?= empty($m['is_read']) ? 'row-unread' : '' ?>">
            <td><?= e(format_datetime($m['created_at'])) ?></td>
            <td><?= e($m['name']) ?><br><small><?= e($m['email']) ?></small></td>
            <td><?= e($m['subject']) ?></td>
            <td><?= e(truncate($m['message'], 80)) ?></td>
            <td>
                <?php if (empty($m['is_read'])): ?>
                    <form method="post" action="/admin/messages/<?= (int) $m['id'] ?>/read"><?= csrf_field() ?><button class="btn btn-ghost">Marquer lu</button></form>
                <?php else: ?>Lu<?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
