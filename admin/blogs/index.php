<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $row = $pdo->prepare('SELECT image FROM blogs WHERE id = ?');
    $row->execute([$id]);
    $img = $row->fetchColumn();
    if ($img && strpos($img, 'uploads/') !== false) {
        $abs = realpath(__DIR__ . '/../../') . '/' . ltrim($img, '/');
        if (file_exists($abs)) @unlink($abs);
    }
    $pdo->prepare('DELETE FROM blogs WHERE id = ?')->execute([$id]);
    setFlash('success', 'Blog post deleted successfully.');
    header('Location: index.php'); exit;
}

$blogs = $pdo->query('SELECT id, title, author, status, created_at FROM blogs ORDER BY created_at DESC')->fetchAll();

$adminPageTitle  = 'Blogs';
$adminActivePage = 'blogs';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Blog Posts</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / Blogs</div>
    </div>
    <a href="create.php" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Blog Post</a>
</div>

<?php renderFlash(); ?>

<div class="wv-card">
    <div class="wv-card__header">
        <span class="wv-card__title">All Blog Posts (<?php echo count($blogs); ?>)</span>
    </div>
    <div class="wv-card__body" style="padding:0;">
        <?php if ($blogs): ?>
        <table class="wv-table">
            <thead>
                <tr><th>#</th><th>Title</th><th>Author</th><th>Date</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($blogs as $i => $b): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><strong><?php echo e(substr($b['title'], 0, 60)) . (strlen($b['title']) > 60 ? '…' : ''); ?></strong></td>
                <td><?php echo e($b['author']); ?></td>
                <td style="white-space:nowrap; font-size:12px;"><?php echo date('d M Y', strtotime($b['created_at'])); ?></td>
                <td><span class="wv-badge <?php echo $b['status'] ? 'wv-badge-active' : 'wv-badge-inactive'; ?>"><?php echo $b['status'] ? 'Published' : 'Draft'; ?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $b['id']; ?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $b['id']; ?>" />
                        <button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete this blog post? This cannot be undone.">
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
            <i class="fa fa-newspaper" style="font-size:40px; color:#dee2e6; display:block; margin-bottom:12px;"></i>
            No blog posts yet. <a href="create.php" style="color:#0e3c7d;">Write your first post</a>.
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
