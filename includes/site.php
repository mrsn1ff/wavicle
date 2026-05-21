<?php
// ─── Load .env ────────────────────────────────────────────────
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v);
    }
}

// ─── Base URL from .env ───────────────────────────────────────
// Local:  SITE_BASE=/wavicle
// Live:   SITE_BASE=https://wavicle.canvinfoods.com
$base = rtrim($_ENV['SITE_BASE'] ?? '/wavicle', '/');

// ─── Site Config ──────────────────────────────────────────────
$siteName       = 'Wavicle';
$logoPath       = $base . '/assets/images/WAVICLE POOLS LOGO.png';
$logoDark       = $base . '/assets/images/wavicle_white.png';
$logoLight      = $base . '/assets/images/wavicle_white.png';
$phoneDisplay   = '+91 9560838375';
$phoneHref      = 'tel:9560838375';
$emailDisplay   = 'waviclepools@gmail.com';
$emailHref      = 'mailto:waviclepools@gmail.com';
$addressDisplay = 'New Delhi, India';
$addressHref    = 'https://maps.google.com/?q=New+Delhi,+India';
$social         = ['facebook' => '#', 'twitter' => '#', 'pinterest' => '#', 'instagram' => '#'];

// ─── DB ───────────────────────────────────────────────────────
if (!isset($pdo)) require_once __DIR__ . '/../admin/includes/db.php';

// ─── Nav Categories from DB ───────────────────────────────────
$navProductCats = $pdo->query(
    "SELECT name, slug FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC, id ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);

$navServiceCats = $pdo->query(
    "SELECT name, slug FROM catalog_categories WHERE type='service' AND status=1 ORDER BY sort_order ASC, id ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);

// ─── Nav Children ─────────────────────────────────────────────
$productChildren = [];
foreach ($navProductCats as $c) {
    $productChildren[$c['slug']] = [
        'label' => $c['name'],
        'href'  => $base . '/products/' . urlencode($c['slug']),
    ];
}

$serviceChildren = [];
foreach ($navServiceCats as $c) {
    $serviceChildren[$c['slug']] = [
        'label' => $c['name'],
        'href'  => $base . '/services/' . urlencode($c['slug']),
    ];
}

// ─── Menu ─────────────────────────────────────────────────────
$menuItems = [
    'home'    => ['label' => 'Home',        'href' => $base . '/index.php'],
    'about'   => ['label' => 'About',       'href' => $base . '/about.php'],
    'courses' => ['label' => 'Products',    'href' => $base . '/courses.php',  'children' => $productChildren],
    'pages'   => ['label' => 'Services',    'href' => $base . '/services.php', 'children' => $serviceChildren],
    'news'    => ['label' => 'Latest News', 'href' => $base . '/news.php'],
    'contact' => ['label' => 'Contact',     'href' => $base . '/contact.php'],
];

$pageTitle  = $pageTitle  ?? $siteName;
$activePage = $activePage ?? 'home';
