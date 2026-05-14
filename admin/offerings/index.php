<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id  = (int)$_POST['delete_id'];
    $row = $pdo->prepare('SELECT icon_image FROM offerings WHERE id = ?');
    $row->execute([$id]);
    $img = $row->fetchColumn();
    if ($img && strpos($img, 'admin/uploads/') !== false) {
        $abs = realpath(__DIR__ . '/../../') . '/' . ltrim($img, '/');
        if (file_exists($abs)) @unlink($abs);
    }
    $pdo->prepare('DELETE FROM offerings WHERE id = ?')->execute([$id]);
    setFlash('success', 'Offering deleted successfully.');
    header('Location: index.php'); exit;
}

$offerings = $pdo->query('SELECT * FROM offerings ORDER BY sort_order ASC, id ASC')->fetchAll();

$adminPageTitle  = 'What We Offer';
$adminActivePage = 'offerings';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>What We Offer</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / What We Offer</div>
    </div>
    <a href="create.php" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Offering</a>
</div>

<?php renderFlash(); ?>

<div class="wv-card">
    <div class="wv-card__header">
        <span class="wv-card__title">All Offerings (<?php echo count($offerings); ?>)</span>
        <small style="color:#6c757d; font-size:12px;">These appear in the "What We Offer" section on homepage.</small>
    </div>
    <div class="wv-card__body" style="padding:0;">
        <?php if ($offerings): ?>
        <table class="wv-table">
            <thead>
                <tr><th>#</th><th>Icon</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($offerings as $i => $o): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                    <?php if ($o['icon_image']): ?>
                    <img src="../../<?php echo e($o['icon_image']); ?>"
                         style="width:50px; height:50px; object-fit:contain; background:#f0f6ff; border-radius:8px; padding:6px;" />
                    <?php else: ?>
                    <div style="width:50px; height:50px; background:#f0f6ff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#adb5bd; font-size:20px;"><i class="fa fa-image"></i></div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?php echo e($o['title']); ?></strong><br/>
                    <small style="color:#6c757d;"><?php echo e(substr($o['description'], 0, 60)); ?>…</small>
                </td>
                <td><?php echo (int)$o['sort_order']; ?></td>
                <td><span class="wv-badge <?php echo $o['status'] ? 'wv-badge-active' : 'wv-badge-inactive'; ?>"><?php echo $o['status'] ? 'Active' : 'Inactive'; ?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $o['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $o['id']; ?>" />
                        <button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($o['title']); ?>'?">
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
            <i class="fa fa-list" style="font-size:40px; color:#dee2e6; display:block; margin-bottom:12px;"></i>
            No offerings yet. <a href="create.php" style="color:#0e3c7d;">Add your first offering</a>.
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
