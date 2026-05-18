<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/admin/includes/db.php';

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

$pageTitle  = 'Products | Wavicle';
$activePage = 'courses';
$fallback   = $base . '/assets/images/courses/course-1-1.jpg';

$categories = $pdo->query(
    "SELECT * FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC, id ASC"
)->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<style>
    .pc-hero {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #051b35 0%, #0e3c7d 60%, #1a5096 100%);
        position: relative;
        overflow: hidden;
    }

    .pc-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .pc-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e8eef5;
        text-decoration: none;
        box-shadow: 0 2px 14px rgba(14, 60, 125, .06);
        transition: transform .25s, box-shadow .25s;
        display: block;
    }

    .pc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 32px rgba(14, 60, 125, .14);
    }

    .pc-card img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .pc-card-body {
        padding: 16px 18px;
    }

    .pc-card-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #051b35;
        text-transform: uppercase;
        letter-spacing: .5px;
        line-height: 1.3;
        margin-bottom: 6px;
    }

    .pc-card-count {
        font-size: 11px;
        color: rgba(14, 60, 125, .6);
        font-weight: 600;
    }

    .pc-tag {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(5, 27, 53, .65) 0%, transparent 55%);
    }

    .pc-tag-text {
        position: absolute;
        bottom: 16px;
        left: 16px;
        right: 16px;
        font-family: 'Montserrat', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: .5px;
        line-height: 1.3;
    }

    @media(max-width:991px) {
        .pc-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:576px) {
        .pc-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero -->
<div class="pc-hero">
    <div class="container" style="position:relative;z-index:1;text-align:center;">
        <div style="font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#59b5e8;margin-bottom:12px;font-family:'Montserrat',sans-serif;">Our Products</div>
        <h1 style="font-family:'Montserrat',sans-serif;font-size:clamp(26px,5vw,44px);font-weight:800;color:#fff;text-transform:uppercase;margin-bottom:0;">Product Categories</h1>
    </div>
</div>

<section style="padding:60px 0 90px;background:#f8f9fb;">
    <div class="container">
        <?php if ($categories): ?>
            <div class="pc-grid">
                <?php foreach ($categories as $cat):
                    $rawImg = $cat['image'] ?? '';
                    $img = !empty($rawImg)
                        ? htmlspecialchars($base . '/' . ltrim($rawImg, '/'), ENT_QUOTES, 'UTF-8')
                        : $fallback;
                    $itemCount = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE category_id={$cat['id']} AND status=1")->fetchColumn();
                    $url = $base . '/products/' . urlencode($cat['slug']);
                ?>
                    <a href="<?php echo $url; ?>" class="pc-card">
                        <div style="position:relative;overflow:hidden;">
                            <img src="<?php echo $img; ?>"
                                alt="<?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                onerror="this.onerror=null;this.src='<?php echo $fallback; ?>'" />
                            <div class="pc-tag"></div>
                            <div class="pc-tag-text"><?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        <div class="pc-card-body">
                            
                            <?php if (!empty($cat['description'])): ?>
                                <p style="margin:8px 0 0;font-size:12px;color:#6c757d;line-height:1.5;">
                                    <?php echo htmlspecialchars(substr($cat['description'], 0, 80), ENT_QUOTES, 'UTF-8'); ?>...
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:80px 0;color:#6c757d;">
                <i class="fa fa-box-open" style="font-size:52px;color:#dee2e6;display:block;margin-bottom:16px;"></i>
                <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:16px;">No product categories yet.</div>
                <p style="color:#adb5bd;font-size:13px;margin-top:8px;">Categories will appear here once added from admin panel.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
