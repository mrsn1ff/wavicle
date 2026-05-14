<?php
require_once __DIR__ . '/includes/auth.php'; requireLogin();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$adminPageTitle  = 'Dashboard';
$adminActivePage = 'dashboard';

// Stats from correct tables
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE type='product' AND status=1")->fetchColumn();
$totalServices = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE type='service' AND status=1")->fetchColumn();
$totalBlogs    = (int)$pdo->query("SELECT COUNT(*) FROM blogs WHERE status=1")->fetchColumn();
$totalCats     = (int)$pdo->query("SELECT COUNT(*) FROM catalog_categories WHERE status=1")->fetchColumn();
$totalAll      = $totalProducts + $totalServices;

// Recent products from catalog_items
$recentProducts = $pdo->query(
    "SELECT ci.id, ci.title, ci.slug, cc.name as cat_name
     FROM catalog_items ci
     JOIN catalog_categories cc ON cc.id = ci.category_id
     WHERE ci.type = 'product'
     ORDER BY ci.created_at DESC LIMIT 5"
)->fetchAll();

// Recent blogs
$recentBlogs = $pdo->query(
    'SELECT id, title, created_at FROM blogs ORDER BY created_at DESC LIMIT 5'
)->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<?php renderFlash(); ?>

<!-- Stat Cards -->
<div class="wv-stats">
    <div class="wv-stat">
        <div class="wv-stat__icon"><i class="fa fa-layer-group"></i></div>
        <div>
            <div class="wv-stat__num"><?php echo $totalAll; ?></div>
            <div class="wv-stat__label">Total Items</div>
        </div>
    </div>
    <div class="wv-stat">
        <div class="wv-stat__icon"><i class="fa fa-box-open"></i></div>
        <div>
            <div class="wv-stat__num"><?php echo $totalProducts; ?></div>
            <div class="wv-stat__label">Active Products</div>
        </div>
    </div>
    <div class="wv-stat">
        <div class="wv-stat__icon"><i class="fa fa-concierge-bell"></i></div>
        <div>
            <div class="wv-stat__num"><?php echo $totalServices; ?></div>
            <div class="wv-stat__label">Active Services</div>
        </div>
    </div>
    <div class="wv-stat">
        <div class="wv-stat__icon"><i class="fa fa-newspaper"></i></div>
        <div>
            <div class="wv-stat__num"><?php echo $totalBlogs; ?></div>
            <div class="wv-stat__label">Published Blogs</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px;">
    <a href="catalog/products/create.php" class="wv-btn wv-btn-primary" style="justify-content:center; padding:14px;">
        <i class="fa fa-plus"></i> Add New Product
    </a>
    <a href="catalog/services/create.php" class="wv-btn wv-btn-primary" style="justify-content:center; padding:14px;">
        <i class="fa fa-plus"></i> Add New Service
    </a>
    <a href="catalog/blogs/create.php" class="wv-btn wv-btn-primary" style="justify-content:center; padding:14px;">
        <i class="fa fa-plus"></i> Add New Blog
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
    <!-- Recent Products -->
    <div class="wv-card">
        <div class="wv-card__header">
            <span class="wv-card__title"><i class="fa fa-box-open" style="margin-right:8px;color:#59b5e8;"></i>Recent Products</span>
            <a href="catalog/products/index.php" class="wv-btn wv-btn-secondary wv-btn-sm">View All</a>
        </div>
        <div class="wv-card__body" style="padding:0;">
            <?php if($recentProducts): ?>
            <table class="wv-table">
                <thead><tr><th>Title</th><th>Category</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($recentProducts as $p): ?>
                <tr>
                    <td><strong><?php echo e($p['title']); ?></strong></td>
                    <td><span style="background:#e8f4fb;color:#0e3c7d;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700;"><?php echo e($p['cat_name']); ?></span></td>
                    <td><a href="catalog/products/edit.php?id=<?php echo $p['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i></a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="padding:20px;color:#6c757d;font-size:13px;">No products yet. <a href="catalog/products/create.php" style="color:#0e3c7d;font-weight:600;">Add one</a>.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Blogs -->
    <div class="wv-card">
        <div class="wv-card__header">
            <span class="wv-card__title"><i class="fa fa-newspaper" style="margin-right:8px;color:#59b5e8;"></i>Recent Blogs</span>
            <a href="catalog/blogs/index.php" class="wv-btn wv-btn-secondary wv-btn-sm">View All</a>
        </div>
        <div class="wv-card__body" style="padding:0;">
            <?php if($recentBlogs): ?>
            <table class="wv-table">
                <thead><tr><th>Title</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($recentBlogs as $b): ?>
                <tr>
                    <td><?php echo e(strlen($b['title'])>40 ? substr($b['title'],0,40).'…' : $b['title']); ?></td>
                    <td style="white-space:nowrap;font-size:11px;color:#6c757d;"><?php echo date('d M Y', strtotime($b['created_at'])); ?></td>
                    <td><a href="catalog/blogs/edit.php?id=<?php echo $b['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i></a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="padding:20px;color:#6c757d;font-size:13px;">No blogs yet. <a href="catalog/blogs/create.php" style="color:#0e3c7d;font-weight:600;">Add one</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
