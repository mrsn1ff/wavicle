<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/admin/includes/db.php';

// Load $base from .env if not already set
if (!isset($base)) {
    $envFile = __DIR__ . '/.env';
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_contains($line, '=') && $line[0] !== '#') {
                [$k, $v] = explode('=', $line, 2);
                $_ENV[trim($k)] = trim($v);
            }
        }
    }
    $base = rtrim($_ENV['SITE_BASE'] ?? '', '/');
}

$slug    = trim($_GET['slug'] ?? '');
$catSlug = trim($_GET['cat']  ?? '');

if (!$slug) {
    header('Location: ' . $base . '/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT ci.*, cc.name as cat_name, cc.slug as cat_slug, cc.type as cat_type FROM catalog_items ci JOIN catalog_categories cc ON cc.id = ci.category_id WHERE ci.slug = ? AND ci.status = 1 LIMIT 1');
$stmt->execute([$slug]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$item) {
    header('Location: ' . $base . '/index.php');
    exit;
}

$itemTitle = !empty($item['title'])       ? $item['title']       : '';
$itemDesc  = !empty($item['description']) ? $item['description'] : '';
$itemImg   = !empty($item['main_image'])  ? $item['main_image']  : '';
$catName   = !empty($item['cat_name'])    ? $item['cat_name']    : '';
$catSlug   = !empty($item['cat_slug'])    ? $item['cat_slug']    : $catSlug;
$catType   = !empty($item['cat_type'])    ? $item['cat_type']    : 'product';

$sections = $pdo->prepare('SELECT * FROM catalog_sections WHERE item_id = ? ORDER BY sort_order ASC, id ASC');
$sections->execute([$item['id']]);
$sections = $sections->fetchAll(PDO::FETCH_ASSOC);

$related = $pdo->prepare('SELECT * FROM catalog_items WHERE category_id = ? AND status = 1 AND id != ? ORDER BY sort_order ASC, id ASC LIMIT 6');
$related->execute([$item['category_id'], $item['id']]);
$related = $related->fetchAll(PDO::FETCH_ASSOC);

$catUrl      = $base . '/' . $catType . 's/' . urlencode($catSlug);
$activePage  = $catType === 'product' ? 'courses' : 'pages';
$pageTitle   = htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8') . ' | Wavicle';
$fallbackImg = $base . '/assets/images/courses/course-1-1.jpg';

// Fix image path - prepend $base for relative upload paths
if (!empty($itemImg)) {
    $imgSrc = htmlspecialchars($base . '/' . ltrim($itemImg, '/'), ENT_QUOTES, 'UTF-8');
} else {
    $imgSrc = $fallbackImg;
}

include __DIR__ . '/includes/header.php';
?>

<style>
    .ci-hero {
        background: linear-gradient(135deg, #051b35 0%, #0e3c7d 60%, #1a5096 100%);
        padding: 110px 0 30px;
    }

    .ci-hero-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #59b5e8;
        margin-bottom: 10px;
        font-family: 'Montserrat', sans-serif;
    }

    .ci-hero-title {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(22px, 4vw, 36px);
        font-weight: 800;
        color: #fff;
        text-transform: uppercase;
        margin: 0 0 10px;
    }

    .ci-breadcrumb {
        font-size: 12px;
        color: rgba(255, 255, 255, .5);
    }

    .ci-breadcrumb a {
        color: #59b5e8;
        text-decoration: none;
    }

    .ci-top {
        background: #fff;
    }

    .ci-top-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-width: 1200px;
        margin: 0 auto;
    }

    .ci-img-col {
        overflow: hidden;
        height: 560px;
    }

    .ci-img-col img {
        width: 100%;
        height: 100%;
        padding: 100px 0 30px 0;
        object-fit: contain;
    }

    .ci-desc-col {
        padding: 0 52px 50px 52px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
        max-height: 560px;
    }

    .ci-item-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 24px;
        font-weight: 800;
        padding: 50px 0 0 0;
        color: #051b35;
        text-transform: uppercase;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .ci-desc-text {
        font-size: 14px;
        line-height: 1.95;
        color: #5a6474;
        text-align: justify;
    }

    .ci-desc-text p {
        margin-bottom: 12px;
    }

    .ci-desc-text strong {
        color: #051b35;
        font-weight: 700;
    }

    .ci-desc-text ul {
        padding-left: 20px;
        margin-bottom: 12px;
    }

    .ci-desc-text li {
        margin-bottom: 6px;
    }

    .ci-sections {
        /* padding: 50px 0 80px; */
        background: #fff;
    }

    .ci-sec-block {
        padding: 36px 0;
        border-top: 1px solid #f0f3f8;
    }

    .ci-sec-heading {
        font-family: 'Montserrat', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: #051b35;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ci-sec-heading::before {
        content: '';
        width: 4px;
        height: 22px;
        background: #59b5e8;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .ci-sec-text {
        font-size: 14px;
        line-height: 1.95;
        color: #5a6474;
        text-align: justify;
    }

    .ci-sec-text p {
        margin-bottom: 12px;
    }

    .ci-sec-text strong {
        color: #051b35;
        font-weight: 700;
    }

    .ci-sec-text ul {
        padding-left: 20px;
        margin-bottom: 12px;
    }

    .ci-sec-text li {
        margin-bottom: 6px;
    }

    .ci-rel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 28px;
    }

    .ci-rel-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e8eef5;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
        display: block;
    }

    .ci-rel-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 22px rgba(14, 60, 125, .12);
    }

    .ci-rel-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .ci-rel-body {
        padding: 14px 16px;
    }

    .ci-rel-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #051b35;
        line-height: 1.4;
    }

    @media(max-width:991px) {
        .ci-top-grid {
            grid-template-columns: 1fr;
        }

        .ci-img-col img {
            width: 100%;
            height: 85%;
        }

        .ci-desc-col {
            padding: 36px 28px;
            max-height: none;
        }

        .ci-rel-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:576px) {
        .ci-desc-col {
            padding: 15px !important;
        }

        .ci-item-title {
            font-size: 18px;
            padding: 0 !important;
        }

        .ci-rel-grid {
            grid-template-columns: 1fr;
        }
        
    }
</style>

<div class="ci-hero">
    <div class="container">
        <div class="ci-hero-label"><?php echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8'); ?></div>
        <h1 class="ci-hero-title"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <div class="ci-breadcrumb">
            <a href="<?php echo $base; ?>/">Home</a> /
            <a href="<?php echo $catUrl; ?>"><?php echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8'); ?></a> /
            <span style="color:rgba(255,255,255,.8);"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>
</div>

<div class="ci-top">
    <div class="ci-top-grid">
        <div class="ci-img-col">
            <img src="<?php echo $imgSrc; ?>"
                alt="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>"
                onerror="this.onerror=null;this.src='<?php echo $fallbackImg; ?>'" />
        </div>
        <div class="ci-desc-col">
            <h2 class="ci-item-title"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
            <?php if ($itemDesc): ?><div class="ci-desc-text"><?php echo $itemDesc; ?></div><?php endif; ?>
            <div style="margin-top:28px;">
                <a href="<?php echo $base; ?>/contact.php" class="thm-btn">Contact Us</a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($sections)): ?>
    <div class="ci-sections">
        <div class="container">
            <?php foreach ($sections as $sec): ?>
                <div class="ci-sec-block">
                    <?php if (!empty($sec['heading'])): ?>
                        <h3 class="ci-sec-heading"><?php echo htmlspecialchars($sec['heading'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <?php endif; ?>
                    <div class="ci-sec-text"><?php echo $sec['content']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($related)): ?>
    <div style="padding:60px 0;background:#f8f9fb;">
        <div class="container">
            <div style="text-align:center;margin-bottom:28px;">
                <div style="font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#59b5e8;font-family:'Montserrat',sans-serif;">More From</div>
                <h3 style="font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;color:#051b35;text-transform:uppercase;margin-top:6px;"><?php echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>
            <div class="ci-rel-grid">
                <?php foreach ($related as $r):
                    $rRawImg = $r['main_image'] ?? '';
                    $rImg = !empty($rRawImg)
                        ? htmlspecialchars($base . '/' . ltrim($rRawImg, '/'), ENT_QUOTES, 'UTF-8')
                        : $fallbackImg;
                    $rTitle = !empty($r['title']) ? $r['title'] : '';
                    $rUrl   = $base . '/' . $catType . 's/' . urlencode($catSlug) . '/' . urlencode($r['slug'] ?? '');
                ?>
                    <a href="<?php echo $rUrl; ?>" class="ci-rel-card">
                        <img src="<?php echo $rImg; ?>"
                            alt="<?php echo htmlspecialchars($rTitle, ENT_QUOTES, 'UTF-8'); ?>"
                            onerror="this.onerror=null;this.src='<?php echo $fallbackImg; ?>'" />
                        <div class="ci-rel-body">
                            <div class="ci-rel-title"><?php echo htmlspecialchars($rTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                            <div style="font-size:11px;color:#59b5e8;font-weight:600;margin-top:4px;font-family:'Montserrat',sans-serif;">View Details →</div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>