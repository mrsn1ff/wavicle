<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    // Get image to delete
    $row = $pdo->prepare('SELECT image FROM products WHERE id = ?');
    $row->execute([$id]);
    $img = $row->fetchColumn();
    if ($img && strpos($img, 'uploads/') !== false) {
        $abs = realpath(__DIR__ . '/../../') . '/' . $img;
        if (file_exists($abs)) @unlink($abs);
    }
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    setFlash('success', 'Product deleted successfully.');
    header('Location: index.php');
    exit;
}

// Toggle homepage featured
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_homepage'])) {
    $pid     = (int)$_POST['product_id'];
    $current = (int)$pdo->query("SELECT show_on_homepage FROM products WHERE id = $pid")->fetchColumn();
    $pdo->prepare('UPDATE products SET show_on_homepage = ? WHERE id = ?')
        ->execute([$current ? 0 : 1, $pid]);
    setFlash('success', 'Homepage visibility updated.');
    header('Location: index.php');
    exit;
}

$products = $pdo->query('SELECT * FROM products ORDER BY sort_order ASC, id ASC')->fetchAll();

$adminPageTitle  = 'Products';
$adminActivePage = 'products';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Products</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / Products</div>
    </div>
    <a href="create.php" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Product</a>
</div>

<?php renderFlash(); ?>

<div class="wv-card">
    <div class="wv-card__header">
        <span class="wv-card__title">All Products (<?php echo count($products); ?>)</span>
    </div>
    <div class="wv-card__body" style="padding:0;">
        <?php if ($products): ?>
            <table class="wv-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Homepage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $i => $p): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td>
                                <?php if ($p['image']): ?>
                                    <img class="thumb" src="../../<?php echo e($p['image']); ?>" alt="" />
                                <?php else: ?>
                                    <div style="width:60px;height:46px;background:#edf2f5;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#adb5bd;font-size:18px;"><i class="fa fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo e($p['title']); ?></strong></td>
                            <td><?php echo e($p['category']); ?></td>
                            <td><?php echo (int)$p['sort_order']; ?></td>
                            <td>
                                <span class="wv-badge <?php echo $p['status'] ? 'wv-badge-active' : 'wv-badge-inactive'; ?>">
                                    <?php echo $p['status'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="toggle_homepage" value="1" />
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>" />
                                    <button type="submit"
                                        style="background:<?php echo $p['show_on_homepage'] ? '#198754' : '#dee2e6'; ?>;
                   color:<?php echo $p['show_on_homepage'] ? '#fff' : '#6c757d'; ?>;
                   border:none; border-radius:20px; padding:4px 14px; font-size:11px;
                   font-weight:700; cursor:pointer; font-family:'Montserrat',sans-serif;">
                                        <?php echo $p['show_on_homepage'] ? '✓ Featured' : 'Add to Home'; ?>
                                    </button>
                                </form>
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="edit.php?id=<?php echo $p['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>" />
                                    <button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($p['title']); ?>'? This cannot be undone.">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding:40px; text-align:center; color:#6c757d;">
                <i class="fa fa-box-open" style="font-size:40px; color:#dee2e6; display:block; margin-bottom:12px;"></i>
                No products yet. <a href="create.php" style="color:#0e3c7d;">Add your first product</a>.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>