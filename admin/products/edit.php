<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { header('Location: index.php'); exit; }
$errors = []; $data = $product;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']       = trim($_POST['title'] ?? '');
    $data['category']    = trim($_POST['category'] ?? '');
    $data['description'] = trim($_POST['description'] ?? '');
    $data['sort_order']  = (int)($_POST['sort_order'] ?? 0);
    $data['status']      = isset($_POST['status']) ? 1 : 0;
    if (!$data['title'])       $errors[] = 'Title is required.';
    if (!$data['description']) $errors[] = 'Description is required.';
    if (!$errors) {
        $slug = $product['slug'];
        if ($data['title'] !== $product['title']) {
            $slug = makeSlug($data['title']); $base = $slug; $i = 1;
            while (true) {
                $chk = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug=? AND id!=?');
                $chk->execute([$slug,$id]);
                if ((int)$chk->fetchColumn()===0) break;
                $slug = $base.'-'.(++$i);
            }
        }
        $newImg = handleImageUpload('image', 'products', $product['image']);
        $img = $newImg ?: $product['image'];
        $pdo->prepare('UPDATE products SET title=?,slug=?,category=?,description=?,image=?,sort_order=?,status=? WHERE id=?')
            ->execute([$data['title'],$slug,$data['category'],$data['description'],$img,$data['sort_order'],$data['status'],$id]);
        setFlash('success','Product updated successfully.');
        header('Location: index.php'); exit;
    }
}
$adminPageTitle='Edit Product'; $adminActivePage='products';
include __DIR__ . '/../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Edit Product</h1><div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / <a href="index.php">Products</a> / Edit</div></div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if ($errors): ?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;"><?php foreach($errors as $err): ?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err);?></div><?php endforeach;?></div><?php endif;?>
<div class="wv-card"><div class="wv-card__header"><span class="wv-card__title">Edit: <?php echo e($product['title']);?></span></div><div class="wv-card__body">
<form method="POST" enctype="multipart/form-data">
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Title <span class="wv-required">*</span></label><input type="text" name="title" class="wv-input" value="<?php echo e($data['title']);?>" required/></div>
        <div class="wv-form-group"><label class="wv-label">Category</label><input type="text" name="category" class="wv-input" value="<?php echo e($data['category']);?>"/></div>
    </div>
    <div class="wv-form-group"><label class="wv-label">Slug (auto-updated)</label><input type="text" class="wv-input" value="<?php echo e($product['slug']);?>" readonly style="background:#f4f6fb;color:#6c757d;font-size:12px;"/></div>
    <div class="wv-form-group"><label class="wv-label">Description <span class="wv-required">*</span></label><textarea name="description" class="wv-textarea" required><?php echo e($data['description']);?></textarea></div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Image</label><?php if($data['image']):?><div class="wv-img-preview" style="margin-bottom:10px;"><img id="img_preview" src="../../<?php echo e($data['image']);?>" alt=""/></div><?php else:?><div class="wv-img-preview"><img id="img_preview" src="" alt="" style="display:none;"/></div><?php endif;?><input type="file" name="image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/></div>
        <div class="wv-form-group"><label class="wv-label">Sort Order</label><input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/></div>
    </div>
    <div class="wv-form-group"><div style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/><label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active</label></div></div>
    <div style="display:flex;gap:12px;"><button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Update Product</button><a href="index.php" class="wv-btn wv-btn-secondary">Cancel</a></div>
</form>
</div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
