<?php
$pageTitle  = 'Pricing | Wavicle';
$activePage = 'pages';
include __DIR__ . '/includes/header.php';
?>

    <!-- Page Title -->
    <section class="course-one__title" style="padding: 140px 0 60px;">
        <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
        <div class="container">
            <div class="block-title text-center">
                <img src="assets/images/shapes/sec-line-1.png" alt="" />
                <p class="text-uppercase">Our Packages</p>
                <h3 class="text-uppercase">Transparent Pricing</h3>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row justify-content-center">
                <?php
                $plans = [
                    ['name' => 'Starter',     'price' => '49',  'period' => 'per session', 'features' => ['Snorkeling Introduction', 'Basic Equipment Provided', 'Group Session (up to 10)', 'Safety Briefing Included', '—', '—'], 'popular' => false],
                    ['name' => 'Explorer',    'price' => '149', 'period' => 'per course',  'features' => ['Open Water Scuba Course', 'Full Equipment Included', 'Small Groups (max 6)', 'Theory + Pool + Ocean Dives', 'PADI Certification', '—'], 'popular' => true],
                    ['name' => 'Professional','price' => '299', 'period' => 'per course',  'features' => ['Advanced OW / Freediving', 'Premium Equipment', 'Private Instructor Option', '5 Open-Water Dives', 'PADI Advanced Certification', 'Ongoing Support & Community'], 'popular' => false],
                ];
                foreach ($plans as $plan):
                    $bg = $plan['popular'] ? 'background:#0a3d62; color:#fff;' : 'background:#f5f5f5;';
                    $textColor = $plan['popular'] ? 'color:#fff;' : '';
                    $btnClass  = $plan['popular'] ? 'thm-btn' : 'thm-btn';
                ?>
                <div class="col-lg-4 col-md-6" style="margin-bottom:30px;">
                    <div style="<?php echo $bg; ?> padding:40px 30px; border-radius:8px; text-align:center; height:100%;">
                        <?php if ($plan['popular']): ?>
                        <div style="background:#e8b84b; color:#fff; display:inline-block; padding:4px 14px; border-radius:20px; font-size:12px; font-family:'Montserrat',sans-serif; margin-bottom:15px; font-weight:700; letter-spacing:1px;">MOST POPULAR</div>
                        <?php endif; ?>
                        <h3 style="font-family:'Montserrat',sans-serif; <?php echo $textColor; ?>"><?php echo $plan['name']; ?></h3>
                        <div style="font-size:48px; font-family:'Montserrat',sans-serif; font-weight:700; <?php echo $textColor; ?> margin:20px 0 5px;">$<?php echo $plan['price']; ?></div>
                        <div style="<?php echo $textColor; ?> margin-bottom:25px; opacity:0.8;"><?php echo $plan['period']; ?></div>
                        <ul class="list-unstyled" style="margin-bottom:30px; line-height:2.5; <?php echo $textColor; ?>">
                            <?php foreach ($plan['features'] as $f): ?>
                            <li style="border-top:1px solid <?php echo $plan['popular'] ? 'rgba(255,255,255,0.15)' : '#ddd'; ?>; padding-top:5px;"><?php echo $f; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="contact.php" class="<?php echo $btnClass; ?>" style="<?php echo $plan['popular'] ? 'background:#e8b84b; border-color:#e8b84b;' : ''; ?>">Get Started</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-two" style="margin-bottom:0;">
        <div class="cta-two__bg" style="background-image: url(assets/images/background/footer-bg-1-1.jpg);"></div>
        <div class="container">
            <h3>Not Sure Which Package Is Right for You? <br />We Are Happy to <span>Help You Choose</span></h3>
            <div class="cta-two__btn-block">
                <a href="contact.php" class="thm-btn cta-two__btn">Talk to Our Team</a>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
