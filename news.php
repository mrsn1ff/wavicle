<?php
$pageTitle  = 'Latest News | Wavicle';
$activePage = 'news';
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/admin/includes/db.php';

$blogs = $pdo->query('SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="course-one__title" style="padding:140px 0 60px;">
    <div class="course-one__bg" style="background-image:url(assets/images/shapes/water-wave-bg.png)"></div>
    <div class="container">
        <div class="block-title text-center">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">From The Blog</p>
            <h3 class="text-uppercase">News &amp; Articles</h3>
        </div>
    </div>
</section>

<section style="padding:60px 0 90px; background:#f8f9fb;">
    <div class="container">
        <?php if($blogs): ?>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px;">
            <?php foreach($blogs as $blog):
                $img = !empty($blog['main_image']) ? htmlspecialchars($blog['main_image'],ENT_QUOTES,'UTF-8') : 'assets/images/blog/blog-1-1.jpg';
                $url = '/wavicle_v5/news/' . urlencode($blog['slug']);
            ?>
            <a href="<?php echo $url;?>"
               style="display:block;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(14,60,125,.08);text-decoration:none;border:1px solid #e8eef5;transition:transform .25s,box-shadow .25s;"
               onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 10px 36px rgba(14,60,125,.16)';"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 16px rgba(14,60,125,.08)';">
                <div style="position:relative;overflow:hidden;">
                    <img src="<?php echo $img;?>" alt="<?php echo htmlspecialchars($blog['title'],ENT_QUOTES,'UTF-8');?>"
                         onerror="this.src='assets/images/blog/blog-1-1.jpg'"
                         style="width:100%;height:220px;object-fit:cover;display:block;transition:transform .35s;"/>
                    <div style="position:absolute;top:12px;left:12px;background:#0e3c7d;color:#fff;font-size:10px;font-weight:700;padding:4px 12px;border-radius:3px;font-family:'Montserrat',sans-serif;">
                        <?php echo date('d M, Y', strtotime($blog['created_at'])); ?>
                    </div>
                </div>
                <div style="padding:20px;">
                    <h3 style="font-family:'Montserrat',sans-serif;font-size:15px;font-weight:800;color:#051b35;line-height:1.4;margin-bottom:10px;">
                        <?php echo htmlspecialchars($blog['title'],ENT_QUOTES,'UTF-8');?>
                    </h3>
                    <div style="font-size:11px;color:#adb5bd;display:flex;align-items:center;gap:12px;">
                        <span><i class="fa fa-user-circle" style="margin-right:4px;color:#59b5e8;"></i><?php echo htmlspecialchars($blog['author']??'Admin',ENT_QUOTES,'UTF-8');?></span>
                    </div>
                    <div style="margin-top:14px;font-size:12px;color:#59b5e8;font-weight:700;font-family:'Montserrat',sans-serif;">Read More →</div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
        <?php else:?>
        <div style="text-align:center;padding:80px 0;color:#6c757d;">
            <i class="fa fa-newspaper" style="font-size:52px;color:#dee2e6;display:block;margin-bottom:16px;"></i>
            <div style="font-family:'Montserrat',sans-serif;font-weight:700;">No articles yet.</div>
        </div>
        <?php endif;?>
    </div>
</section>

<style>
@media(max-width:991px){section div[style*="grid-template-columns:repeat(3"]{grid-template-columns:repeat(2,1fr)!important;}}
@media(max-width:576px){section div[style*="grid-template-columns:repeat(3"]{grid-template-columns:1fr!important;}}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
