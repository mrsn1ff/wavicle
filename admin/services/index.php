<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([(int)$_POST['delete_id']]);
    setFlash('success', 'Service deleted successfully.');
    header('Location: index.php'); exit;
}

$services = $pdo->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();

$adminPageTitle  = 'Services';
$adminActivePage = 'services';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Services</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / Services</div>
    </div>
    <a href="create.php" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Service</a>
</div>

<?php renderFlash(); ?>

<div class="wv-card">
    <div class="wv-card__header">
        <span class="wv-card__title">All Services (<?php echo count($services); ?>)</span>
        <small style="color:#6c757d; font-size:12px;">These appear in the "What We Offer" section on the homepage.</small>
    </div>
    <div class="wv-card__body" style="padding:0;">
        <?php if ($services): ?>
        <table class="wv-table">
            <thead>
                <tr><th>#</th><th>Icon Class</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($services as $i => $s): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                    <i class="<?php echo e($s['icon_class']); ?>" style="margin-right:6px; font-size:20px; color:#0e3c7d;"></i>
                    <small style="color:#6c757d;"><?php echo e($s['icon_class']); ?></small>
                </td>
                <td><strong><?php echo e($s['title']); ?></strong><br/><small style="color:#6c757d;"><?php echo e(substr($s['description'], 0, 60)); ?>…</small></td>
                <td><?php echo (int)$s['sort_order']; ?></td>
                <td><span class="wv-badge <?php echo $s['status'] ? 'wv-badge-active' : 'wv-badge-inactive'; ?>"><?php echo $s['status'] ? 'Active' : 'Inactive'; ?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $s['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $s['id']; ?>" />
                        <button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($s['title']); ?>'?">
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
            <i class="fa fa-concierge-bell" style="font-size:40px; color:#dee2e6; display:block; margin-bottom:12px;"></i>
            No services yet. <a href="create.php" style="color:#0e3c7d;">Add your first service</a>.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Icon Reference -->
<div class="wv-card" style="margin-top:24px;">
    <div class="wv-card__header"><span class="wv-card__title">Available Wavicle Icon Classes</span></div>
    <div class="wv-card__body">
        <div style="display:flex; flex-wrap:wrap; gap:16px; font-size:13px;">
            <?php
            $icons = ['scubo-icon-scuba-diving','scubo-icon-aqualung','scubo-icon-swimming','scubo-icon-snorkel','scubo-icon-scuba','scubo-icon-plus-symbol','scubo-icon-checked'];
            foreach ($icons as $ic): ?>
            <div style="text-align:center; padding:12px 16px; background:#f4f6fb; border-radius:8px; border:1px solid #dee2e6;">
                <i class="<?php echo $ic; ?>" style="font-size:26px; color:#0e3c7d; display:block; margin-bottom:6px;"></i>
                <code style="font-size:10px; color:#6c757d;"><?php echo $ic; ?></code>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
