<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/admin/includes/db.php';

$base = '/wavicle_v5';
$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: '.$base.'/news.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM blogs WHERE slug=? AND status=1 LIMIT 1');
$stmt->execute([$slug]); $blog = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$blog) { header('Location: '.$base.'/news.php'); exit; }

$blogTitle = !empty($blog['title'])       ? $blog['title']       : '';
$blogDesc  = !empty($blog['description']) ? $blog['description'] : '';
$blogImg   = !empty($blog['main_image'])  ? $blog['main_image']  : '';
$author    = !empty($blog['author'])      ? $blog['author']      : 'Admin';

$sections = $pdo->prepare('SELECT * FROM blog_sections WHERE blog_id=? ORDER BY sort_order ASC, id ASC');
$sections->execute([$blog['id']]); $sections = $sections->fetchAll(PDO::FETCH_ASSOC);

$related = $pdo->prepare('SELECT * FROM blogs WHERE status=1 AND id!=? ORDER BY created_at DESC LIMIT 3');
$related->execute([$blog['id']]); $related = $related->fetchAll(PDO::FETCH_ASSOC);

$activePage  = 'news';
$pageTitle   = htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8') . ' | Wavicle';
$imgSrc      = $blogImg ?: $base.'/assets/images/blog/blog-1-1.jpg';
$fallbackImg = $base.'/assets/images/blog/blog-1-1.jpg';

include __DIR__ . '/includes/header.php';
?>

<style>
.bi-hero{background:linear-gradient(135deg,#051b35 0%,#0e3c7d 60%,#1a5096 100%);padding:110px 0 30px;}
.bi-hero-label{font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#59b5e8;margin-bottom:10px;font-family:'Montserrat',sans-serif;}
.bi-hero-title{font-family:'Montserrat',sans-serif;font-size:clamp(22px,4vw,36px);font-weight:800;color:#fff;margin:0 0 10px;line-height:1.3;}
.bi-breadcrumb{font-size:12px;color:rgba(255,255,255,.5);}
.bi-breadcrumb a{color:#59b5e8;text-decoration:none;}
.bi-top{background:#fff;}
.bi-top-grid{display:grid;grid-template-columns:1fr 1fr;max-width:1200px;margin:0 auto;}
.bi-img-col{overflow:hidden;}
.bi-img-col img{width:100%;height:100%;min-height:480px;max-height:540px;object-fit:cover;display:block;}
.bi-desc-col{padding:50px 52px;display:flex;flex-direction:column;justify-content:center;overflow-y:auto;max-height:540px;}
.bi-item-title{font-family:'Montserrat',sans-serif;font-size:24px;font-weight:800;color:#051b35;margin-bottom:12px;line-height:1.3;}
.bi-meta{font-size:12px;color:#adb5bd;margin-bottom:16px;display:flex;align-items:center;gap:16px;}
.bi-desc-text{font-size:14px;line-height:1.95;color:#5a6474;text-align:justify;}
.bi-desc-text p{margin-bottom:12px;}
.bi-desc-text strong{color:#051b35;font-weight:700;}
.bi-desc-text ul{padding-left:20px;margin-bottom:12px;}
.bi-sections{padding:50px 0 80px;background:#fff;}
.bi-sec-block{padding:36px 0;border-top:1px solid #f0f3f8;}
.bi-sec-heading{font-family:'Montserrat',sans-serif;font-size:20px;font-weight:800;color:#051b35;margin-bottom:16px;display:flex;align-items:center;gap:12px;}
.bi-sec-heading::before{content:'';width:4px;height:22px;background:#59b5e8;border-radius:2px;flex-shrink:0;}
.bi-sec-text{font-size:14px;line-height:1.95;color:#5a6474;text-align:justify;}
.bi-sec-text p{margin-bottom:12px;}
.bi-rel-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:28px;}
.bi-rel-card{background:#fff;border-radius:10px;overflow:hidden;border:1px solid #e8eef5;text-decoration:none;transition:transform .2s,box-shadow .2s;display:block;}
.bi-rel-card:hover{transform:translateY(-3px);box-shadow:0 6px 22px rgba(14,60,125,.12);}
.bi-rel-card img{width:100%;height:200px;object-fit:cover;display:block;}
.bi-rel-body{padding:14px 16px;}
.bi-rel-title{font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#051b35;line-height:1.4;}
@media(max-width:991px){.bi-top-grid{grid-template-columns:1fr;}.bi-img-col img{min-height:280px;max-height:320px;}.bi-desc-col{padding:36px 28px;max-height:none;}.bi-rel-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:576px){.bi-desc-col{padding:24px 20px;}.bi-item-title{font-size:18px;}.bi-rel-grid{grid-template-columns:1fr;}}
</style>

<div class="bi-hero">
    <div class="container">
        <div class="bi-hero-label">News &amp; Articles</div>
        <h1 class="bi-hero-title"><?php echo htmlspecialchars($blogTitle,ENT_QUOTES,'UTF-8');?></h1>
        <div class="bi-breadcrumb">
            <a href="<?php echo $base;?>/">Home</a> /
            <a href="<?php echo $base;?>/news.php">Latest News</a> /
            <span style="color:rgba(255,255,255,.8);"><?php echo htmlspecialchars($blogTitle,ENT_QUOTES,'UTF-8');?></span>
        </div>
    </div>
</div>

<div class="bi-top">
    <div class="bi-top-grid">
        <div class="bi-img-col">
            <img src="<?php echo htmlspecialchars($imgSrc,ENT_QUOTES,'UTF-8');?>"
                 alt="<?php echo htmlspecialchars($blogTitle,ENT_QUOTES,'UTF-8');?>"
                 onerror="this.onerror=null;this.src='<?php echo $fallbackImg;?>'" />
        </div>
        <div class="bi-desc-col">
            <h2 class="bi-item-title"><?php echo htmlspecialchars($blogTitle,ENT_QUOTES,'UTF-8');?></h2>
            <div class="bi-meta">
                <span><i class="fa fa-user-circle" style="margin-right:4px;color:#59b5e8;"></i><?php echo htmlspecialchars($author,ENT_QUOTES,'UTF-8');?></span>
                <span><i class="fa fa-calendar" style="margin-right:4px;color:#59b5e8;"></i><?php echo date('d M, Y',strtotime($blog['created_at']));?></span>
            </div>
            <?php if($blogDesc):?><div class="bi-desc-text"><?php echo $blogDesc;?></div><?php endif;?>
        </div>
    </div>
</div>

<?php if(!empty($sections)):?>
<div class="bi-sections">
    <div class="container">
        <?php foreach($sections as $sec):?>
        <div class="bi-sec-block">
            <?php if(!empty($sec['heading'])):?><h3 class="bi-sec-heading"><?php echo htmlspecialchars($sec['heading'],ENT_QUOTES,'UTF-8');?></h3><?php endif;?>
            <div class="bi-sec-text"><?php echo $sec['content'];?></div>
        </div>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>

<?php if(!empty($related)):?>
<div style="padding:60px 0;background:#f8f9fb;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#59b5e8;font-family:'Montserrat',sans-serif;">From The Blog</div>
            <h3 style="font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;color:#051b35;text-transform:uppercase;margin-top:6px;">Related Articles</h3>
        </div>
        <div class="bi-rel-grid">
            <?php foreach($related as $r):
                $rImg  = !empty($r['main_image']) ? htmlspecialchars($r['main_image'],ENT_QUOTES,'UTF-8') : $fallbackImg;
                $rTitle = !empty($r['title']) ? $r['title'] : '';
                $rUrl  = $base.'/news/'.urlencode($r['slug']??'');
            ?>
            <a href="<?php echo $rUrl;?>" class="bi-rel-card">
                <img src="<?php echo $rImg;?>"
                     alt="<?php echo htmlspecialchars($rTitle,ENT_QUOTES,'UTF-8');?>"
                     onerror="this.onerror=null;this.src='<?php echo $fallbackImg;?>'" />
                <div class="bi-rel-body">
                    <div style="font-size:10px;color:#adb5bd;margin-bottom:6px;"><?php echo date('d M, Y',strtotime($r['created_at']));?></div>
                    <div class="bi-rel-title"><?php echo htmlspecialchars($rTitle,ENT_QUOTES,'UTF-8');?></div>
                    <div style="font-size:11px;color:#59b5e8;font-weight:600;margin-top:4px;font-family:'Montserrat',sans-serif;">Read More →</div>
                </div>
            </a>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>

<?php include __DIR__ . '/includes/footer.php'; ?>
