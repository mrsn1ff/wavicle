<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$errors=[]; $data=['title'=>'','icon_class'=>'scubo-icon-scuba-diving','description'=>'','sort_order'=>0,'status'=>1];
$iconOptions=['scubo-icon-scuba-diving','scubo-icon-aqualung','scubo-icon-swimming','scubo-icon-snorkel','scubo-icon-scuba','scubo-icon-plus-symbol','scubo-icon-checked'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $data['title']       = trim($_POST['title']??'');
    $data['icon_class']  = trim($_POST['icon_class']??'scubo-icon-scuba-diving');
    $data['description'] = trim($_POST['description']??'');
    $data['sort_order']  = (int)($_POST['sort_order']??0);
    $data['status']      = isset($_POST['status'])?1:0;
    if (!$data['title'])       $errors[]='Title is required.';
    if (!$data['description']) $errors[]='Description is required.';
    if (!$errors) {
        $slug=makeSlug($data['title']); $base=$slug; $i=1;
        while(true){$chk=$pdo->prepare('SELECT COUNT(*) FROM services WHERE slug=?');$chk->execute([$slug]);if((int)$chk->fetchColumn()===0)break;$slug=$base.'-'.(++$i);}
        $pdo->prepare('INSERT INTO services (title,slug,icon_class,description,link_url,sort_order,status) VALUES (?,?,?,?,?,?,?)')
            ->execute([$data['title'],$slug,$data['icon_class'],$data['description'],'item-details.php',$data['sort_order'],$data['status']]);
        setFlash('success','Service added successfully.');
        header('Location: index.php'); exit;
    }
}
$adminPageTitle='Add Service'; $adminActivePage='services';
include __DIR__ . '/../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Add Service</h1><div class="wv-breadcrumb"><a href="../index.php">Dashboard</a> / <a href="index.php">Services</a> / Add</div></div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if($errors):?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:6px;margin-bottom:20px;"><?php foreach($errors as $err):?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($err);?></div><?php endforeach;?></div><?php endif;?>
<div class="wv-card"><div class="wv-card__header"><span class="wv-card__title">Service Details</span></div><div class="wv-card__body">
<form method="POST">
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Title <span class="wv-required">*</span></label><input type="text" name="title" class="wv-input" value="<?php echo e($data['title']);?>" required/></div>
        <div class="wv-form-group"><label class="wv-label">Icon Class</label><select name="icon_class" class="wv-select"><?php foreach($iconOptions as $ic):?><option value="<?php echo $ic;?>"<?php echo $data['icon_class']===$ic?' selected':'';?>><?php echo $ic;?></option><?php endforeach;?></select><small style="color:#6c757d;font-size:11px;margin-top:6px;display:block;">Preview: <i id="icon_preview" class="<?php echo e($data['icon_class']);?>" style="font-size:20px;color:#0e3c7d;"></i></small></div>
    </div>
    <div class="wv-form-group"><label class="wv-label">Description <span class="wv-required">*</span></label><textarea name="description" class="wv-textarea" required><?php echo e($data['description']);?></textarea></div>
    <div class="wv-form-row">
        <div class="wv-form-group"><label class="wv-label">Sort Order</label><input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/></div>
        <div class="wv-form-group"><label class="wv-label">Status</label><div style="display:flex;align-items:center;gap:10px;margin-top:10px;"><input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/><label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active</label></div></div>
    </div>
    <div style="display:flex;gap:12px;"><button type="submit" class="wv-btn wv-btn-success"><i class="fa fa-save"></i> Save Service</button><a href="index.php" class="wv-btn wv-btn-secondary">Cancel</a></div>
</form>
</div></div>
<script>document.querySelector('[name="icon_class"]').addEventListener('change',function(){document.getElementById('icon_preview').className=this.value;});</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
