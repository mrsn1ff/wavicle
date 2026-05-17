<?php if (!isset($base)) $base = "/wavicle"; ?>
<?php require __DIR__ . '/site.php'; ?>

<?php
if (!isset($pdo)) require_once __DIR__ . '/../admin/includes/db.php';

// Top 5 products
$footerProducts = $pdo->query(
    "SELECT ci.title, ci.slug, cc.slug as cat_slug FROM catalog_items ci 
      JOIN catalog_categories cc ON cc.id=ci.category_id 
      WHERE ci.type='product' AND ci.status=1 ORDER BY ci.sort_order ASC, ci.id ASC LIMIT 6"
)->fetchAll(PDO::FETCH_ASSOC);
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE type='product' AND status=1")->fetchColumn();

// Top 5 services
$footerServices = $pdo->query(
    "SELECT ci.title, ci.slug, cc.slug as cat_slug FROM catalog_items ci 
      JOIN catalog_categories cc ON cc.id=ci.category_id 
      WHERE ci.type='service' AND ci.status=1 ORDER BY ci.sort_order ASC, ci.id ASC LIMIT 6"
)->fetchAll(PDO::FETCH_ASSOC);
$totalServices = (int)$pdo->query("SELECT COUNT(*) FROM catalog_items WHERE type='service' AND status=1")->fetchColumn();

// Top 3 blogs
$footerBlogs = $pdo->query(
    'SELECT title, slug, created_at FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 6'
)->fetchAll(PDO::FETCH_ASSOC);
$totalBlogs = (int)$pdo->query("SELECT COUNT(*) FROM blogs WHERE status=1")->fetchColumn();
?>

<footer class="site-footer-one">
    <div class="site-footer-one__bg" style="background-image: url(assets/images/background/footer-bg-1-1.jpg);"></div>

    <img src="<?php echo $base; ?>/assets/images/shapes/fish-f-1.png" alt="" class="site-footer__fish-1" />
    <img src="<?php echo $base; ?>/assets/images/shapes/fish-f-2.png" alt="" class="site-footer__fish-2" />
    <img src="<?php echo $base; ?>/assets/images/shapes/fish-f-3.png" alt="" class="site-footer__fish-3" />
    <img src="<?php echo $base; ?>/assets/images/shapes/tree-f-1.png" class="site-footer__tree-1" alt="" />
    <img src="<?php echo $base; ?>/assets/images/shapes/tree-f-2.png" class="site-footer__tree-2" alt="" />

    <div class="site-footer-one__upper">
        <div class="container">
            <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr 1.5fr; gap:40px;">

                <!-- Col 1: Brand -->
                <div>
                    <a href="<?php echo $base; ?>/index.php" style="display:inline-block; margin-bottom:20px;">
                        <img src="<?php echo $logoDark; ?>" alt="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>" style="width:180px; height:auto;" />
                    </a>
                    <p style="color:rgba(255,255,255,.65); font-size:13px; line-height:1.85; margin-bottom:24px;">
                        India's leading swimming pool equipment manufacturer and supplier. Designing water, defining luxury since 2015.
                    </p>
                    <div style="display:flex; gap:10px;">
                        <a href="<?php echo $social['facebook']; ?>" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#59b5e8';" onmouseout="this.style.background='rgba(255,255,255,.1)';"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo $social['twitter']; ?>" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#ff0000';" onmouseout="this.style.background='rgba(255,255,255,.1)';"><i class="fab fa-youtube"></i></a>
                        <a href="<?php echo $social['instagram']; ?>" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#e1306c';" onmouseout="this.style.background='rgba(255,255,255,.1)';"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/919560838375" target="_blank" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#25d366';" onmouseout="this.style.background='rgba(255,255,255,.1)';"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Col 2: Products -->
                <div>
                    <h4 style="font-family:'Montserrat',sans-serif;color:#fff;font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #59b5e8;display:inline-block;">Products</h4>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach ($footerProducts as $fp): ?>
                            <li style="margin-bottom:10px;">
                                <a href="<?php echo $base; ?>/products/<?php echo urlencode($fp['cat_slug']); ?>/<?php echo urlencode($fp['slug']); ?>"
                                    style="color:rgba(255,255,255,.65);font-size:13px;text-decoration:none;transition:color .2s;display:flex;align-items:center;gap:8px;"
                                    onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.65)';">
                                    <i class="fa fa-angle-right" style="font-size:11px;color:#59b5e8;"></i>
                                    <?php echo htmlspecialchars($fp['title'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php if ($totalProducts > 6): ?>
                            <li style="margin-top:14px;">
                                <a href="<?php echo $base; ?>/courses.php"
                                    style="display:inline-flex;align-items:center;gap:6px;color:#59b5e8;font-size:12px;font-weight:700;font-family:'Montserrat',sans-serif;text-decoration:none;border:1px solid rgba(89,181,232,.4);padding:6px 14px;border-radius:4px;transition:all .2s;"
                                    onmouseover="this.style.background='rgba(89,181,232,.15)';" onmouseout="this.style.background='transparent';">
                                    View All <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Col 3: Services -->
                <div>
                    <h4 style="font-family:'Montserrat',sans-serif;color:#fff;font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #59b5e8;display:inline-block;">Services</h4>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach ($footerServices as $fs): ?>
                            <li style="margin-bottom:10px;">
                                <a href="<?php echo $base; ?>/services/<?php echo urlencode($fs['cat_slug']); ?>/<?php echo urlencode($fs['slug']); ?>"
                                    style="color:rgba(255,255,255,.65);font-size:13px;text-decoration:none;transition:color .2s;display:flex;align-items:center;gap:8px;"
                                    onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.65)';">
                                    <i class="fa fa-angle-right" style="font-size:11px;color:#59b5e8;"></i>
                                    <?php echo htmlspecialchars($fs['title'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php if ($totalServices > 6): ?>
                            <li style="margin-top:14px;">
                                <a href="<?php echo $base; ?>/services.php"
                                    style="display:inline-flex;align-items:center;gap:6px;color:#59b5e8;font-size:12px;font-weight:700;font-family:'Montserrat',sans-serif;text-decoration:none;border:1px solid rgba(89,181,232,.4);padding:6px 14px;border-radius:4px;transition:all .2s;"
                                    onmouseover="this.style.background='rgba(89,181,232,.15)';" onmouseout="this.style.background='transparent';">
                                    View All <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Col 4: Latest News -->
                <div>
                    <h4 style="font-family:'Montserrat',sans-serif;color:#fff;font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #59b5e8;display:inline-block;">Latest News</h4>
                    <?php foreach ($footerBlogs as $fb): ?>
                        <div style="margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid rgba(255,255,255,.08);">
                            <a href="<?php echo $base; ?>/news/<?php echo urlencode($fb['slug']); ?>"
                                style="color:rgba(255,255,255,.8);font-size:12px;font-weight:600;text-decoration:none;line-height:1.5;display:block;margin-bottom:4px;font-family:'Montserrat',sans-serif;transition:color .2s;"
                                onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.8)';">
                                <?php echo htmlspecialchars(substr($fb['title'], 0, 45), ENT_QUOTES, 'UTF-8'); ?><?php echo strlen($fb['title']) > 45 ? '…' : ''; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($totalBlogs > 6): ?>
                        <div style="margin-top:6px;">
                            <a href="<?php echo $base; ?>/news.php"
                                style="display:inline-flex;align-items:center;gap:6px;color:#59b5e8;font-size:12px;font-weight:700;font-family:'Montserrat',sans-serif;text-decoration:none;border:1px solid rgba(89,181,232,.4);padding:6px 14px;border-radius:4px;transition:all .2s;"
                                onmouseover="this.style.background='rgba(89,181,232,.15)';" onmouseout="this.style.background='transparent';">
                                View All <i class="fa fa-arrow-right" style="font-size:10px;"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Col 5: Contact -->
                <div>
                    <h4 style="font-family:'Montserrat',sans-serif;color:#fff;font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #59b5e8;display:inline-block;">Contact Us</h4>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <li style="display:flex;gap:20px;margin-top:16px;align-items:center;">
                            <div style="width:34px;height:34px;border-radius:50%;background:rgba(89,181,232,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-phone-alt" style="color:#59b5e8;font-size:13px;"></i>
                            </div>
                            <div>
                                <div style="color:rgba(255,255,255,.45);font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Phone</div>
                                <a href="<?php echo $phoneHref; ?>" style="color:rgba(255,255,255,.85);font-size:13px;font-weight:600;text-decoration:none;font-family:'Montserrat',sans-serif;" onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.85)';"><?php echo htmlspecialchars($phoneDisplay, ENT_QUOTES, 'UTF-8'); ?></a>
                            </div>
                        </li>
                        <li style="display:flex;gap:20px;margin-top:16px;align-items:center;">
                            <div style="width:34px;height:34px;border-radius:50%;background:rgba(89,181,232,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-envelope" style="color:#59b5e8;font-size:13px;"></i>
                            </div>
                            <div>
                                <div style="color:rgba(255,255,255,.45);font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Email</div>
                                <a href="<?php echo $emailHref; ?>" style="color:rgba(255,255,255,.85);font-size:13px;font-weight:600;text-decoration:none;font-family:'Montserrat',sans-serif;" onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.85)';"><?php echo htmlspecialchars($emailDisplay, ENT_QUOTES, 'UTF-8'); ?></a>
                            </div>
                        </li>
                        <li style="display:flex;gap:20px;margin-top:16px;align-items:center;">
                            <div style="width:34px;height:34px;border-radius:50%;background:rgba(89,181,232,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-map-marker-alt" style="color:#59b5e8;font-size:13px;"></i>
                            </div>
                            <div>
                                <div style="color:rgba(255,255,255,.45);font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Address</div>
                                <a href="<?php echo $addressHref; ?>" target="_blank" style="color:rgba(255,255,255,.85);font-size:13px;font-weight:600;text-decoration:none;font-family:'Montserrat',sans-serif;line-height:1.5;" onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.85)';"><?php echo htmlspecialchars($addressDisplay, ENT_QUOTES, 'UTF-8'); ?></a>
                            </div>
                        </li>
                        <li style="display:flex;gap:20px;margin-top:16px;align-items:center;">
                            <div style="width:34px;height:34px;border-radius:50%;background:rgba(89,181,232,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fab fa-whatsapp" style="color:#25d366;font-size:15px;"></i>
                            </div>
                            <div>
                                <div style="color:rgba(255,255,255,.45);font-size:10px;text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">WhatsApp</div>
                                <a href="https://wa.me/919560838375" target="_blank" style="color:rgba(255,255,255,.85);font-size:13px;font-weight:600;text-decoration:none;font-family:'Montserrat',sans-serif;" onmouseover="this.style.color='#25d366';" onmouseout="this.style.color='rgba(255,255,255,.85)';">+91 95608 38375</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div style="border-top:1px solid rgba(255,255,255,.1);padding:20px 0;background:rgba(0,0,0,.2);">
        <div class="container">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <p style="margin:0;color:rgba(255,255,255,.55);font-size:13px;">
                    &copy; Copyright <?php echo date('Y'); ?> by
                    <a href="<?php echo $base; ?>/index.php" style="color:#59b5e8;text-decoration:none;font-weight:600;"><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?></a>
                    — All Rights Reserved.
                </p>
                <div style="display:flex;gap:20px;">
                    <a href="#" style="color:rgba(255,255,255,.45);font-size:12px;text-decoration:none;" onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.45)';">Privacy Policy</a>
                    <a href="#" style="color:rgba(255,255,255,.45);font-size:12px;text-decoration:none;" onmouseover="this.style.color='#59b5e8';" onmouseout="this.style.color='rgba(255,255,255,.45)';">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media(max-width:1199px) {
            .site-footer-one__upper .container>div {
                grid-template-columns: 1fr 1fr 1fr !important;
            }
        }

        @media(max-width:767px) {
            .site-footer-one__upper .container>div {
                grid-template-columns: 1fr 1fr !important;
                gap: 28px !important;
                padding: 40px 0 28px !important;
            }
        }

        @media(max-width:480px) {
            .site-footer-one__upper .container>div {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <!-- Fixed Floating Buttons -->
    <div style="position:fixed;bottom:30px;right:20px;z-index:9999;display:flex;flex-direction:column;align-items:center;gap:10px;">
        <a href="https://wa.me/919560838375" target="_blank" rel="noopener"
            style="width:52px;height:52px;display:flex;align-items:center;justify-content:center;transition:transform .2s;"
            onmouseover="this.style.transform='scale(1.12)';" onmouseout="this.style.transform='scale(1)';">
            <img src="<?php echo $base; ?>/assets/images/WhatsApp_icon.png" alt="WhatsApp" style="width:52px;height:52px;object-fit:contain;" />
        </a>
        <a href="tel:919560838375"
            style="width:52px;height:52px;display:flex;align-items:center;justify-content:center;transition:transform .2s;"
            onmouseover="this.style.transform='scale(1.12)';" onmouseout="this.style.transform='scale(1)';">
            <img src="<?php echo $base; ?>/assets/images/phn.png" alt="Call" style="width:52px;height:52px;object-fit:contain;" />
        </a>
        <a href="#" data-target="html" class="scroll-to-target"
            style="width:40px;height:40px;background:#0e3c7d;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;text-decoration:none;transition:transform .2s,background .2s;"
            onmouseover="this.style.background='#59b5e8';this.style.transform='scale(1.12)';" onmouseout="this.style.background='#0e3c7d';this.style.transform='scale(1)';">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>

</div><!-- /.page-wrapper -->

<div class="side-menu__block">
    <a href="#" class="side-menu__toggler side-menu__close-btn"><i class="fa fa-times"></i></a>
    <div class="side-menu__block-overlay custom-cursor__overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
    <div class="side-menu__block-inner">
        <a href="<?php echo $base; ?>/index.php" class="side-menu__logo">
            <img src="<?php echo $logoLight; ?>" alt="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>" width="143" />
        </a>
        <nav class="mobile-nav__container"></nav>
        <p class="side-menu__block__copy">&copy; <?php echo date('Y'); ?> <a href="<?php echo $base; ?>/index.php"><?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?></a> - All rights reserved.</p>
        <div class="side-menu__social">
            <a href="<?php echo $social['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
            <a href="<?php echo $social['twitter']; ?>"><i class="fab fa-youtube"></i></a>
            <a href="<?php echo $social['instagram']; ?>"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/919560838375" target="_blank"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>
</div>

<script src="<?php echo $base; ?>/assets/js/jquery.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/bootstrap-select.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/isotope.js"></script>
<script src="<?php echo $base; ?>/assets/js/jquery.ajaxchimp.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/jquery.counterup.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/jquery.magnific-popup.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/jquery.validate.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/owl.carousel.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/TweenMax.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/waypoints.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/wow.min.js"></script>
<script src="<?php echo $base; ?>/assets/js/theme.js"></script>
<script>
    if ($('.service-one__carousel').length) {
        var serviceOwl = $('.service-one__carousel').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        });
        $('.service-carousel-prev').on('click', function(e) {
            e.preventDefault();
            serviceOwl.trigger('prev.owl.carousel');
        });
        $('.service-carousel-next').on('click', function(e) {
            e.preventDefault();
            serviceOwl.trigger('next.owl.carousel');
        });
    }
    if ($('.funfact-one__count').length) {
        $('.funfact-one__count').each(function() {
            var full = $(this).text();
            var num = parseInt(full);
            var suffix = full.replace(num, '');
            var $el = $(this);
            $({
                count: 0
            }).animate({
                count: num
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $el.text(Math.floor(this.count) + suffix);
                },
                complete: function() {
                    $el.text(num + suffix);
                }
            });
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var waBtn = document.querySelector('.video-two .video-popup');
        if (waBtn) {
            waBtn.classList.remove('video-popup');
            waBtn.classList.add('wv-wa-btn');
        }
    });
</script>
</body>

</html>