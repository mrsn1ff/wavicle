<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/admin/includes/db.php';

$type = trim($_GET['type'] ?? '');
$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

if (!in_array($type, ['product', 'service'], true)) {
    header('Location: index.php');
    exit;
}

$table = ($type === 'product') ? 'products' : 'services';
$item  = null;

if ($slug !== '') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE `slug` = ? AND `status` = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $item = null;
    }
}
if (!$item && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE `id` = ? AND `status` = 1 LIMIT 1");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $item = null;
    }
}
if (!$item) {
    header('Location: ' . ($type === 'product' ? 'courses.php' : 'index.php'));
    exit;
}

$itemTitle    = !empty($item['title'])       ? $item['title']       : 'Wavicle ' . ucfirst($type);
$itemDesc     = !empty($item['description']) ? $item['description'] : '';
$itemCategory = !empty($item['category'])    ? $item['category']    : '';
$itemImage    = !empty($item['image'])       ? $item['image']       : '';
$itemSlug     = !empty($item['slug'])        ? $item['slug']        : '';

$relatedItems = [];
try {
    if ($itemSlug) {
        $relStmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE `status` = 1 AND `slug` != ? ORDER BY `sort_order` ASC, `id` ASC LIMIT 4");
        $relStmt->execute([$itemSlug]);
    } else {
        $relStmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE `status` = 1 AND `id` != ? ORDER BY `id` ASC LIMIT 4");
        $relStmt->execute([$item['id']]);
    }
    $relatedItems = $relStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $relatedItems = [];
}

$typeLabel  = ($type === 'product') ? 'Product' : 'Service';
$backUrl    = ($type === 'product') ? 'courses.php' : 'index.php';
$backLabel  = ($type === 'product') ? 'All Products' : 'All Services';
$activePage = ($type === 'product') ? 'courses' : 'pages';
$pageTitle  = $itemTitle . ' | Wavicle';

include __DIR__ . '/includes/header.php';

$imgSrc = $itemImage !== ''
    ? htmlspecialchars($itemImage, ENT_QUOTES, 'UTF-8')
    : 'assets/images/courses/course-1-1.jpg';
?>

<style>
    /* ── Item Detail Page Styles ── */
    .id-hero {
        background: linear-gradient(135deg, #051b35 0%, #0e3c7d 60%, #1a5096 100%);
        padding: 140px 0 70px;
        position: relative;
        overflow: hidden;
    }

    .id-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url(assets/images/shapes/water-wave-bg.png);
        background-size: cover;
        background-position: center;
        opacity: .08;
    }

    .id-hero .container {
        position: relative;
        z-index: 1;
    }

    .id-hero-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #59b5e8;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .id-hero-label::before,
    .id-hero-label::after {
        content: '';
        flex: 1;
        max-width: 40px;
        height: 1px;
        background: #59b5e8;
        opacity: .5;
    }

    .id-hero-title {
        font-size: clamp(28px, 5vw, 48px);
        font-weight: 800;
        color: #fff;
        font-family: 'Montserrat', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
    }

    .id-hero-badge {
        display: inline-block;
        margin-top: 16px;
        background: rgba(89, 181, 232, .2);
        color: #59b5e8;
        border: 1px solid rgba(89, 181, 232, .4);
        padding: 5px 18px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-family: 'Montserrat', sans-serif;
    }

    /* ── Layout ── */
    .id-body {
        padding: 70px 0 90px;
        background: #f8f9fb;
    }

    .id-main-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(14, 60, 125, .08);
        margin-bottom: 28px;
    }

    .id-image-wrap {
        position: relative;
        overflow: hidden;
    }

    .id-image-wrap img {
        width: 100%;
        height: 440px;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }

    .id-image-wrap:hover img {
        transform: scale(1.02);
    }

    .id-content {
        padding: 36px 40px 40px;
    }

    .id-section-tag {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .id-section-tag::before {
        content: '';
        width: 32px;
        height: 2px;
        background: #59b5e8;
        border-radius: 2px;
    }

    .id-section-tag span {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #59b5e8;
        font-family: 'Montserrat', sans-serif;
    }

    .id-content-title {
        font-size: 28px;
        font-weight: 800;
        color: #051b35;
        font-family: 'Montserrat', sans-serif;
        text-transform: uppercase;
        margin-bottom: 18px;
        line-height: 1.25;
    }

    .id-content-desc {
        font-size: 15px;
        line-height: 1.9;
        color: #5a6474;
        margin-bottom: 32px;
    }

    .id-btn-group {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        align-items: center;
    }

    /* ── Buttons — consistent thm-btn style ── */
    .id-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0e3c7d;
        color: #fff;
        padding: 14px 32px;
        border-radius: 4px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        text-decoration: none;
        border: 2px solid #0e3c7d;
        transition: background .25s, color .25s, transform .15s;
        cursor: pointer;
    }

    .id-btn-primary:hover {
        background: #092d5e;
        border-color: #092d5e;
        color: #fff;
        transform: translateY(-1px);
    }

    .id-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        color: #0e3c7d;
        padding: 14px 32px;
        border-radius: 4px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        text-decoration: none;
        border: 2px solid #0e3c7d;
        transition: background .25s, color .25s, transform .15s;
        cursor: pointer;
    }

    .id-btn-outline:hover {
        background: #0e3c7d;
        color: #fff;
        transform: translateY(-1px);
    }

    /* ── Sidebar ── */
    .id-info-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(14, 60, 125, .08);
        margin-bottom: 24px;
    }

    .id-info-card-header {
        background: linear-gradient(135deg, #0e3c7d, #1a5096);
        padding: 20px 24px;
    }

    .id-info-card-header h4 {
        font-family: 'Montserrat', sans-serif;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
    }

    .id-info-list {
        padding: 0 24px;
        margin: 0;
        list-style: none;
    }

    .id-info-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f0f3f8;
        font-size: 13px;
    }

    .id-info-list li:last-child {
        border-bottom: none;
    }

    .id-info-key {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: #8a9ab0;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .id-info-val {
        color: #051b35;
        font-weight: 600;
        font-size: 13px;
        text-align: right;
        max-width: 60%;
    }

    .id-status-badge {
        background: #e8f8ee;
        color: #1a7a3c;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
    }

    .id-info-cta {
        padding: 20px 24px 24px;
    }

    .id-info-cta a {
        display: block;
        text-align: center;
        background: #0e3c7d;
        color: #fff;
        padding: 14px;
        border-radius: 4px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        text-decoration: none;
        transition: background .25s;
    }

    .id-info-cta a:hover {
        background: #092d5e;
    }

    /* ── Related ── */
    .id-related-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(14, 60, 125, .08);
    }

    .id-related-header {
        background: linear-gradient(135deg, #0e3c7d, #1a5096);
        padding: 18px 24px;
    }

    .id-related-header h4 {
        font-family: 'Montserrat', sans-serif;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
    }

    .id-related-list {
        padding: 8px 16px 16px;
    }

    .id-related-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 8px;
        border-radius: 8px;
        text-decoration: none;
        border-bottom: 1px solid #f0f3f8;
        transition: background .2s;
    }

    .id-related-item:last-child {
        border-bottom: none;
    }

    .id-related-item:hover {
        background: #f0f6ff;
    }

    .id-related-thumb {
        width: 64px;
        height: 52px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .id-related-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #051b35;
        line-height: 1.4;
        margin-bottom: 3px;
    }

    .id-related-cat {
        font-family: 'Montserrat', sans-serif;
        font-size: 10px;
        font-weight: 700;
        color: #59b5e8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {

        .id-col-main,
        .id-col-side {
            width: 100% !important;
            float: none !important;
        }

        .id-image-wrap img {
            height: 300px;
        }

        .id-content {
            padding: 28px 24px 30px;
        }
    }

    @media (max-width: 576px) {
        .id-image-wrap img {
            height: 220px;
        }

        .id-content-title {
            font-size: 22px;
        }

        .id-btn-group {
            flex-direction: column;
        }

        .id-btn-primary,
        .id-btn-outline {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- ═══ Hero Banner ═══ -->
<div class="id-hero">
    <div class="container">
        <div style="text-align:center;">
            <div class="id-hero-label"><span><?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?> Details</span></div>
            <h1 class="id-hero-title"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <?php if ($type === 'product' && $itemCategory !== ''): ?>
                <span class="id-hero-badge"><?php echo htmlspecialchars($itemCategory, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ═══ Body ═══ -->
<div class="id-body">
    <div class="container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-8 id-col-main" style="margin-bottom:30px;">
                <div class="id-main-card">
                    <div class="id-image-wrap">
                        <img src="<?php echo $imgSrc; ?>"
                            alt="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>"
                            onerror="this.onerror=null; this.src='assets/images/courses/course-1-1.jpg';" />
                    </div>
                    <div class="id-content">
                        <div class="id-section-tag"><span>About This <?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?></span></div>
                        <h2 class="id-content-title"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="id-content-desc"><?php echo nl2br(htmlspecialchars($itemDesc, ENT_QUOTES, 'UTF-8')); ?></p>
                        <div class="id-btn-group">
                            <a href="contact.php" class="id-btn-primary">
                                <?php echo ($type === 'product') ? 'Book This Course' : 'Enquire Now'; ?>
                                <i class="fa fa-arrow-right"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="id-btn-outline">
                                <i class="fa fa-arrow-left"></i>
                                <?php echo htmlspecialchars($backLabel, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 id-col-side">

                <!-- Info Card -->
                <div class="id-info-card">
                    <div class="id-info-card-header">
                        <h4><?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?> Info</h4>
                    </div>
                    <ul class="id-info-list">
                        <li>
                            <span class="id-info-key">Name</span>
                            <span class="id-info-val"><?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                        <?php if ($type === 'product' && $itemCategory !== ''): ?>
                            <li>
                                <span class="id-info-key">Level</span>
                                <span class="id-info-val"><?php echo htmlspecialchars($itemCategory, ENT_QUOTES, 'UTF-8'); ?></span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <span class="id-info-key">Status</span>
                            <span class="id-status-badge">Available</span>
                        </li>
                        <li>
                            <span class="id-info-key">Call Us</span>
                            <a href="tel:6668880000" style="color:#59b5e8; font-weight:700; text-decoration:none; font-size:13px;">666 888 0000</a>
                        </li>
                    </ul>
                    <div class="id-info-cta">
                        <a href="contact.php">Get In Touch</a>
                    </div>
                </div>

                <!-- Related Items -->
                <?php if (!empty($relatedItems)): ?>
                    <div class="id-related-card">
                        <div class="id-related-header">
                            <h4>Other <?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?>s</h4>
                        </div>
                        <div class="id-related-list">
                            <?php foreach ($relatedItems as $r):
                                $rTitle = !empty($r['title'])    ? $r['title']    : 'Item';
                                $rCat   = !empty($r['category']) ? $r['category'] : '';
                                $rImg   = !empty($r['image'])
                                    ? htmlspecialchars($r['image'], ENT_QUOTES, 'UTF-8')
                                    : 'assets/images/courses/course-1-1.jpg';
                                $rUrl   = !empty($r['slug'])
                                    ? 'item-details.php?type=' . urlencode($type) . '&slug=' . urlencode($r['slug'])
                                    : 'item-details.php?type=' . urlencode($type) . '&id=' . (int)$r['id'];
                            ?>
                                <a href="<?php echo $rUrl; ?>" class="id-related-item">
                                    <img class="id-related-thumb"
                                        src="<?php echo $rImg; ?>"
                                        alt="<?php echo htmlspecialchars($rTitle, ENT_QUOTES, 'UTF-8'); ?>"
                                        onerror="this.onerror=null; this.src='assets/images/courses/course-1-1.jpg';" />
                                    <div>
                                        <div class="id-related-name"><?php echo htmlspecialchars($rTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php if ($type === 'product' && $rCat !== ''): ?>
                                            <div class="id-related-cat"><?php echo htmlspecialchars($rCat, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>