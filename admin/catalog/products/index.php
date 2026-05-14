<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';

if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['delete_id'])){
    $id=(int)$_POST['delete_id'];
    $r=$pdo->prepare("SELECT main_image FROM catalog_items WHERE id=? AND type='product'"); $r->execute([$id]);
    $img=$r->fetchColumn();
    if($img&&strpos($img,'admin/uploads/')!==false){$abs=realpath(__DIR__.'/../../../').'/'.$img;if(file_exists($abs))@unlink($abs);}
    $pdo->prepare('DELETE FROM catalog_items WHERE id=?')->execute([$id]);
    setFlash('success','Product deleted.'); header('Location: index.php'.($catId=$_POST['cat_id']??0?'?cat='.(int)$_POST['cat_id']:'')); exit;
}

$catId=(int)($_GET['cat']??0);
$allCats=$pdo->query("SELECT * FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC, name ASC")->fetchAll();

if($catId){
    $stmt=$pdo->prepare("SELECT ci.*,cc.name as cat_name FROM catalog_items ci JOIN catalog_categories cc ON cc.id=ci.category_id WHERE ci.category_id=? AND ci.type='product' ORDER BY ci.sort_order ASC,ci.id ASC");
    $stmt->execute([$catId]);
} else {
    $stmt=$pdo->query("SELECT ci.*,cc.name as cat_name FROM catalog_items ci JOIN catalog_categories cc ON cc.id=ci.category_id WHERE ci.type='product' ORDER BY cc.sort_order ASC,ci.sort_order ASC,ci.id ASC");
}
$items=$stmt->fetchAll();

$adminPageTitle='Products'; $adminActivePage='cat_products';
include __DIR__.'/../../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>Products</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / Products</div></div>
    <a href="create.php<?php echo $catId?'?cat='.$catId:'';?>" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Product</a>
</div>
<?php renderFlash();?>
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;align-items:center;">
    <a href="index.php" style="padding:8px 16px;background:<?php echo !$catId?'#0e3c7d':'#fff';?>;color:<?php echo !$catId?'#fff':'#6c757d';?>;border:1px solid <?php echo !$catId?'#0e3c7d':'#dee2e6';?>;border-radius:8px;font-family:'Montserrat',sans-serif;font-size:12px;font-weight:700;text-decoration:none;">All</a>
    <?php foreach($allCats as $c):?>
    <a href="?cat=<?php echo $c['id'];?>" style="padding:8px 16px;background:<?php echo $catId==$c['id']?'#0e3c7d':'#fff';?>;color:<?php echo $catId==$c['id']?'#fff':'#6c757d';?>;border:1px solid <?php echo $catId==$c['id']?'#0e3c7d':'#dee2e6';?>;border-radius:8px;font-family:'Montserrat',sans-serif;font-size:12px;font-weight:700;text-decoration:none;"><?php echo e($c['name']);?></a>
    <?php endforeach;?>
    <a href="../categories/index.php?type=product" class="wv-btn wv-btn-secondary wv-btn-sm" style="margin-left:auto;"><i class="fa fa-folder-open"></i> Manage Categories</a>
</div>
<div class="wv-card">
    <div class="wv-card__header"><span class="wv-card__title">All Products (<?php echo count($items);?>)</span></div>
    <div class="wv-card__body" style="padding:0;">
        <?php if($items):?>
        <table class="wv-table">
            <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($items as $i=>$item):?>
            <tr>
                <td style="font-weight:600;color:#0e3c7d;"><?php echo $i+1;?></td>
                <td><?php if($item['main_image']):?><img src="../../../<?php echo e($item['main_image']);?>" style="width:60px;height:46px;object-fit:cover;border-radius:5px;"/><?php else:?><div style="width:60px;height:46px;background:#f0f6ff;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#adb5bd;font-size:18px;"><i class="fa fa-image"></i></div><?php endif;?></td>
                <td><strong><?php echo e($item['title']);?></strong><?php if(!empty($item['description'])):?><div style="font-size:11px;color:#adb5bd;margin-top:2px;"><?php echo e(substr(strip_tags($item['description']),0,50));?>...</div><?php endif;?></td>
                <td><span style="background:#e8f4fb;color:#0e3c7d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;"><?php echo e($item['cat_name']);?></span></td>
                <td><code style="background:#f4f6fb;padding:3px 8px;border-radius:4px;font-size:11px;color:#6c757d;"><?php echo e($item['slug']);?></code></td>
                <td><span class="wv-badge <?php echo $item['status']?'wv-badge-active':'wv-badge-inactive';?>"><?php echo $item['status']?'Active':'Inactive';?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $item['id'];?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <form method="POST" style="display:inline;"><input type="hidden" name="delete_id" value="<?php echo $item['id'];?>"/><input type="hidden" name="cat_id" value="<?php echo $item['category_id'];?>"/><button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($item['title']);?>'?"><i class="fa fa-trash"></i></button></form>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?php else:?>
        <div style="padding:60px;text-align:center;color:#6c757d;"><i class="fa fa-box-open" style="font-size:48px;color:#dee2e6;display:block;margin-bottom:14px;"></i><div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:15px;margin-bottom:6px;">No products yet</div><a href="create.php" style="color:#0e3c7d;font-weight:600;">Add your first product →</a></div>
        <?php endif;?>
    </div>
</div>
<?php include __DIR__.'/../../includes/footer.php';?>
