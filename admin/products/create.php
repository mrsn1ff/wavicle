<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$errors = [];
$data = ['title'=>'','category'=>'','description'=>'','sort_order'=>0,'status'=>1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']       = trim($_POST['title'] ?? '');
    $data['category']    = trim($_POST['category'] ?? '');
    $data['description'] = trim($_POST['description'] ?? '');
    $data['sort_order']  = (int)($_POST['sort_order'] ?? 0);
    $data['status']      = isset($_POST['status']) ? 1 : 0;
    if (!$data['title'])       $errors[] = 'Title is required.';
    if (!$data['description']) $errors[] = 'Description is required.';
    if (!$errors) {
        $slug = makeSlug($data['title']);
        $base = $slug; $i = 1;
        while (true) {
            $chk = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug = ?');
            $chk->execute([$slug]);
            if ((int)$chk->fetchColumn() === 0) break;
            $slug = $base . '-' . (++$i);
        }
        $img = handleImageUpload('image', 'products');
        $pdo->prepare('INSERT INTO products (title,slug,category,description,image,link_url,sort_order,status) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([$data['title'],$slug,$data['category'],$data['description'],$img?:'','item-details.php',$data['sort_order'],$data['status']]);
        setFlash('success', 'Product added successfully.');
        header('Location: index.php'); exit;
    }
}
$adminPageTitle='Add Product'; $adminActivePage='products';
include __DIR__ . '/../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Add Product</h1><div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / <a href="index.php">Products</a> / Add</div></div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if ($errors): ?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;"><?php foreach($errors as $err): ?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err);?></div><?php endforeach;?></div><?php endif;?>
<div class="wv-card"><div class="wv-card__header"><span class="wv-card__title">Product Details</span></div><div class="wv-card__body">
<form method="POST" enctype="multipart/form-data" onsubmit="syncCK()">
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Title <span class="wv-required">*</span></label><input type="text" name="title" class="wv-input" value="<?php echo e($data['title']);?>" placeholder="e.g. Scuba Diving" required /></div>
        <div class="wv-form-group"><label class="wv-label">Category</label><input type="text" name="category" class="wv-input" value="<?php echo e($data['category']);?>" placeholder="e.g. Advanced, Beginner" /></div>
    </div>
    <div class="wv-form-group"><label class="wv-label">Description <span class="wv-required">*</span></label><textarea name="description" class="wv-textarea" required><?php echo e($data['description']);?></textarea></div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Image</label><input type="file" name="image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/><div class="wv-img-preview"><img id="img_preview" src="" alt="" style="display:none;"/></div><small style="color:#6c757d;font-size:11px;margin-top:6px;display:block;">JPG, PNG, WebP — 600×400px recommended</small></div>
        <div class="wv-form-group"><label class="wv-label">Sort Order</label><input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/><small style="color:#6c757d;font-size:11px;margin-top:6px;display:block;">Lower = shown first</small></div>
    </div>
    <div class="wv-form-group"><div style="display:flex;align-items:center;gap:10px;"><input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/><label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active (visible on website)</label></div></div>
    <div style="display:flex;gap:12px;"><button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Save Product</button><a href="index.php" class="wv-btn wv-btn-secondary">Cancel</a></div>
</form>
</div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
