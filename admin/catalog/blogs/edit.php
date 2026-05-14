<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';

$id=(int)($_GET['id']??0); if(!$id){header('Location: index.php');exit;}
$stmt=$pdo->prepare('SELECT * FROM blogs WHERE id=?'); $stmt->execute([$id]); $blog=$stmt->fetch();
if(!$blog){header('Location: index.php');exit;}

$existSecs=$pdo->prepare('SELECT * FROM blog_sections WHERE blog_id=? ORDER BY sort_order ASC,id ASC');
$existSecs->execute([$id]); $existSecs=$existSecs->fetchAll();

$errors=[]; $data=$blog;

if($_SERVER['REQUEST_METHOD']==='POST'){
    $data['title']       = trim($_POST['title']??'');
    $data['slug']        = makeSlug(trim($_POST['slug']??'')?:trim($_POST['title']??''));
    $data['description'] = $_POST['description']??'';
    $data['author']      = trim($_POST['author']??'Admin');
    $data['status']      = isset($_POST['status'])?1:0;
    $sections            = $_POST['sections']??[];

    if(!$data['title']) $errors[]='Title is required.';

    if(!$errors){
        $base=$data['slug']; $i=2;
        $chk=$pdo->prepare('SELECT COUNT(*) FROM blogs WHERE slug=? AND id!=?');
        $chk->execute([$data['slug'],$id]);
        while((int)$chk->fetchColumn()>0){$data['slug']=$base.'-'.$i++;$chk->execute([$data['slug'],$id]);}

        $newImg=handleImageUpload('main_image','catalog/blogs',$blog['main_image']??'');
        $img=$newImg?:($blog['main_image']??'');

        $pdo->prepare('UPDATE blogs SET title=?,slug=?,main_image=?,description=?,author=?,status=? WHERE id=?')
            ->execute([$data['title'],$data['slug'],$img,$data['description'],$data['author'],$data['status'],$id]);

        $pdo->prepare('DELETE FROM blog_sections WHERE blog_id=?')->execute([$id]);
        $order=1;
        foreach($sections as $sec){
            $h=trim($sec['heading']??''); $c=trim($sec['content']??'');
            if(!$c) continue;
            $pdo->prepare('INSERT INTO blog_sections (blog_id,heading,content,sort_order) VALUES (?,?,?,?)')->execute([$id,$h,$c,$order++]);
        }
        setFlash('success','Blog updated.'); header('Location: index.php'); exit;
    }
}
$adminPageTitle='Edit Blog'; $adminActivePage='cat_blogs';
include __DIR__.'/../../includes/header.php';
?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
var ckInstances={};
function initCK(id){
    if(ckInstances[id]) return;
    ClassicEditor.create(document.getElementById(id),{toolbar:['bold','italic','underline','|','bulletedList','numberedList','|','removeFormat']})
    .then(function(ed){ckInstances[id]=ed;});
}
function syncCK(){Object.keys(ckInstances).forEach(function(id){document.getElementById(id).value=ckInstances[id].getData();});}
var secCount=<?php echo count($existSecs);?>;
function addSection(h,c){
    var idx=secCount++; var tid='sec_'+idx;
    var html='<div class="sec-block" id="sb_'+idx+'" style="border:1px solid #e8eef5;border-radius:10px;margin:16px 20px;overflow:hidden;">'
        +'<div style="background:#f8f9fb;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e8eef5;">'
        +'<span style="font-family:\'Montserrat\',sans-serif;font-size:12px;font-weight:700;color:#0e3c7d;" class="sec-label">Section '+(document.querySelectorAll(".sec-block").length+1)+'</span>'
        +'<button type="button" onclick="delSec('+idx+')" style="background:#fff0f0;border:1px solid #f5c6cb;border-radius:6px;padding:4px 10px;cursor:pointer;color:#dc3545;font-size:11px;font-weight:700;" onmouseover="this.style.background=\'#dc3545\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff0f0\';this.style.color=\'#dc3545\';">✕ Remove</button>'
        +'</div><div style="padding:16px;">'
        +'<div style="margin-bottom:12px;"><label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Heading <span style="font-weight:400;font-style:italic;text-transform:none;color:#adb5bd;">(optional)</span></label>'
        +'<input type="text" name="sections['+idx+'][heading]" class="wv-input" value="'+(h||'')+'" placeholder="e.g. Introduction..."/></div>'
        +'<div><label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Paragraph <span style="color:#dc3545;">*</span></label>'
        +'<textarea name="sections['+idx+'][content]" id="'+tid+'" style="width:100%;border:1px solid #dee2e6;border-radius:7px;padding:10px;font-family:inherit;font-size:13px;min-height:180px;resize:vertical;">'+(c||'')+'</textarea>'
        +'</div></div></div>';
    document.getElementById('sec-container').insertAdjacentHTML('beforeend',html);
    initCK(tid);
}
function delSec(idx){
    if(!confirm('Delete this section?')) return;
    if(ckInstances['sec_'+idx]) ckInstances['sec_'+idx].destroy();
    delete ckInstances['sec_'+idx];
    var el=document.getElementById('sb_'+idx); if(el) el.remove();
}
document.addEventListener('DOMContentLoaded',function(){
    initCK('desc_editor');
    <?php foreach($existSecs as $si=>$sec):?>
    initCK('sec_<?php echo $si;?>');
    <?php endforeach;?>
});
</script>

<div class="wv-page-header">
    <div><h1>Edit Blog</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / <a href="index.php">Blogs</a> / Edit</div></div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<?php if($errors):?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:8px;margin-bottom:20px;"><?php foreach($errors as $er):?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($er);?></div><?php endforeach;?></div><?php endif;?>

<form method="POST" enctype="multipart/form-data" onsubmit="syncCK()">
<div class="wv-card" style="margin-bottom:20px;">
    <div class="wv-card__header"><span class="wv-card__title">Blog Details</span></div>
    <div class="wv-card__body">
        <div class="wv-form-row">
            <div class="wv-form-group"><label class="wv-label">Title <span class="wv-required">*</span></label><input type="text" name="title" class="wv-input" value="<?php echo e($data['title']);?>" required/></div>
            <div class="wv-form-group"><label class="wv-label">Author</label><input type="text" name="author" class="wv-input" value="<?php echo e($data['author']??'Admin');?>"/></div>
        </div>
        <div class="wv-form-row">
            <div class="wv-form-group"><label class="wv-label">Slug</label><input type="text" name="slug" class="wv-input" value="<?php echo e($data['slug']);?>"/><small style="color:#dc3545;font-size:11px;margin-top:4px;display:block;">⚠ Changing slug will break existing URLs.</small></div>
            <div class="wv-form-group"><label class="wv-label">Status</label><div style="margin-top:10px;display:flex;align-items:center;gap:10px;"><input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/><label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active</label></div></div>
        </div>
        <div class="wv-form-row">
            <div class="wv-form-group"><label class="wv-label">Main Image</label>
                <?php if(!empty($data['main_image'])):?><div style="margin-bottom:10px;"><img id="img_preview" src="../../../<?php echo e($data['main_image']);?>" style="max-width:220px;max-height:160px;border-radius:8px;object-fit:cover;border:1px solid #dee2e6;"/></div><small style="color:#6c757d;font-size:11px;margin-bottom:6px;display:block;">Upload new to replace.</small><?php else:?><img id="img_preview" src="" alt="" style="display:none;max-width:220px;border-radius:8px;margin-bottom:10px;"/><?php endif;?>
                <input type="file" name="main_image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/></div>
            <div class="wv-form-group"><label class="wv-label">Description <span class="wv-required">*</span> <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#adb5bd;">— shown beside main image</small></label>
                <textarea name="description" id="desc_editor" style="width:100%;border:1px solid #dee2e6;border-radius:7px;padding:10px;font-family:inherit;font-size:13px;min-height:200px;resize:vertical;"><?php echo htmlspecialchars($data['description']??'',ENT_QUOTES,'UTF-8');?></textarea></div>
        </div>
    </div>
</div>

<div class="wv-card" style="margin-bottom:20px;">
    <div class="wv-card__header"><span class="wv-card__title"><i class="fa fa-list-ul" style="color:#59b5e8;margin-right:8px;"></i>Content Sections</span></div>
    <div id="sec-container">
        <?php foreach($existSecs as $si=>$sec):?>
        <div class="sec-block" id="sb_<?php echo $si;?>" style="border:1px solid #e8eef5;border-radius:10px;margin:16px 20px;overflow:hidden;">
            <div style="background:#f8f9fb;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e8eef5;">
                <span style="font-family:'Montserrat',sans-serif;font-size:12px;font-weight:700;color:#0e3c7d;" class="sec-label">Section <?php echo $si+1;?></span>
                <button type="button" onclick="delSec(<?php echo $si;?>)" style="background:#fff0f0;border:1px solid #f5c6cb;border-radius:6px;padding:4px 10px;cursor:pointer;color:#dc3545;font-size:11px;font-weight:700;" onmouseover="this.style.background='#dc3545';this.style.color='#fff';" onmouseout="this.style.background='#fff0f0';this.style.color='#dc3545';">✕ Remove</button>
            </div>
            <div style="padding:16px;">
                <div style="margin-bottom:12px;"><label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Heading <span style="font-weight:400;font-style:italic;text-transform:none;color:#adb5bd;">(optional)</span></label><input type="text" name="sections[<?php echo $si;?>][heading]" class="wv-input" value="<?php echo e($sec['heading']);?>"/></div>
                <div><label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Paragraph <span style="color:#dc3545;">*</span></label><textarea name="sections[<?php echo $si;?>][content]" id="sec_<?php echo $si;?>" style="width:100%;border:1px solid #dee2e6;border-radius:7px;padding:10px;font-family:inherit;font-size:13px;min-height:180px;resize:vertical;"><?php echo htmlspecialchars($sec['content'],ENT_QUOTES,'UTF-8');?></textarea></div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <div style="padding:20px;"><button type="button" onclick="addSection()" style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:#f0f6ff;color:#0e3c7d;border:2px dashed #59b5e8;border-radius:8px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;cursor:pointer;" onmouseover="this.style.background='#e0eeff';" onmouseout="this.style.background='#f0f6ff';"><i class="fa fa-plus"></i> Add Section</button></div>
</div>

<div style="display:flex;gap:12px;margin-bottom:40px;">
    <button type="submit" class="wv-btn wv-btn-success" style="padding:13px 36px;font-size:14px;"><i class="fa fa-save"></i> Update Blog</button>
    <a href="index.php" class="wv-btn wv-btn-secondary" style="padding:13px 36px;">Cancel</a>
</div>
</form>
<?php include __DIR__.'/../../includes/footer.php';?>
