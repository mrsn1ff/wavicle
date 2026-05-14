<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';

$type = in_array($_GET['type']??'',['product','service'])?$_GET['type']:'product';
$errors=[];
$data=['type'=>$type,'name'=>'','slug'=>'','description'=>'','sort_order'=>0,'status'=>1];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $data['type']        = in_array($_POST['type']??'',['product','service'])?$_POST['type']:'product';
    $data['name']        = trim($_POST['name']??'');
    $data['slug']        = makeSlug(trim($_POST['slug']??'')?:trim($_POST['name']??''));
    $data['description'] = trim($_POST['description']??'');
    $data['sort_order']  = (int)($_POST['sort_order']??0);
    $data['status']      = isset($_POST['status'])?1:0;
    if(!$data['name']) $errors[]='Name is required.';
    if(!$errors){
        // Unique slug
        $base=$data['slug']; $i=2;
        $chk=$pdo->prepare('SELECT COUNT(*) FROM catalog_categories WHERE slug=?');
        $chk->execute([$data['slug']]);
        while((int)$chk->fetchColumn()>0){ $data['slug']=$base.'-'.$i++; $chk->execute([$data['slug']]); }
        $img=handleImageUpload('image','catalog/categories');
        $pdo->prepare('INSERT INTO catalog_categories (type,name,slug,image,description,sort_order,status) VALUES (?,?,?,?,?,?,?)')
            ->execute([$data['type'],$data['name'],$data['slug'],$img?:'',strip_tags($data['description']),$data['sort_order'],$data['status']]);
        setFlash('success','Category created successfully.');
        header('Location: index.php?type='.$data['type']); exit;
    }
}
$adminPageTitle='Add Category'; $adminActivePage='cat_mgr';
include __DIR__.'/../../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Add Category</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / <a href="index.php?type=<?php echo $type;?>">Categories</a> / Add</div></div>
    <a href="index.php?type=<?php echo $type;?>" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if($errors):?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:8px;margin-bottom:20px;"><?php foreach($errors as $e):?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($e);?></div><?php endforeach;?></div><?php endif;?>
<div class="wv-card"><div class="wv-card__header"><span class="wv-card__title">Category Details</span></div><div class="wv-card__body">
<form method="POST" enctype="multipart/form-data">
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Type <span class="wv-required">*</span></label>
            <select name="type" class="wv-select"><option value="product" <?php echo $data['type']==='product'?'selected':'';?>>Product Category</option><option value="service" <?php echo $data['type']==='service'?'selected':'';?>>Service Category</option></select></div>
        <div class="wv-form-group"><label class="wv-label">Sort Order</label><input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/></div>
    </div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Category Name <span class="wv-required">*</span></label>
            <input type="text" name="name" id="cat_name" class="wv-input" value="<?php echo e($data['name']);?>" placeholder="e.g. Swimming Pool Ladders" required oninput="slug.value=makeSlug(this.value)"/></div>
        <div class="wv-form-group"><label class="wv-label">Slug</label>
            <input type="text" name="slug" id="slug" class="wv-input" value="<?php echo e($data['slug']);?>" placeholder="auto-generated"/>
            <small style="color:#6c757d;font-size:11px;margin-top:4px;display:block;">Auto-generated from name. Used in URL.</small></div>
    </div>
    <div class="wv-form-group"><label class="wv-label">Description</label><textarea name="description" class="wv-textarea" rows="3" placeholder="Brief description..."><?php echo e($data['description']);?></textarea></div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Category Image</label>
            <input type="file" name="image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/>
            <div style="margin-top:10px;"><img id="img_preview" src="" alt="" style="display:none;max-width:180px;border-radius:6px;border:1px solid #dee2e6;"/></div>
            <small style="color:#6c757d;font-size:11px;margin-top:6px;display:block;">Recommended: 800×500px</small></div>
        <div class="wv-form-group"><label class="wv-label">Status</label>
            <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/>
                <label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active (visible on website)</label></div></div>
    </div>
    <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid #dee2e6;">
        <button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Save Category</button>
        <a href="index.php?type=<?php echo $type;?>" class="wv-btn wv-btn-secondary">Cancel</a>
    </div>
</form>
</div></div>
<script>
function makeSlug(v){return v.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');}
</script>
<?php include __DIR__.'/../../includes/footer.php';?>
