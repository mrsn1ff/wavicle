<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';
$id=(int)($_GET['id']??0); if(!$id){header('Location: index.php');exit;}
$stmt=$pdo->prepare('SELECT * FROM catalog_categories WHERE id=?'); $stmt->execute([$id]); $cat=$stmt->fetch();
if(!$cat){header('Location: index.php');exit;}
$errors=[]; $data=$cat;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $data['type']=in_array($_POST['type']??'',['product','service'])?$_POST['type']:$cat['type'];
    $data['name']=trim($_POST['name']??'');
    $data['slug']=makeSlug(trim($_POST['slug']??'')?:trim($_POST['name']??''));
    $data['description']=trim($_POST['description']??'');
    $data['sort_order']=(int)($_POST['sort_order']??0);
    $data['status']=isset($_POST['status'])?1:0;
    if(!$data['name'])$errors[]='Name is required.';
    if(!$errors){
        $base=$data['slug']; $i=2;
        $chk=$pdo->prepare('SELECT COUNT(*) FROM catalog_categories WHERE slug=? AND id!=?');
        $chk->execute([$data['slug'],$id]);
        while((int)$chk->fetchColumn()>0){$data['slug']=$base.'-'.$i++;$chk->execute([$data['slug'],$id]);}
        $newImg=handleImageUpload('image','catalog/categories',$cat['image']);
        $img=$newImg?:$cat['image'];
        $pdo->prepare('UPDATE catalog_categories SET type=?,name=?,slug=?,image=?,description=?,sort_order=?,status=? WHERE id=?')
            ->execute([$data['type'],$data['name'],$data['slug'],$img,strip_tags($data['description']),$data['sort_order'],$data['status'],$id]);
        setFlash('success','Category updated.'); header('Location: index.php?type='.$data['type']); exit;
    }
}
$adminPageTitle='Edit Category'; $adminActivePage='cat_mgr';
include __DIR__.'/../../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Edit Category</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / <a href="index.php?type=<?php echo $data['type'];?>">Categories</a> / Edit</div></div>
    <a href="index.php?type=<?php echo $data['type'];?>" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if($errors):?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:8px;margin-bottom:20px;"><?php foreach($errors as $e):?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($e);?></div><?php endforeach;?></div><?php endif;?>
<div class="wv-card"><div class="wv-card__header"><span class="wv-card__title">Edit: <?php echo e($cat['name']);?></span></div><div class="wv-card__body">
<form method="POST" enctype="multipart/form-data">
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Type</label>
            <select name="type" class="wv-select"><option value="product" <?php echo $data['type']==='product'?'selected':'';?>>Product Category</option><option value="service" <?php echo $data['type']==='service'?'selected':'';?>>Service Category</option></select></div>
        <div class="wv-form-group"><label class="wv-label">Sort Order</label><input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/></div>
    </div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Category Name <span class="wv-required">*</span></label><input type="text" name="name" class="wv-input" value="<?php echo e($data['name']);?>" required/></div>
        <div class="wv-form-group"><label class="wv-label">Slug</label><input type="text" name="slug" class="wv-input" value="<?php echo e($data['slug']);?>"/><small style="color:#dc3545;font-size:11px;margin-top:4px;display:block;">⚠ Changing slug will break existing URLs.</small></div>
    </div>
    <div class="wv-form-group"><label class="wv-label">Description</label><textarea name="description" class="wv-textarea" rows="3"><?php echo e($data['description']);?></textarea></div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Category Image</label>
            <?php if($data['image']):?><div style="margin-bottom:10px;"><img id="img_preview" src="../../../<?php echo e($data['image']);?>" style="max-width:180px;border-radius:6px;border:1px solid #dee2e6;"/></div><small style="color:#6c757d;font-size:11px;display:block;margin-bottom:6px;">Upload new to replace.</small><?php else:?><img id="img_preview" src="" alt="" style="display:none;max-width:180px;border-radius:6px;margin-bottom:10px;"/><?php endif;?>
            <input type="file" name="image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/></div>
        <div class="wv-form-group"><label class="wv-label">Status</label>
            <div style="margin-top:10px;display:flex;align-items:center;gap:10px;"><input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/><label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active</label></div></div>
    </div>
    <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid #dee2e6;">
        <button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Update Category</button>
        <a href="index.php?type=<?php echo $data['type'];?>" class="wv-btn wv-btn-secondary">Cancel</a>
    </div>
</form>
</div></div>
<?php include __DIR__.'/../../includes/footer.php';?>
