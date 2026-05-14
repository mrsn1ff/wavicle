<?php
require_once __DIR__ . '/admin/includes/db.php';

$slug = trim($_GET['slug'] ?? '');
$post = null;

if ($slug) {
    $stmt = $pdo->prepare('SELECT * FROM blogs WHERE slug = ? AND status = 1 LIMIT 1');
    $stmt->execute([$slug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$post) {
    header('Location: news.php');
    exit;
}

$pageTitle  = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . ' | Wavicle';
$activePage = 'news';
include __DIR__ . '/includes/header.php';

// Sidebar: recent posts
$recents = $pdo->query('SELECT id, title, slug, image, created_at FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 4')->fetchAll(PDO::FETCH_ASSOC);

$imgSrc = $post['image'] ? htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8') : 'assets/images/blog/blog-1-1.jpg';
?>

    <!-- Page Title -->
    <section class="course-one__title" style="padding: 140px 0 60px;">
        <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
        <div class="container">
            <div class="block-title text-center">
                <img src="assets/images/shapes/sec-line-1.png" alt="" />
                <p class="text-uppercase">From the Blog</p>
                <h3 class="text-uppercase"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <img src="<?php echo $imgSrc; ?>" alt="" style="width:100%; border-radius:8px; margin-bottom:30px;" />
                    <div style="margin-bottom:20px; color:#888; font-size:14px;">
                        <i class="far fa-user-circle"></i> <?php echo htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8'); ?> &nbsp;&nbsp;
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M, Y', strtotime($post['created_at'])); ?>
                    </div>
                    <div style="line-height:1.9; font-size:15px;">
                        <?php echo $post['content']; // stored as HTML, rendered as-is ?>
                    </div>
                    <div style="margin-top:30px; padding-top:20px; border-top:1px solid #eee;">
                        <a href="news.php" class="thm-btn" style="display:inline-block;">&larr; Back to News</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Recent Posts -->
                    <div style="background:#f5f5f5; padding:25px; border-radius:8px; margin-bottom:30px;">
                        <h4 style="font-family:'Montserrat',sans-serif; margin-bottom:20px; font-size:15px;">Recent Posts</h4>
                        <?php foreach ($recents as $r):
                            if ($r['slug'] === $post['slug']) continue;
                            $rImg = $r['image'] ? htmlspecialchars($r['image'], ENT_QUOTES, 'UTF-8') : 'assets/images/blog/blog-1-1.jpg';
                        ?>
                        <div style="display:flex; gap:10px; margin-bottom:15px;">
                            <img src="<?php echo $rImg; ?>" alt="" style="width:60px; height:50px; object-fit:cover; border-radius:4px; flex-shrink:0;" />
                            <div>
                                <small style="color:#888;"><?php echo date('d M Y', strtotime($r['created_at'])); ?></small>
                                <p style="margin:0;"><a href="news-details.php?slug=<?php echo urlencode($r['slug']); ?>" style="font-size:13px; font-family:'Montserrat',sans-serif; color:#0e3c7d;"><?php echo htmlspecialchars(substr($r['title'], 0, 50), ENT_QUOTES, 'UTF-8'); ?><?php echo strlen($r['title']) > 50 ? '…' : ''; ?></a></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
