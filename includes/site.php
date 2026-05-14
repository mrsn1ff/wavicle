<?php
$base = '/wavicle_v5';
$siteName       = 'Wavicle';
$logoPath       = $base . '/assets/images/WAVICLE POOLS LOGO.png';
$logoDark       = $base . '/assets/images/wavicle_white.png';
$logoLight      = $base . '/assets/images/logo-3-1.png';
$phoneDisplay   = '9560838375';
$phoneHref      = 'tel:9560838375';
$emailDisplay   = 'hello@wavicle.com';
$emailHref      = 'mailto:hello@wavicle.com';
$addressDisplay = 'New Delhi, India';
$addressHref    = 'https://maps.google.com/?q=New+Delhi,+India';
$social = ['facebook' => '#', 'twitter' => '#', 'pinterest' => '#', 'instagram' => '#'];

if (!isset($pdo)) require_once __DIR__ . '/../admin/includes/db.php';

// Nav: Product Categories
$navProductCats = $pdo->query(
    "SELECT name,slug FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC,id ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);

// Nav: Service Categories
$navServiceCats = $pdo->query(
    "SELECT name,slug FROM catalog_categories WHERE type='service' AND status=1 ORDER BY sort_order ASC,id ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);

// Build nav children — Clean URLs
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

$menuItems = [
    'home'    => ['label' => 'Home',        'href' => $base . '/index.php'],
    'about'   => ['label' => 'About',       'href' => $base . '/about.php'],
    'courses' => ['label' => 'Products',    'href' => $base . '/courses.php',  'children' => $productChildren],
    'pages'   => ['label' => 'Services',    'href' => '#',            'children' => $serviceChildren],
    'news'    => ['label' => 'Latest News', 'href' => $base . '/news.php'],
    'contact' => ['label' => 'Contact',     'href' => $base . '/contact.php'],
];

$pageTitle  = $pageTitle  ?? $siteName;
$activePage = $activePage ?? 'home';
