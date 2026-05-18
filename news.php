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

$pageTitle  = 'Latest News | Wavicle';
$activePage = 'news';
$fallback   = $base . '/assets/images/blog/blog-1-1.jpg';

$blogs = $pdo->query('SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<style>
    .news-hero {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #051b35 0%, #0e3c7d 60%, #1a5096 100%);
        position: relative;
        overflow: hidden;
    }

    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    .news-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(14, 60, 125, .08);
        text-decoration: none;
        border: 1px solid #e8eef5;
        transition: transform .25s, box-shadow .25s;
        display: block;
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 36px rgba(14, 60, 125, .16);
    }

    .news-card img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .news-card-body {
        padding: 20px;
    }

    .news-card-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: #051b35;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    .news-date-tag {
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
    }

    @media(max-width:991px) {
        .news-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:576px) {
        .news-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero -->
<div class="news-hero">
    <div class="container" style="position:relative;z-index:1;text-align:center;">
        <div style="font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#59b5e8;margin-bottom:12px;font-family:'Montserrat',sans-serif;">From The Blog</div>
        <h1 style="font-family:'Montserrat',sans-serif;font-size:clamp(26px,5vw,44px);font-weight:800;color:#fff;text-transform:uppercase;margin-bottom:0;">News &amp; Articles</h1>
    </div>
</div>

<section style="padding:60px 0 90px;background:#f8f9fb;">
    <div class="container">
        <?php if ($blogs): ?>
            <div class="news-grid">
                <?php foreach ($blogs as $blog):
                    $rawImg = $blog['main_image'] ?? '';
                    $img = !empty($rawImg)
                        ? htmlspecialchars($base . '/' . ltrim($rawImg, '/'), ENT_QUOTES, 'UTF-8')
                        : $fallback;
                    $url = $base . '/news/' . urlencode($blog['slug'] ?? '');
                ?>
                    <a href="<?php echo $url; ?>" class="news-card">
                        <div style="position:relative;overflow:hidden;">
                            <img src="<?php echo $img; ?>"
                                alt="<?php echo htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                onerror="this.onerror=null;this.src='<?php echo $fallback; ?>'" />
                            <div class="news-date-tag"><?php echo date('d M, Y', strtotime($blog['created_at'])); ?></div>
                        </div>
                        <div class="news-card-body">
                            <h3 class="news-card-title"><?php echo htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <div style="font-size:11px;color:#adb5bd;display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                                <span><i class="fa fa-user-circle" style="margin-right:4px;color:#59b5e8;"></i><?php echo htmlspecialchars($blog['author'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div style="font-size:12px;color:#59b5e8;font-weight:700;font-family:'Montserrat',sans-serif;">Read More →</div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:80px 0;color:#6c757d;">
                <i class="fa fa-newspaper" style="font-size:52px;color:#dee2e6;display:block;margin-bottom:16px;"></i>
                <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:16px;">No articles yet.</div>
                <p style="color:#adb5bd;font-size:13px;margin-top:8px;">Articles will appear here once published from admin panel.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>