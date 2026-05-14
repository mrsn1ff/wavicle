<?php $base = '/wavicle_v5'; ?>
<?php
$pageTitle  = 'Swimming Pool Equipment Manufacturer & Suppliers';
$activePage = 'home';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Slider -->
<div class="slider-one__wrapper">
    <div class="slider-one">
        <div class="slider-one__carousel thm__owl-dot-1 owl-carousel owl-theme thm__owl-carousel"
            data-carousel-prev-btn=".slider-one__nav-right"
            data-carousel-next-btn=".slider-one__nav-left"
            data-options='{"loop": true, "items": 1, "margin": 0, "dots": true, "nav": false, "animateOut": "slideOutDown", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": true, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>
            <div class="item slider-one__slide-1" style="background-image: url(assets/images/background/slide-bg-1-1.jpg);">
                <div class="container">
                    <div class="slider-one__content text-center">
                        <p class="anim-elm">Discover Scuba Diving with Wavicle</p>
                        <h3 class="anim-elm">A New Discovery <br />Awaits</h3>
                        <a href="https://wa.me/919560838375" target="_blank" rel="noopener" class="anim-elm" style="display:inline-block;">
                            <img src="assets/images/chat_whatsapp.png" alt="Chat on WhatsApp"
                                style="height:80px; width:auto; max-width:220px;" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="item slider-one__slide-2" style="background-image: url(assets/images/background/slide-bg-1-2.jpg);">
                <div class="container">
                    <div class="slider-one__content text-center">
                        <p class="anim-elm">Experience the Ocean with Wavicle</p>
                        <h3 class="anim-elm">Take Your Dive <br />To The Next Level</h3>
                        <a href="https://wa.me/919560838375" target="_blank" rel="noopener" class="anim-elm" style="display:inline-block;">
                            <img src="assets/images/chat_whatsapp.png" alt="Chat on WhatsApp"
                                style="height:80px; width:auto; max-width:220px;" />
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- /.slider-one__carousel -->
        <div class="slider-one__nav">
            <a href="#" class="slider-one__nav-left"><i class="fa fa-angle-right"></i></a>
            <a href="#" class="slider-one__nav-right"><i class="fa fa-angle-left"></i></a>
        </div>
    </div><!-- /.slider-one -->
</div><!-- /.slider-one__wrapper -->

<!-- CTA Banner -->
<section class="cta-two">
    <div class="cta-two__bg" style="background-image: url(assets/images/background/footer-bg-1-1.jpg);"></div>
    <div class="container">
        <img src="assets/images/slide-ribbon-1-2.png" class="cta-two__moc" />
        <h3>We Manufacture India's
            Best Quality <br> Swimming <span>Pool Equipment</span></h3>
        <div class="cta-two__btn-block">
            <a href="contact.php" class="thm-btn cta-two__btn">Get A Quote</a>
        </div>
    </div>
</section><!-- /.cta-two -->

<!-- Services — dynamic from database -->
<?php
require_once __DIR__ . '/admin/includes/db.php';
$base = '/wavicle_v5';
// Service categories from catalog
$dbServiceCats = $pdo->query("SELECT * FROM catalog_categories WHERE type='service' AND status=1 ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="service-one">
    <img src="assets/images/shapes/swimmer-contact-1.png" class="contact-one__swimmer" alt="" />
    <img src="assets/images/shapes/fish-service-1.png" alt="" class="site-footer__fish-1" />
    <img src="assets/images/shapes/fish-service-2.png" alt="" class="site-footer__fish-3" />
    <img src="assets/images/shapes/tree-service-1.png" class="site-footer__tree-2" alt="" />
    <div class="service-one__floated-text">services</div>
    <div class="container" style="position:relative;">
        <div class="block-title text-center">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Our Services</p>
            <h3 class="text-uppercase">What We Offer</h3>
        </div>
        <?php
        $dbOfferings = $pdo->query('SELECT * FROM offerings WHERE status = 1 ORDER BY sort_order ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="owl-carousel service-one__carousel owl-theme">
            <?php foreach ($dbOfferings as $off): ?>
                <div class="service-one__single">
                    <img src="<?php echo htmlspecialchars($off['icon_image'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?php echo htmlspecialchars($off['title'], ENT_QUOTES, 'UTF-8'); ?>"
                        onerror="this.style.display='none'" style="width:70px; height:70px; object-fit:contain; margin:0 auto 15px; display:block;" />
                    <h3><?php echo htmlspecialchars($off['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($off['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="service-one__carousel-btn__wrapper">
            <a class="service-one__carousel-btn-left" href="#"><i class="fa fa-angle-left"></i></a>
            <a class="service-one__carousel-btn-right" href="#"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
    <a href="#" class="service-carousel-prev"><i class="fa fa-angle-left"></i></a>
    <a href="#" class="service-carousel-next"><i class="fa fa-angle-right"></i></a>
</section><!-- /.service-one -->

<!-- Our Stats -->
<section class="funfact-one funfact-one__home-one">
    <div class="container">
        <div class="funfact-one__title">Our Stats</div>
        <div class="inner-container">
            <div class="wv-stats-row">
                <div class="wv-stat-item">
                    <div class="wv-stat-number"><span class="counter">5</span>+</div>
                    <div class="wv-stat-label">Years Of<br />Experience</div>
                </div>
                <div class="wv-stat-divider"></div>
                <div class="wv-stat-item">
                    <div class="wv-stat-number"><span class="counter">10</span>+</div>
                    <div class="wv-stat-label">Awards<br />Won</div>
                </div>
                <div class="wv-stat-divider"></div>
                <div class="wv-stat-item">
                    <div class="wv-stat-number"><span class="counter">200</span>+</div>
                    <div class="wv-stat-label">Clients<br />Served</div>
                </div>
                <div class="wv-stat-divider"></div>
                <div class="wv-stat-item">
                    <div class="wv-stat-number"><span class="counter">150</span>+</div>
                    <div class="wv-stat-label">States<br />Served</div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.funfact-one --><!-- /.funfact-one -->

<!-- Courses Title -->
<section class="course-one__title">
    <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
    <div class="container">
        <div class="block-title text-left">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Our Products</p>
            <h3 class="text-uppercase quality-pool-h3">Quality Swimming Pool Equipment</h3>
        </div>
        <div class="text-block">
            <p class="m-0">At Wavicle Pools, we manufacture and supply premium swimming pool equipment built for durability, performance, and elegance. From filtration systems to pool fittings, every product is engineered to meet the highest industry standards — ensuring your pool stays clean, safe, and luxurious all year round.</p>
        </div>
    </div>
</section><!-- /.course-one__title -->

<!-- Courses Carousel — Homepage Featured Products -->
<?php
// Fetch first product from each category for homepage carousel
$homepageProducts = $pdo->query(
    "SELECT ci.*, cc.name as cat_name, cc.slug as cat_slug 
      FROM catalog_items ci 
      JOIN catalog_categories cc ON cc.id = ci.category_id 
      WHERE ci.type='product' AND ci.status=1 
      AND ci.id IN (
          SELECT MIN(id) FROM catalog_items 
          WHERE type='product' AND status=1 
          GROUP BY category_id
      )
      ORDER BY cc.sort_order ASC, ci.sort_order ASC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="course-one course-one__carousel-wrapper">
    <img src="<?php echo $base;?>/assets/images/shapes/fish-1-1.png" alt="" class="site-footer__fish-1" />
    <img src="<?php echo $base;?>/assets/images/shapes/tree-1-1.png" class="site-footer__tree-1" alt="" />
    <div class="container">
        <?php if(!empty($homepageProducts)): ?>
        <div class="course-one__carousel thm__owl-carousel owl-carousel owl-theme"
            data-options='{"loop": true,"items": 3, "margin":30, "smartSpeed": 700, "autoplay": true, "autoplayTimeout": 5000, "autoplayHoverPause": true, "nav": false, "dots": false, "responsive": { "0": {"items": 1}, "767": {"items": 2}, "991": {"items": 2}, "1199": { "items": 3} }}'
            data-carousel-prev-btn=".course-one__carousel-btn-left"
            data-carousel-next-btn=".course-one__carousel-btn-right">
            <?php foreach($homepageProducts as $p):
                $imgSrc    = !empty($p['main_image']) ? htmlspecialchars($p['main_image'],ENT_QUOTES,'UTF-8') : $base.'/assets/images/courses/course-1-1.jpg';
                $detailUrl = $base.'/products/'.urlencode($p['cat_slug']).'/'.urlencode($p['slug']);
            ?>
            <div class="item">
                <div class="course-one__single">
                    <div class="course-one__image">
                        <a href="<?php echo $detailUrl;?>" class="course-one__cat"><?php echo htmlspecialchars($p['cat_name'],ENT_QUOTES,'UTF-8');?></a>
                        <div class="course-one__image-inner">
                            <img src="<?php echo $imgSrc;?>" alt="<?php echo htmlspecialchars($p['title'],ENT_QUOTES,'UTF-8');?>"
                                 onerror="this.onerror=null;this.src='<?php echo $base;?>/assets/images/courses/course-1-1.jpg'" />
                            <a href="<?php echo $detailUrl;?>"><i class="scubo-icon-plus-symbol"></i></a>
                        </div>
                    </div>
                    <div class="course-one__content hvr-sweep-to-bottom">
                        <h3><a href="<?php echo $detailUrl;?>"><?php echo htmlspecialchars($p['title'],ENT_QUOTES,'UTF-8');?></a></h3>
                        <p><?php echo htmlspecialchars(substr(strip_tags($p['description']??''),0,80),ENT_QUOTES,'UTF-8');?><?php echo strlen(strip_tags($p['description']??''))>80?'..':'';?></p>
                    </div>
                    <a href="<?php echo $base;?>/contact.php" class="course-one__book-link">ENQUIRE FOR THIS</a>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <div class="course-one__carousel-btn__wrapper">
            <a class="course-one__carousel-btn-left" href="#"><i class="fa fa-angle-left"></i></a>
            <a class="course-one__carousel-btn-right" href="#"><i class="fa fa-angle-right"></i></a>
        </div>
        <?php else: ?>
        <div style="text-align:center; padding:60px 0;">
            <i class="fa fa-box-open" style="font-size:52px; color:#dee2e6; display:block; margin-bottom:16px;"></i>
            <p style="font-family:'Montserrat',sans-serif; font-size:16px; font-weight:700; color:#6c757d;">No Products Added Yet</p>
            <p style="color:#adb5bd; font-size:13px;">Products will appear here once added from the admin panel.</p>
        </div>
        <?php endif;?>
    </div>
</div><!-- /.course-one__carousel-wrapper -->

<!-- Video Section -->
<section class="video-two" style="background-image: url(assets/images/shapes/video-2-bg.png)">
    <img src="assets/images/shapes/swimmer-1-1.png" class="video-two__swimmer" alt="" />
    <div class="container">
        <div class="video-two__box wow fadeInRight" data-wow-duration="1500ms">
            <img src="assets/images/whatsapp_side.png" alt="WhatsApp" style="width:100%; height:100%; object-fit:cover;" />
            <a href="https://wa.me/919560838375"
                onclick="window.open('https://wa.me/919560838375','_blank'); return false;"
                style="width:121px; height:121px; background:transparent; display:flex; justify-content:center; align-items:center; position:absolute; left:-60.5px; bottom:60px; cursor:pointer;">
                <img src="assets/images/WhatsApp_icon.png" alt="WhatsApp"
                    style="width:90px; height:90px; object-fit:contain; filter:drop-shadow(0 4px 12px rgba(0,0,0,.3));" />
            </a>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="video-two__content">
                    <div class="block-title">
                        <img src="assets/images/shapes/sec-line-1.png" alt="" />
                        <p class="text-uppercase">REACH US</p>
                        <h3 class="text-uppercase">CUSTOMER SUPPORT AVAILABLE</h3>
                    </div>
                    <p>Our premium swimming pool equipment is engineered for excellence — providing efficient water circulation, advanced filtration, and long-lasting durability. Enjoy a cleaner, safer, and more luxurious swimming experience with every use.</p>
                    <a href="contact.php" class="thm-btn video-two__btn">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.video-two -->

<!-- Testimonials Title -->
<section class="testimonials-one__title testimonials-one__title__home-one">
    <div class="testimonials-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
    <div class="container">
        <div class="block-title text-center">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Testimonials</p>
            <h3 class="text-uppercase">What They Say</h3>
        </div>
    </div>
</section><!-- /.testimonials-one__title -->

<!-- Testimonials Carousel -->
<section class="testimonials-one__carousel-wrapper testimonials-one__carousel-wrapper__home-one">
    <div class="container wow fadeIn" data-wow-duration="2000ms">
        <div class="testimonials-one__carousel owl-carousel owl-theme thm__owl-carousel thm__owl-dot-2"
            data-options='{"items": 3, "margin": 30, "loop": true, "autoplay": true, "autoplayTimeout": 5000, "autoplayHoverPause": true, "smartSpeed": 700, "responsive": {"0": {"items": 1, "dots": false, "nav": true}, "480": {"items": 1, "dots": false, "nav": true}, "767": {"items": 1, "dots": false, "nav": true}, "991": {"items": 2}, "1199": {"items": 3, "margin": 30}}}'>
            <?php
            $testimonials = [
                ['name' => 'Rahul Sharma',   'role' => 'Manager',           'text' => "I don't know what else to say — this is something I have never experienced before. Wavicle changed my life."],
                ['name' => 'Priya Mehta',    'role' => 'Executive',         'text' => "Excellent service, competitive pricing and outstanding customer support. Truly a personal touch that's hard to find."],
                ['name' => 'Amit Verma',     'role' => 'Senior Executive',  'text' => "I don't know what else to say — this is something I have never experienced before. Absolutely incredible."],
                ['name' => 'Neha Kapoor',    'role' => 'Operations Head',   'text' => "I was very impressed by Wavicle's service. The team is knowledgeable, patient and truly passionate."],
                ['name' => 'Vikram Singh',   'role' => 'Project Manager',   'text' => "Their excellent service and personal touch makes Wavicle stand out from every other company I've worked with."],
                ['name' => 'Sunita Agarwal', 'role' => 'General Manager',   'text' => "Wavicle's products are world-class. We installed their equipment and couldn't be happier with the results."],
            ];
            foreach ($testimonials as $t): ?>
                <div class="item">
                    <div class="testimonials-one__single">
                        <div class="testimonials-one__content">
                            <div class="testimonials-one__content-inner">
                                <div class="testimonials-one__qoute"></div>
                                <p><?php echo htmlspecialchars($t['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <div class="testimonials-one__infos">
                                    <div class="testimonials-one__infos-content">
                                        <h3><?php echo htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <span><?php echo htmlspecialchars($t['role'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section><!-- /.testimonials-one__carousel-wrapper -->

<!-- CTA Three -->
<section class="cta-three" style="overflow:hidden; position:relative;">
    <div class="container" style="position:relative;">
        <div class="cta-three__title">Get Consultation!</div>
        <div style="display:flex; align-items:center; min-height:420px;">

            <!-- Left Text — 50% width with proper padding -->
            <div style="width:60%; padding:50px 60px; position:relative; z-index:2; flex-shrink:0;">
                <div class="block-title" style="margin-bottom:22px;">
                    <p class="text-uppercase" style="margin-bottom:10px;">From Our Expert Technicians</p>
                    <h3 class="text-uppercase" style="margin-bottom:22px;">24x7 <br> Available</h3>
                </div>
                <p style="color:#fff; font-size:15px; line-height:1.85; margin-bottom:30px;">
                    Wavicle provides professional support around the clock. Our expert technicians are always ready to assist you with installation, maintenance and consultation.
                </p>
                <a href="contact.php" class="thm-btn cta-three__btn">Get Consultation</a>
            </div>

            <!-- Right Image — 50% width, image bottom-aligned -->
            <div style="width:50%; flex-shrink:0; position:relative; align-self:stretch; overflow:hidden;">
                <img src="assets/images/group.png" alt="Expert Team"
                    class="wow fadeInRight" data-wow-duration="1500ms"
                    style="position:absolute; bottom:0; right:0; height:110%; width:auto; max-width:none; object-fit:contain;" />
            </div>

        </div>
    </div>
</section><!-- /.cta-three -->

<!-- Feature Boxes -->
<div class="cta-three__feature">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Endless Opportunities <br />for Underwater Discovery</h3>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Uniting the World's <br />Largest Tribe of Divers</h3>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Improving the Health of <br />Our Ocean Planet</h3>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.cta-three__feature -->

<!-- About / CTA Four -->
<section class="cta-four">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6">
                <div class="cta-four__image wow fadeInLeft" data-wow-duration="1500ms">
                    <img src="assets/images/resources/cta-2-1.jpg" alt="" />
                    <div class="cta-four__image-content">
                        <i class="scubo-icon-scuba-diving"></i>
                        <p>5+</p>
                        <h3>Years of Experience</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="cta-four__content">
                    <div class="block-title text-left">
                        <img src="assets/images/shapes/sec-line-1.png" alt="" />
                        <p class="text-uppercase" style="margin-bottom:10px;">About Wavicle</p>
                        <h3 class="text-uppercase" style="font-size:45px; line-height:1.2; margin-bottom:20px;">
                            Setting the Highest Standards for Pool Equipment
                        </h3>
                    </div>
                    <p style="text-align:justify; line-height:1.9; margin-bottom:28px;">
                        Wavicle was founded on a passion for quality and a commitment to world-class swimming pool equipment. Our certified technicians bring years of professional experience and genuine enthusiasm to every project we deliver.
                    </p>
                    <a href="about.php" class="thm-btn cta-four__btn">Discover More</a>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.cta-four -->

<!-- /.cta-five -->

<!-- Brand Carousel -->
<section class="brand-one brand-one__home-one">
    <div class="container">
        <div class="brand-one__carousel owl-carousel thm__owl-carousel owl-theme"
            data-options='{"loop": true, "autoplay": true, "autoplayHoverPause": true, "autoplayTimeout": 5000, "items": 5, "dots": false, "nav": false, "margin": 100, "smartSpeed": 700, "responsive": { "0": {"items": 2, "margin": 30}, "480": {"items": 3, "margin": 30}, "991": {"items": 4, "margin": 50}, "1199": {"items": 5, "margin": 100}}}'>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="item"><img src="assets/images/brand/brand-1-<?php echo $i; ?>.png" alt="" /></div>
            <?php endfor; ?>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="item"><img src="assets/images/brand/brand-1-<?php echo $i; ?>.png" alt="" /></div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Blog — Dynamic from DB -->
<?php $homeBlogs = $pdo->query('SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC LIMIT 3')->fetchAll(PDO::FETCH_ASSOC); ?>
<section class="blog-one blog-one__home-one" style="background-image: url(<?php echo $base;?>/assets/images/shapes/about-brand-team-bg.png);">
    <div class="container">
        <div class="block-title text-center">
            <img src="<?php echo $base;?>/assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">From the Blog</p>
            <h3 class="text-uppercase">News &amp; Articles</h3>
        </div>
        <?php if(!empty($homeBlogs)): ?>
        <div class="row">
            <?php foreach($homeBlogs as $blog):
                $bImg = !empty($blog['main_image'])
                    ? htmlspecialchars($blog['main_image'],ENT_QUOTES,'UTF-8')
                    : $base.'/assets/images/blog/blog-1-1.jpg';
                $bUrl = $base.'/news/'.urlencode($blog['slug']);
                $bDate = date('d M, Y', strtotime($blog['created_at']));
            ?>
            <div class="col-lg-4 col-md-12">
                <div class="blog-one__single">
                    <div class="blog-one__image">
                        <a href="<?php echo $bUrl;?>" class="blog-one__date"><?php echo $bDate;?></a>
                        <div class="blog-one__image-inner">
                            <img src="<?php echo $bImg;?>" alt="<?php echo htmlspecialchars($blog['title'],ENT_QUOTES,'UTF-8');?>"
                                 onerror="this.onerror=null;this.src='<?php echo $base;?>/assets/images/blog/blog-1-1.jpg'" />
                            <a href="<?php echo $bUrl;?>"><i class="scubo-icon-plus-symbol"></i></a>
                        </div>
                    </div>
                    <div class="blog-one__content">
                        <h3><a href="<?php echo $bUrl;?>"><?php echo htmlspecialchars($blog['title'],ENT_QUOTES,'UTF-8');?></a></h3>
                        <p><?php echo htmlspecialchars(substr(strip_tags($blog['description']??'Dive into the latest insights from the Wavicle team.'),0,100),ENT_QUOTES,'UTF-8');?>...</p>
                        <div class="blog-one__meta">
                            <a href="<?php echo $bUrl;?>"><i class="far fa-user-circle"></i> <?php echo htmlspecialchars($blog['author']??'Admin',ENT_QUOTES,'UTF-8');?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <?php else:?>
        <div style="text-align:center;padding:50px 0;">
            <i class="fa fa-newspaper" style="font-size:48px;color:#dee2e6;display:block;margin-bottom:14px;"></i>
            <p style="font-family:'Montserrat',sans-serif;font-size:15px;font-weight:700;color:#6c757d;">No Articles Yet</p>
            <p style="color:#adb5bd;font-size:13px;">Articles will appear here once published from admin panel.</p>
        </div>
        <?php endif;?>
    </div>
</section><!-- /.blog-one -->

<!-- /.brand-one -->

<?php include __DIR__ . '/includes/footer.php'; ?>