<?php

declare(strict_types=1);

/**
 * Test complet admin : pages GET + CRUD créations.
 * php bin/probe-admin.php
 */

$base = 'http://127.0.0.1:8000';
$cookieFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'uco_probe_cookies.txt';
@unlink($cookieFile);

function http(string $method, string $url, string $cookieFile, ?array $post = null, bool $follow = true): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => $follow,
        CURLOPT_HEADER         => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_COOKIEJAR      => $cookieFile,
        CURLOPT_COOKIEFILE     => $cookieFile,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_POSTREDIR      => 0, // don't re-POST on redirect
    ]);
    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);
    return [
        'code'    => $code,
        'headers' => substr((string) $raw, 0, $headerSize),
        'body'    => substr((string) $raw, $headerSize),
        'err'     => $err,
    ];
}

function csrfFrom(string $html): ?string
{
    return preg_match('/name="_csrf"\s+value="([^"]+)"/', $html, $m) ? $m[1] : null;
}

function failHint(array $r): string
{
    if ($r['err']) {
        return 'curl: ' . $r['err'];
    }
    if (preg_match('/(Fatal error|Uncaught|Parse error|PDOException|SQLSTATE)(.{0,200})/s', $r['body'], $em)) {
        return preg_replace('/\s+/', ' ', $em[0]);
    }
    if ($r['code'] >= 400) {
        return 'HTTP ' . $r['code'];
    }
    if (str_contains($r['body'], 'name="password"') && str_contains($r['body'], 'Administration UCO')) {
        return 'redirected to login';
    }
    return '';
}

$login = http('GET', $base . '/admin/login', $cookieFile);
$csrf = csrfFrom($login['body']);
if (!$csrf) {
    fwrite(STDERR, "No CSRF on login\n");
    exit(1);
}

$auth = http('POST', $base . '/admin/login', $cookieFile, [
    'email' => 'admin@uco.local',
    'password' => 'password',
    '_csrf' => $csrf,
], false);
// Follow Location manually with GET
if (preg_match('/^Location:\s*(.+)$/mi', $auth['headers'], $lm)) {
    $loc = trim($lm[1]);
    if (!str_starts_with($loc, 'http')) {
        $loc = $base . $loc;
    }
    $auth = http('GET', $loc, $cookieFile);
}
echo 'LOGIN => ' . ($auth['code'] === 200 && !str_contains($auth['body'], 'name="password"') ? 'OK' : 'FAIL') . " HTTP {$auth['code']}\n";

$pages = [
    '/admin',
    '/admin/carousel', '/admin/carousel/create',
    '/admin/leaders', '/admin/leaders/create',
    '/admin/departments', '/admin/departments/create',
    '/admin/associations', '/admin/associations/create',
    '/admin/committees', '/admin/committees/create',
    '/admin/posts', '/admin/posts/create',
    '/admin/events', '/admin/events/create',
    '/admin/media', '/admin/media/create',
    '/admin/pages',
    '/admin/beliefs', '/admin/beliefs/create',
    '/admin/settings',
];

$fail = 0;
foreach ($pages as $path) {
    $r = http('GET', $base . $path, $cookieFile);
    $issue = failHint($r);
    $mark = $issue === '' ? 'OK' : 'FAIL';
    if ($issue) {
        $fail++;
    }
    echo sprintf("%-4s %-36s %s\n", $mark, $path, $issue);
}

// Edit first page
$pagesList = http('GET', $base . '/admin/pages', $cookieFile);
if (preg_match('#/admin/pages/(\d+)/edit#', $pagesList['body'], $m)) {
    $path = '/admin/pages/' . $m[1] . '/edit';
    $r = http('GET', $base . $path, $cookieFile);
    $issue = failHint($r);
    echo sprintf("%-4s %-36s %s\n", $issue === '' ? 'OK' : 'FAIL', $path, $issue);
    if ($issue) {
        $fail++;
    }
}

// Edit first department
$deptList = http('GET', $base . '/admin/departments', $cookieFile);
if (preg_match('#/admin/departments/(\d+)/edit#', $deptList['body'], $m)) {
    $path = '/admin/departments/' . $m[1] . '/edit';
    $r = http('GET', $base . $path, $cookieFile);
    $issue = failHint($r);
    echo sprintf("%-4s %-36s %s\n", $issue === '' ? 'OK' : 'FAIL', $path, $issue);
    if ($issue) {
        $fail++;
    }
}

// Edit first carousel
$carList = http('GET', $base . '/admin/carousel', $cookieFile);
if (preg_match('#/admin/carousel/(\d+)/edit#', $carList['body'], $m)) {
    $path = '/admin/carousel/' . $m[1] . '/edit';
    $r = http('GET', $base . $path, $cookieFile);
    $issue = failHint($r);
    echo sprintf("%-4s %-36s %s\n", $issue === '' ? 'OK' : 'FAIL', $path, $issue);
    if ($issue) {
        $fail++;
    }
}

// CRUD smoke tests
$crud = [
    [
        'name' => 'create committee',
        'get'  => '/admin/committees/create',
        'post' => '/admin/committees',
        'data' => ['name' => 'Comité Test Probe', 'description' => 'x', 'is_active' => '1'],
        'ok'   => '/admin/committees/',
    ],
    [
        'name' => 'create belief',
        'get'  => '/admin/beliefs/create',
        'post' => '/admin/beliefs',
        'data' => ['number' => '99', 'title' => 'Croyance probe', 'summary' => 's', 'body' => 'b', 'sort_order' => '99', 'is_published' => '1'],
        'ok'   => '/admin/beliefs',
    ],
    [
        'name' => 'create event',
        'get'  => '/admin/events/create',
        'post' => '/admin/events',
        'data' => [
            'title' => 'Event Probe ' . time(),
            'description' => 'desc',
            'location' => 'Kinshasa',
            'starts_at' => '2026-08-01T10:00',
            'ends_at' => '2026-08-01T12:00',
            'is_published' => '1',
        ],
        'ok' => '/admin/events',
    ],
    [
        'name' => 'create post',
        'get'  => '/admin/posts/create',
        'post' => '/admin/posts',
        'data' => [
            'title' => 'Post Probe ' . time(),
            'excerpt' => 'ex',
            'body' => 'Contenu test',
            'status' => 'draft',
        ],
        'ok' => '/admin/posts',
    ],
    [
        'name' => 'create leader',
        'get'  => '/admin/leaders/create',
        'post' => '/admin/leaders',
        'data' => [
            'first_name' => 'Jean',
            'last_name' => 'Probe' . time(),
            'is_published' => '1',
        ],
        'ok' => '/admin/leaders/',
    ],
    [
        'name' => 'create association',
        'get'  => '/admin/associations/create',
        'post' => '/admin/associations',
        'data' => [
            'name' => 'Asso Probe ' . time(),
            'association_type_id' => '1',
            'is_active' => '1',
        ],
        'ok' => '/admin/associations',
    ],
    [
        'name' => 'create media',
        'get'  => '/admin/media/create',
        'post' => '/admin/media',
        'data' => [
            'title' => 'Media Probe ' . time(),
            'type' => 'other',
            'description' => 'd',
            'is_published' => '1',
        ],
        'ok' => '/admin/media',
    ],
    [
        'name' => 'update settings',
        'get'  => '/admin/settings',
        'post' => '/admin/settings',
        'data' => [
            'site_name' => 'UCO Test',
            'site_tagline' => 'Tagline probe',
            'contact_email' => 'contact@uco.local',
            'contact_phone' => '+243',
            'address' => 'Kinshasa',
            'social_facebook' => '',
            'social_youtube' => '',
        ],
        'ok' => '/admin/settings',
    ],
];

foreach ($crud as $test) {
    $form = http('GET', $base . $test['get'], $cookieFile);
    $token = csrfFrom($form['body']);
    if (!$token) {
        echo sprintf("%-4s %-36s %s\n", 'FAIL', $test['name'], 'no csrf on form');
        $fail++;
        continue;
    }
    $payload = $test['data'];
    $payload['_csrf'] = $token;
    $resp = http('POST', $base . $test['post'], $cookieFile, $payload, false);
    $loc = '';
    if (preg_match('/^Location:\s*(.+)$/mi', $resp['headers'], $lm)) {
        $loc = trim($lm[1]);
    }
    $ok = $resp['code'] >= 300 && $resp['code'] < 400 && str_contains($loc, $test['ok']);
    $issue = $ok ? '' : ('HTTP ' . $resp['code'] . ' loc=' . $loc . ' ' . failHint([
        'code' => $resp['code'],
        'body' => $resp['body'],
        'err' => $resp['err'],
        'headers' => $resp['headers'],
    ]));
    // Follow to confirm page loads
    if ($ok && $loc !== '') {
        $followUrl = str_starts_with($loc, 'http') ? $loc : $base . $loc;
        $fr = http('GET', $followUrl, $cookieFile);
        $fi = failHint($fr);
        if ($fi) {
            $ok = false;
            $issue = 'follow: ' . $fi;
        }
    }
    if (!$ok) {
        $fail++;
    }
    echo sprintf("%-4s %-36s %s\n", $ok ? 'OK' : 'FAIL', $test['name'], $issue);
}

echo $fail === 0 ? "\nALL OK\n" : "\n$fail FAILURE(S)\n";
exit($fail === 0 ? 0 : 1);
