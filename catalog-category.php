<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/admin/includes/db.php';
// $base already loaded from site.php via header, but load .env if not set
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

$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
    header('Location: ' . $base . '/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM catalog_categories WHERE slug = ? AND status = 1 LIMIT 1');
$stmt->execute([$slug]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$category) {
    header('Location: ' . $base . '/index.php');
    exit;
}

$items = $pdo->prepare('SELECT * FROM catalog_items WHERE category_id = ? AND status = 1 ORDER BY sort_order ASC, id ASC');
$items->execute([$category['id']]);
$items = $items->fetchAll(PDO::FETCH_ASSOC);

$catType    = $category['type'];
$backUrl    = $catType === 'product' ? $base . '/courses.php' : $base . '/index.php';
$activePage = $catType === 'product' ? 'courses' : 'pages';
$pageTitle  = htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') . ' | Wavicle';
$fallback   = $base . '/assets/images/courses/course-1-1.jpg';

include __DIR__ . '/includes/header.php';
?>
<style>
    .cc-hero {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #051b35 0%, #0e3c7d 60%, #1a5096 100%);
        position: relative;
        overflow: hidden;
    }

    .cc-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .cc-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e8eef5;
        text-decoration: none;
        box-shadow: 0 2px 14px rgba(14, 60, 125, .06);
        transition: transform .25s, box-shadow .25s;
        display: block;
    }

    .cc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 32px rgba(14, 60, 125, .14);
    }

    .cc-card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
    }

    .cc-card-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #051b35;
        line-height: 1.4;
        padding: 16px 18px;
    }

    .cc-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #0e3c7d;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 3px;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: .8px;
        text-transform: uppercase;
    }

    @media(max-width:991px) {
        .cc-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:576px) {
        .cc-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="cc-hero">

    <div class="container" style="position:relative;z-index:1;text-align:center;">
        <div style="font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#59b5e8;margin-bottom:12px;font-family:'Montserrat',sans-serif;">
            <?php echo ucfirst($catType); ?> Category
        </div>
        <h1 style="font-family:'Montserrat',sans-serif;font-size:clamp(26px,5vw,44px);font-weight:800;color:#fff;text-transform:uppercase;margin-bottom:14px;">
            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
        </h1>

    </div>
</div>

<section style="padding:60px 0 90px;background:#f8f9fb;">
    <div class="container">
        <div style="font-size:20px;color:#6c757d;margin-bottom:32px;">
            <a href="<?php echo $base; ?>/" style="color:#59b5e8;text-decoration:none;">Home</a> /
            <a href="<?php echo $backUrl; ?>" style="color:#59b5e8;text-decoration:none;">
                <?php echo $catType === 'product' ? 'Products' : 'Services'; ?>
            </a> /
            <strong style="color:#0e3c7d;"><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>

        <?php if ($items): ?>
            <div class="cc-grid">
                <?php foreach ($items as $it):
                    // Fix image path - prepend $base if it's a relative upload path
                    $rawImg = $it['main_image'] ?? '';
                    if (!empty($rawImg)) {
                        $img = $base . '/' . ltrim($rawImg, '/');
                    } else {
                        $img = $fallback;
                    }
                    $img = htmlspecialchars($img, ENT_QUOTES, 'UTF-8');
                    $url = $base . '/' . $catType . 's/' . urlencode($category['slug']) . '/' . urlencode($it['slug'] ?? '');
                ?>
                    <a href="<?php echo $url; ?>" class="cc-card">
                        <div style="position:relative;overflow:hidden;">
                            <img src="<?php echo $img; ?>"
                                alt="<?php echo htmlspecialchars($it['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                onerror="this.onerror=null;this.src='<?php echo $fallback; ?>'"
                                style="width:100%;height:100%;object-fit:cover;display:block;" />

                        </div>
                        <div class="cc-card-title"><?php echo htmlspecialchars($it['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:80px 0;color:#6c757d;">
                <i class="fa fa-box-open" style="font-size:52px;color:#dee2e6;display:block;margin-bottom:16px;"></i>
                <p style="font-family:'Montserrat',sans-serif;font-weight:600;">No items in this category yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>