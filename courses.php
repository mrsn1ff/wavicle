<?php
$pageTitle  = 'Products | Wavicle';
$activePage = 'courses';
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/admin/includes/db.php';

$categories = $pdo->query(
    "SELECT * FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC, id ASC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="course-one__title" style="padding:140px 0 60px;">
    <div class="course-one__bg" style="background-image:url(assets/images/shapes/water-wave-bg.png)"></div>
    <div class="container">
        <div class="block-title text-center">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Our Products</p>
            <h3 class="text-uppercase">Product Categories</h3>
        </div>
    </div>
</section>

<section style="padding:60px 0 90px; background:#f8f9fb;">
    <div class="container">
        <?php if ($categories): ?>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px;">
            <?php foreach ($categories as $cat):
                $imgSrc = !empty($cat['image'])
                    ? htmlspecialchars($cat['image'], ENT_QUOTES, 'UTF-8')
                    : 'assets/images/courses/course-1-1.jpg';
                $itemCount = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE category_id={$cat['id']} AND status=1")->fetchColumn();
            ?>
            <a href="/wavicle_v5/products/<?php echo urlencode($cat['slug']); ?>"
               style="display:block;background:#fff;border-radius:12px;overflow:hidden;
                      box-shadow:0 2px 16px rgba(14,60,125,.08);text-decoration:none;
                      border:1px solid #e8eef5;transition:transform .25s,box-shadow .25s;"
               onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 10px 36px rgba(14,60,125,.16)';"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 16px rgba(14,60,125,.08)';">
                <div style="position:relative;overflow:hidden;">
                    <img src="<?php echo $imgSrc; ?>"
                         alt="<?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>"
                         onerror="this.onerror=null; this.src='/wavicle_v5/assets/images/courses/course-1-1.jpg'"'"
                         style="width:100%;height:240px;object-fit:cover;display:block;" />
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(5,27,53,.6) 0%,transparent 60%);"></div>
                    <div style="position:absolute;bottom:16px;left:16px;right:16px;">
                        <div style="font-family:'Montserrat',sans-serif;font-size:15px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:.5px;line-height:1.3;">
                            <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div style="font-size:11px;color:rgba(255,255,255,.7);margin-top:4px;font-weight:600;">
                            <?php echo $itemCount; ?> Product<?php echo $itemCount!=1?'s':''; ?>
                        </div>
                    </div>
                </div>
                <div style="padding:16px 20px;">
                    <?php if ($cat['description']): ?>
                    <p style="margin:0;font-size:12px;color:#6c757d;line-height:1.5;">
                        <?php echo htmlspecialchars(substr($cat['description'],0,80), ENT_QUOTES, 'UTF-8'); ?>...
                    </p>
                    <?php else: ?>
                    <span style="font-size:12px;color:#59b5e8;font-weight:700;font-family:'Montserrat',sans-serif;">View All →</span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:80px 0;color:#6c757d;">
            <i class="fa fa-box-open" style="font-size:52px;color:#dee2e6;display:block;margin-bottom:16px;"></i>
            <div style="font-family:'Montserrat',sans-serif;font-weight:700;">No product categories yet.</div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
@media(max-width:991px){section div[style*="grid-template-columns:repeat(3"]{grid-template-columns:repeat(2,1fr)!important;}}
@media(max-width:576px){section div[style*="grid-template-columns:repeat(3"]{grid-template-columns:1fr!important;}}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
