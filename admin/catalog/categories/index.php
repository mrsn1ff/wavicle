<?php
require_once __DIR__ . '/../../includes/auth.php'; requireLogin();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $r  = $pdo->prepare('SELECT image FROM catalog_categories WHERE id=?'); $r->execute([$id]);
    $img = $r->fetchColumn();
    if ($img && strpos($img,'admin/uploads/')!==false) { $abs=realpath(__DIR__.'/../../../').'/'.$img; if(file_exists($abs))@unlink($abs); }
    $pdo->prepare('DELETE FROM catalog_categories WHERE id=?')->execute([$id]);
    setFlash('success','Category deleted.'); header('Location: index.php'); exit;
}

$type = in_array($_GET['type']??'',['product','service']) ? $_GET['type'] : 'product';
$stmt = $pdo->prepare('SELECT c.*, (SELECT COUNT(*) FROM catalog_items WHERE category_id=c.id) as cnt FROM catalog_categories c WHERE c.type=? ORDER BY c.sort_order ASC, c.id ASC');
$stmt->execute([$type]); $cats = $stmt->fetchAll();
$pc = (int)$pdo->query("SELECT COUNT(*) FROM catalog_categories WHERE type='product'")->fetchColumn();
$sc = (int)$pdo->query("SELECT COUNT(*) FROM catalog_categories WHERE type='service'")->fetchColumn();

$adminPageTitle='Categories'; $adminActivePage='cat_mgr';
include __DIR__.'/../../includes/header.php';
?>
<div class="wv-page-header">
    <div><h1><?php echo ucfirst($type);?> Categories</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / Categories</div></div>
    <a href="create.php?type=<?php echo $type;?>" class="wv-btn wv-btn-primary"><i class="fa fa-plus"></i> Add Category</a>
</div>
<?php renderFlash(); ?>
<div style="display:flex;gap:8px;margin-bottom:24px;">
    <?php foreach(['product'=>['Products',$pc,'box-open'],'service'=>['Services',$sc,'concierge-bell']] as $t=>[$lbl,$cnt,$ico]): $a=$type===$t; ?>
    <a href="?type=<?php echo $t;?>" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;background:<?php echo $a?'#0e3c7d':'#fff';?>;color:<?php echo $a?'#fff':'#6c757d';?>;border:1px solid <?php echo $a?'#0e3c7d':'#dee2e6';?>;border-radius:8px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;text-decoration:none;">
        <i class="fa fa-<?php echo $ico;?>"></i> <?php echo $lbl;?> <span style="background:<?php echo $a?'rgba(255,255,255,.2)':'#f0f0f0';?>;color:<?php echo $a?'#fff':'#333';?>;padding:2px 10px;border-radius:20px;font-size:11px;"><?php echo $cnt;?></span>
    </a>
    <?php endforeach;?>
</div>
<div class="wv-card">
    <div class="wv-card__header"><span class="wv-card__title">All <?php echo ucfirst($type);?> Categories (<?php echo count($cats);?>)</span></div>
    <div class="wv-card__body" style="padding:0;">
        <?php if($cats): ?>
        <table class="wv-table">
            <thead><tr><th>#</th><th>Image</th><th>Name</th><th>Slug</th><th>Items</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($cats as $i=>$c): ?>
            <tr>
                <td style="font-weight:600;color:#0e3c7d;"><?php echo $i+1;?></td>
                <td><?php if($c['image']):?><img src="../../../<?php echo e($c['image']);?>" style="width:60px;height:46px;object-fit:cover;border-radius:5px;"/><?php else:?><div style="width:60px;height:46px;background:#f0f6ff;border-radius:5px;display:flex;align-items:center;justify-content:center;color:#adb5bd;font-size:18px;"><i class="fa fa-image"></i></div><?php endif;?></td>
                <td><strong><?php echo e($c['name']);?></strong></td>
                <td><code style="background:#f4f6fb;padding:3px 8px;border-radius:4px;font-size:11px;color:#6c757d;"><?php echo e($c['slug']);?></code></td>
                <td><a href="../<?php echo $type;?>s/index.php?cat=<?php echo $c['id'];?>" style="color:#59b5e8;font-weight:700;"><?php echo (int)$c['cnt'];?> Items →</a></td>
                <td><span class="wv-badge <?php echo $c['status']?'wv-badge-active':'wv-badge-inactive';?>"><?php echo $c['status']?'Active':'Inactive';?></span></td>
                <td style="white-space:nowrap;">
                    <a href="edit.php?id=<?php echo $c['id'];?>" class="wv-btn wv-btn-secondary wv-btn-sm"><i class="fa fa-pen"></i> Edit</a>
                    <a href="../<?php echo $type;?>s/index.php?cat=<?php echo $c['id'];?>" class="wv-btn wv-btn-primary wv-btn-sm"><i class="fa fa-list"></i> Items</a>
                    <form method="POST" style="display:inline;"><input type="hidden" name="delete_id" value="<?php echo $c['id'];?>"/><button type="submit" class="wv-btn wv-btn-danger wv-btn-sm" data-confirm="Delete '<?php echo e($c['name']);?>'? All items will be deleted!"><i class="fa fa-trash"></i></button></form>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <?php else:?>
        <div style="padding:60px;text-align:center;color:#6c757d;"><i class="fa fa-folder-open" style="font-size:48px;color:#dee2e6;display:block;margin-bottom:14px;"></i><div style="font-family:'Montserrat',sans-serif;font-weight:700;margin-bottom:6px;">No categories yet</div><a href="create.php?type=<?php echo $type;?>" style="color:#0e3c7d;font-weight:600;">Add your first category →</a></div>
        <?php endif;?>
    </div>
</div>
<?php include __DIR__.'/../../includes/footer.php';?>
