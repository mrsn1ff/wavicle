<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM blogs WHERE id = ?');
$stmt->execute([$id]);
$blog = $stmt->fetch();
if (!$blog) { header('Location: index.php'); exit; }

$errors = [];
$data = $blog;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']   = trim($_POST['title']   ?? '');
    $data['excerpt'] = trim($_POST['excerpt'] ?? '');
    $data['content'] = trim($_POST['content'] ?? '');
    $data['author']  = trim($_POST['author']  ?? 'Admin');
    $data['status']  = isset($_POST['status']) ? 1 : 0;

    if (!$data['title'])   $errors[] = 'Title is required.';
    if (!$data['excerpt']) $errors[] = 'Excerpt is required.';
    if (!$data['content']) $errors[] = 'Content is required.';

    if (!$errors) {
        // Re-slug only if title changed
        $slug = $blog['slug'];
        if ($data['title'] !== $blog['title']) {
            $slug = makeSlug($data['title']);
            $base = $slug; $i = 1;
            while (true) {
                $chk = $pdo->prepare('SELECT COUNT(*) FROM blogs WHERE slug = ? AND id != ?');
                $chk->execute([$slug, $id]);
                if ((int)$chk->fetchColumn() === 0) break;
                $slug = $base . '-' . (++$i);
            }
        }

        $newImage = handleImageUpload('image', 'blogs', $blog['image']);
        $imagePath = $newImage ?: $blog['image'];

        $stmt = $pdo->prepare('UPDATE blogs SET title=?, slug=?, excerpt=?, content=?, image=?, author=?, status=? WHERE id=?');
        $stmt->execute([$data['title'], $slug, $data['excerpt'], $data['content'], $imagePath, $data['author'], $data['status'], $id]);

        setFlash('success', 'Blog post updated successfully.');
        header('Location: index.php'); exit;
    }
}

$adminPageTitle  = 'Edit Blog Post';
$adminActivePage = 'blogs';
include __DIR__ . '/../includes/header.php';
?>

<div class="wv-page-header">
    <div>
        <h1>Edit Blog Post</h1>
        <div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / <a href="index.php">Blogs</a> / Edit</div>
    </div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>

<?php if ($errors): ?>
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;">
    <?php foreach ($errors as $err): ?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err); ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1fr 320px; gap:24px; align-items:start;">
    <div>
        <div class="wv-card">
            <div class="wv-card__header"><span class="wv-card__title">Blog Content</span></div>
            <div class="wv-card__body">
                <form method="POST" enctype="multipart/form-data" id="blog-form">
                    <div class="wv-form-group">
                        <label class="wv-label">Post Title <span class="wv-required">*</span></label>
                        <input type="text" name="title" class="wv-input" value="<?php echo e($data['title']); ?>" required style="font-size:16px; padding:12px 16px;" />
                    </div>
                    <div class="wv-form-group">
                        <label class="wv-label">Excerpt <span class="wv-required">*</span></label>
                        <textarea name="excerpt" class="wv-textarea" style="min-height:80px;" required><?php echo e($data['excerpt']); ?></textarea>
                    </div>
                    <div class="wv-form-group">
                        <label class="wv-label">Full Content <span class="wv-required">*</span></label>
                        <textarea name="content" class="wv-textarea" style="min-height:280px;" required><?php echo e($data['content']); ?></textarea>
                        <small style="color:#6c757d; font-size:11px; margin-top:4px; display:block;">HTML tags are supported: &lt;p&gt; &lt;h3&gt; &lt;ul&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;a&gt;</small>
                    </div>
                    <div style="display:flex; gap:12px; padding-top:8px;">
                        <button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Update Post</button>
                        <a href="index.php" class="wv-btn wv-btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="wv-card" style="margin-bottom:20px;">
            <div class="wv-card__header"><span class="wv-card__title">Publish Settings</span></div>
            <div class="wv-card__body">
                <div class="wv-form-group">
                    <label class="wv-label">Slug</label>
                    <input type="text" class="wv-input" value="<?php echo e($blog['slug']); ?>" readonly style="background:#f4f6fb; color:#6c757d; font-size:11px;" />
                    <small style="color:#6c757d; font-size:11px; margin-top:4px; display:block;">Auto-updated when title changes.</small>
                </div>
                <div class="wv-form-group">
                    <label class="wv-label">Author</label>
                    <input type="text" name="author" form="blog-form" class="wv-input" value="<?php echo e($data['author']); ?>" />
                </div>
                <div class="wv-form-group">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <input type="checkbox" name="status" id="status" form="blog-form" <?php echo $data['status'] ? 'checked' : ''; ?> style="width:18px;height:18px;accent-color:#0e3c7d;" />
                        <label for="status" style="font-size:13px; font-weight:500; cursor:pointer;">Published (visible on website)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="wv-card">
            <div class="wv-card__header"><span class="wv-card__title">Featured Image</span></div>
            <div class="wv-card__body">
                <?php if ($data['image']): ?>
                <div class="wv-img-preview" style="margin-bottom:12px;">
                    <img id="img_preview" src="../../<?php echo e($data['image']); ?>" alt="" style="width:100%; border-radius:6px;" />
                </div>
                <small style="color:#6c757d; font-size:11px; display:block; margin-bottom:8px;">Upload a new image to replace this one.</small>
                <?php else: ?>
                <div class="wv-img-preview" style="margin-bottom:12px;"><img id="img_preview" src="" alt="" style="display:none; width:100%;" /></div>
                <?php endif; ?>
                <input type="file" name="image" form="blog-form" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;" />
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
