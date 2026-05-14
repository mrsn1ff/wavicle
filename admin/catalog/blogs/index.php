<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';

if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['delete_id'])){
    $id=(int)$_POST['delete_id'];
    $r=$pdo->prepare('SELECT main_image FROM blogs WHERE id=?'); $r->execute([$id]);
    $img=$r->fetchColumn();
    if($img&&strpos($img,'admin/uploads/')!==false){$abs=realpath(__DIR__.'/../../../').'/'.$img;if(file_exists($abs))@unlink($abs);}
    $pdo->prepare('DELETE FROM blogs WHERE id=?')->execute([$id]);
    setFlash('success','Blog deleted.'); header('Location: index.php'); exit;
}

$blogs=$pdo->query('SELECT * FROM blogs ORDER BY created_at DESC')->fetchAll();
$adminPageTitle='Blogs'; $adminActivePage='cat_blogs';
include __DIR__.'/../../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1>News & Articles</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / Blogs</div></div>
    <a href="create.php" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Blog</a>
</div>
<?php renderFlash();?>
<div class="wv-card">
    <div class="wv-card__header"><span class="wv-card__title">All Blogs (<?php echo count($blogs);?>)</span></div>
    <div class="wv-card__body" style="padding:0;">
        <?php if($blogs):?>
        <table class="wv-table">
            <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Slug</th><th>Author</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($blogs as $i=>$b):?>
            <tr>
                <td style="font-weight:600;color:#0e3c7d;"><?php echo $i+1;?></td>
                <td><?php if(!empty($b['main_image'])):?><img src="../../../<?php echo e($b['main_image']);?>" style="width:60px;height:46px;object-fit:cover;border-radius:5px;"/><?php else:?><div style="width:60px;height:46px;background:#f0f6ff;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#adb5bd;font-size:18px;"><i class="fa fa-image"></i></div><?php endif;?></td>
                <td><strong><?php echo e($b['title']);?></strong></td>
                <td><code style="background:#f4f6fb;padding:3px 8px;border-radius:4px;font-size:11px;color:#6c757d;"><?php echo e($b['slug']);?></code></td>
                <td><?php echo e($b['author']??'Admin');?></td>
                <td><span class="wv-badge <?php echo $b['status']?'wv-badge-active':'wv-badge-inactive';?>"><?php echo $b['status']?'Active':'Inactive';?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $b['id'];?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <form method="POST" style="display:inline;"><input type="hidden" name="delete_id" value="<?php echo $b['id'];?>"/><button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($b['title']);?>'?"><i class="fa fa-trash"></i></button></form>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?php else:?>
        <div style="padding:60px;text-align:center;color:#6c757d;"><i class="fa fa-newspaper" style="font-size:48px;color:#dee2e6;display:block;margin-bottom:14px;"></i><div style="font-family:'Montserrat',sans-serif;font-weight:700;margin-bottom:6px;">No blogs yet</div><a href="create.php" style="color:#0e3c7d;font-weight:600;">Add your first blog →</a></div>
        <?php endif;?>
    </div>
</div>
<?php include __DIR__.'/../../includes/footer.php';?>
