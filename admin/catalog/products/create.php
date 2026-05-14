<?php
require_once __DIR__.'/../../includes/auth.php'; requireLogin();
require_once __DIR__.'/../../includes/db.php';
require_once __DIR__.'/../../includes/functions.php';

$type = 'product';
$categories = $pdo->query("SELECT id,name FROM catalog_categories WHERE type='product' AND status=1 ORDER BY sort_order ASC,name ASC")->fetchAll();
$preselCat  = (int)($_GET['cat']??0);
$errors=[];
$data=['title'=>'','slug'=>'','description'=>'','category_id'=>$preselCat,'sort_order'=>0,'status'=>1];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $data['title']       = trim($_POST['title']??'');
    $data['slug']        = makeSlug(trim($_POST['slug']??'')?:trim($_POST['title']??''));
    $data['description'] = $_POST['description']??'';
    $data['category_id'] = (int)($_POST['category_id']??0);
    $data['sort_order']  = (int)($_POST['sort_order']??0);
    $data['status']      = isset($_POST['status'])?1:0;
    $sections            = $_POST['sections']??[];

    if(!$data['title'])       $errors[]='Title is required.';
    if(!$data['category_id']) $errors[]='Category is required.';

    if(!$errors){
        $base=$data['slug']; $i=2;
        $chk=$pdo->prepare('SELECT COUNT(*) FROM catalog_items WHERE slug=?');
        $chk->execute([$data['slug']]);
        while((int)$chk->fetchColumn()>0){$data['slug']=$base.'-'.$i++;$chk->execute([$data['slug']]);}

        $img=handleImageUpload('main_image','catalog/products');
        $pdo->prepare('INSERT INTO catalog_items (category_id,type,title,slug,main_image,description,sort_order,status) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([$data['category_id'],'product',$data['title'],$data['slug'],$img?:'',$data['description'],$data['sort_order'],$data['status']]);
        $itemId=(int)$pdo->lastInsertId();

        $order=1;
        foreach($sections as $sec){
            $h=trim($sec['heading']??'');
            $c=trim($sec['content']??'');
            if(!$c) continue;
            $pdo->prepare('INSERT INTO catalog_sections (item_id,heading,content,sort_order) VALUES (?,?,?,?)')->execute([$itemId,$h,$c,$order++]);
        }

        setFlash('success','Product "'.$data['title'].'" saved successfully.');
        header('Location: index.php?cat='.$data['category_id']); exit;
    }
}

$adminPageTitle='Add Product'; $adminActivePage='cat_products';
include __DIR__.'/../../includes/header.php';
?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
var secCount=0;
function makeSlug(v){return v.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');}
function initTiny(id){
    tinymce.init({selector:'#'+id,plugins:'lists',toolbar:'bold italic underline | bullist numlist | removeformat',menubar:false,height:200,branding:false,promotion:false,statusbar:false,setup:function(ed){ed.on('change',function(){ed.save();});}});
}
function addSection(h,c){
    var idx=secCount++; var tid='sec_'+idx;
    var first=document.querySelectorAll('.sec-block').length===0;
    var html='<div class="sec-block" id="sb_'+idx+'" style="border:1px solid #e8eef5;border-radius:10px;margin:16px 20px;overflow:hidden;">'
        +'<div style="background:#f8f9fb;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e8eef5;">'
        +'<span style="font-family:\'Montserrat\',sans-serif;font-size:12px;font-weight:700;color:#0e3c7d;" class="sec-label">'+(first?'Section 1 — shown beside main image':'Section '+(document.querySelectorAll(".sec-block").length+1))+'</span>'
        +'<button type="button" onclick="delSection('+idx+')" style="background:#fff0f0;border:1px solid #f5c6cb;border-radius:6px;padding:4px 10px;cursor:pointer;color:#dc3545;font-size:11px;font-weight:700;" onmouseover="this.style.background=\'#dc3545\';this.style.color=\'#fff\';" onmouseout="this.style.background=\'#fff0f0\';this.style.color=\'#dc3545\';">✕ Remove</button>'
        +'</div>'
        +'<div style="padding:16px;">'
        +'<div style="margin-bottom:12px;">'
        +'<label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Heading <span style="font-weight:400;font-style:italic;text-transform:none;color:#adb5bd;">(optional)</span></label>'
        +'<input type="text" name="sections['+idx+'][heading]" class="wv-input" value="'+(h||'')+'" placeholder="e.g. Key Features, Specifications..."/>'
        +'</div>'
        +'<div>'
        +'<label style="display:block;font-size:10px;font-weight:700;color:#8a9ab0;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Paragraph <span style="color:#dc3545;">*</span></label>'
        +'<textarea name="sections['+idx+'][content]" id="'+tid+'" style="width:100%;border:1px solid #dee2e6;border-radius:7px;padding:10px;font-family:inherit;font-size:13px;min-height:180px;resize:vertical;">'+(c||'')+'</textarea>'
        +'</div>'
        +'</div>'
        +'</div>';
    document.getElementById('sec-container').insertAdjacentHTML('beforeend',html);
    initTiny(tid);
    refreshLabels();
}
function delSection(idx){
    if(!confirm('Delete this section?')) return;
    var ed=tinymce.get('sec_'+idx); if(ed) ed.remove();
    var el=document.getElementById('sb_'+idx); if(el) el.remove();
    refreshLabels();
}
function refreshLabels(){
    var blocks=document.querySelectorAll('.sec-block');
    blocks.forEach(function(b,i){
        var lbl=b.querySelector('.sec-label');
        if(lbl) lbl.textContent='Section '+(i+1)+(i===0?' — shown beside main image':'');
    });
}
document.addEventListener('DOMContentLoaded',function(){ addSection(); });
</script>

<div class="wv-page-header">
    <div><h1>Add Product</h1><div class="wv-breadcrumb"><a href="../../index.php">Dashboard</a> / <a href="index.php">Products</a> / Add</div></div>
    <a href="index.php" class="wv-btn wv-btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
</div>

<?php if($errors):?><div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px 20px;border-radius:8px;margin-bottom:20px;"><?php foreach($errors as $er):?><div><i class="fa fa-circle-exclamation"></i> <?php echo e($er);?></div><?php endforeach;?></div><?php endif;?>

<?php if(empty($categories)):?>
<div style="background:#fff3cd;border:1px solid #ffc107;color:#856404;padding:16px 20px;border-radius:8px;margin-bottom:20px;">
    <i class="fa fa-triangle-exclamation"></i> <strong>No product categories found.</strong>
    <a href="../categories/create.php?type=product" style="color:#856404;font-weight:700;margin-left:8px;">Create a category first →</a>
</div>
<?php endif;?>

<form method="POST" enctype="multipart/form-data">

<!-- Basic Info -->
<div class="wv-card" style="margin-bottom:20px;">
    <div class="wv-card__header"><span class="wv-card__title">Basic Information</span></div>
    <div class="wv-card__body">
        <div class="wv-form-row">
            <div class="wv-form-group">
                <label class="wv-label">Product Title <span class="wv-required">*</span></label>
                <input type="text" name="title" class="wv-input" value="<?php echo e($data['title']);?>" placeholder="e.g. TF Series SS Ladder" required oninput="document.getElementById('item_slug').value=makeSlug(this.value)"/>
            </div>
            <div class="wv-form-group">
                <label class="wv-label">Category <span class="wv-required">*</span></label>
                <select name="category_id" class="wv-select" required>
                    <option value="">— Select Category —</option>
                    <?php foreach($categories as $cat):?>
                    <option value="<?php echo $cat['id'];?>" <?php echo $data['category_id']==$cat['id']?'selected':'';?>><?php echo e($cat['name']);?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="wv-form-row">
            <div class="wv-form-group">
                <label class="wv-label">Slug</label>
                <input type="text" name="slug" id="item_slug" class="wv-input" value="<?php echo e($data['slug']);?>" placeholder="auto-generated from title"/>
                <small style="color:#6c757d;font-size:11px;margin-top:4px;display:block;">Auto-generated. Used in page URL.</small>
            </div>
            <div class="wv-form-group">
                <label class="wv-label">Sort Order</label>
                <input type="number" name="sort_order" class="wv-input" value="<?php echo (int)$data['sort_order'];?>" min="0"/>
            </div>
        </div>
        <div class="wv-form-row">
            <div class="wv-form-group">
                <label class="wv-label">Main Image</label>
                <input type="file" name="main_image" class="wv-input" accept="image/*" data-preview="img_preview" style="padding:8px;"/>
                <div style="margin-top:10px;"><img id="img_preview" src="" alt="" style="display:none;max-width:220px;max-height:160px;border-radius:8px;object-fit:cover;border:1px solid #dee2e6;"/></div>
                <small style="color:#6c757d;font-size:11px;margin-top:6px;display:block;">Recommended: 800×600px, JPG/PNG/WebP</small>
            </div>
            <div class="wv-form-group">
                <label class="wv-label">Status</label>
                <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="status" id="status" <?php echo $data['status']?'checked':'';?> style="width:18px;height:18px;accent-color:#0e3c7d;"/>
                    <label for="status" style="font-size:13px;font-weight:500;cursor:pointer;">Active (visible on website)</label>
                </div>
            </div>
        </div>
        <!-- Description (Rich Text) -->
        <div class="wv-form-group">
            <label class="wv-label">Description <span class="wv-required">*</span>
                <small style="font-weight:400;text-transform:none;letter-spacing:0;color:#adb5bd;"> — shown beside main image on product page</small>
            </label>
            <textarea name="description" id="desc_editor" style="width:100%;border:1px solid #dee2e6;border-radius:7px;padding:10px;font-family:inherit;font-size:13px;min-height:200px;resize:vertical;"><?php echo htmlspecialchars($data['description'],ENT_QUOTES,'UTF-8');?></textarea>
        </div>
    </div>
</div>

<!-- Sections -->
<div class="wv-card" style="margin-bottom:20px;">
    <div class="wv-card__header">
        <span class="wv-card__title"><i class="fa fa-list-ul" style="color:#59b5e8;margin-right:8px;"></i>Additional Content Sections</span>
        <small style="color:#6c757d;font-size:12px;">Each section = one heading + one paragraph. Add as many as needed.</small>
    </div>
    <div id="sec-container"></div>
    <div style="padding:20px;">
        <button type="button" onclick="addSection()"
                style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:#f0f6ff;color:#0e3c7d;border:2px dashed #59b5e8;border-radius:8px;font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.background='#e0eeff';" onmouseout="this.style.background='#f0f6ff';">
            <i class="fa fa-plus"></i> Add Another Section
        </button>
    </div>
</div>

<div style="display:flex;gap:12px;margin-bottom:40px;">
    <button type="submit" class="wv-btn wv-btn-success" style="padding:13px 36px;font-size:14px;"><i class="fa fa-save"></i> Save Product</button>
    <a href="index.php" class="wv-btn wv-btn-secondary" style="padding:13px 36px;">Cancel</a>
</div>

</form>

<script>
// Init description editor
document.addEventListener('DOMContentLoaded',function(){
    initTiny('desc_editor');
});
</script>

<?php include __DIR__.'/../../includes/footer.php';?>
