<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM offerings WHERE id = ?');
$stmt->execute([$id]);
$offering = $stmt->fetch();
if (!$offering) { header('Location: index.php'); exit; }

$errors = [];
$data   = $offering;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']       = trim($_POST['title']       ?? '');
    $data['description'] = trim($_POST['description'] ?? '');
    $data['sort_order']  = (int)($_POST['sort_order'] ?? 0);
    $data['status']      = isset($_POST['status']) ? 1 : 0;

    if (!$data['title'])       $errors[] = 'Title is required.';
    if (!$data['description']) $errors[] = 'Description is required.';

    if (!$errors) {
        $newImg = handleImageUpload('icon_image', 'offerings', $offering['icon_image']);
        $img    = $newImg ?: $offering['icon_image'];
        $pdo->prepare('UPDATE offerings SET title=?, icon_image=?, description=?, sort_order=?, status=? WHERE id=?')
            ->execute([$data['title'], $img, $data['description'], $data['sort_order'], $data['status'], $id]);
        setFlash('success', 'Offering updated successfully.');
        header('Location: index.php'); exit;
    }
}

$adminPageTitle  = 'Edit Offering';
$adminActivePage = 'offerings';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Edit Offering</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / <a href="index.php">What We Offer</a> / Edit</div>
    </div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>

<?php if ($errors): ?>
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;">
    <?php foreach ($errors as $err): ?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err); ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="wv-card">
    <div class="wv-card__header"><span class="wv-card__title">Edit: <?php echo e($offering['title']); ?></span></div>
    <div class="wv-card__body">
        <form method="POST" enctype="multipart/form-data">
            <div class="wv-form-group">
                <label class="wv-label">Title <span class="wv-required">*</span></label>
                <input type="text" name="title" class="wv-input" value="<?php echo e($data['title']); ?>" required />
            </div>
            <div class="wv-form-group">
                <label class="wv-label">Description <span class="wv-required">*</span></label>
                <textarea name="description" class="wv-textarea" required><?php echo e($data['description']); ?></textarea>
            </div>
            <div class="wv-form-row">
                <div class="wv-form-group">
                    <label class="wv-label">Icon Image</label>
                    <?php if ($data['icon_image']): ?>
                    <div style="margin-bottom:10px;">
                        <img id="img_preview" src="../../<?php echo e($data['icon_image']); ?>"
                             style="width:80px; height:80px; object-fit:contain; background:#f0f6ff; border-radius:8px; padding:8px;" />
                    </div>
                    <small style="color:#6c757d; font-size:11px; display:block; margin-bottom:6px;">Upload new image to replace current.</small>
                    <?php else: ?>
                    <div class="wv-img-preview"><img id="img_preview" src="" alt="" style="display:none;" /></div>
                    <?php endif; ?>
                    <input type="file" name="icon_image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;" />
                </div>
                <div class="wv-form-group">
                    <label class="wv-label">Sort Order</label>
                    <input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order']; ?>" min="0" />
                    <div style="margin-top:16px; display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" name="status" id="status" <?php echo $data['status'] ? 'checked' : ''; ?> style="width:18px; height:18px; accent-color:#0e3c7d;" />
                        <label for="status" style="font-size:13px; font-weight:500; cursor:pointer;">Active (visible on website)</label>
                    </div>
                </div>
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Update Offering</button>
                <a href="index.php" class="wv-btn wv-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
