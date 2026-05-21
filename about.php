<?php
$pageTitle  = 'About Us | Wavicle';
$activePage = 'about';
include __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<section class="page-title-one" style="background-image: url(assets/images/background/slide-bg-1-1.jpg); padding: 160px 0 80px; background-size: cover; background-position: center;">
    <div class="container">
        <div class="block-title text-center">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Welcome to Wavicle</p>
            <h3 class="text-uppercase" style="color:#fff;">About Us</h3>
        </div>
    </div>
</section>

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
</section>


<!-- Our Stats -->
<style>
    .wv-stats-container {
        background-color: #051b35;
        padding: 60px 80px;
        position: relative;
        z-index: 22;
        margin-bottom: -141.5px;
    }

    .wv-stats-row-inline {
        display: flex;
        align-items: center;
        justify-content: space-around;
        width: 100%;
        padding: 10px 0;
    }

    .wv-stats-divider-inline {
        width: 1px;
        height: 45px;
        background: rgba(255, 255, 255, 0.25);
        flex-shrink: 0;
    }

    .wv-stats-item-inline {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .wv-stats-number {
        font-size: 44px;
        font-weight: 800;
        color: #fff;
        font-family: 'Montserrat', sans-serif;
        line-height: 1;
        white-space: nowrap;
    }

    .wv-stats-label {
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        font-family: 'Montserrat', sans-serif;
        text-transform: uppercase;
        line-height: 1.5;
    }

    /* Tablet */
    @media (max-width: 1024px) {
        .wv-stats-container {
            padding: 40px 40px;
        }

        .wv-stats-number {
            font-size: 36px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .wv-stats-container {
            padding: 30px 60px;
            margin-bottom: 0;
        }

        .wv-stats-row-inline {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 10px;
            padding: 0;
        }

        .wv-stats-divider-inline {
            display: none;
        }

        .wv-stats-number {
            font-size: 32px;
        }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
        .wv-stats-container {
            padding: 20px 15px;
        }

        .wv-stats-number {
            font-size: 28px;
        }

        .wv-stats-label {
            font-size: 10px;
        }
    }
</style>

<section class="funfact-one funfact-one__home-one">
    <div class="container">
        <div class="funfact-one__title">Our Stats</div>
        <div class="wv-stats-container">
            <div class="wv-stats-row-inline">
                <div class="wv-stats-item-inline">
                    <div class="wv-stats-number"><span class="counter">5</span>+</div>
                    <div class="wv-stats-label">Years Of<br />Experience</div>
                </div>
                <div class="wv-stats-divider-inline"></div>
                <div class="wv-stats-item-inline">
                    <div class="wv-stats-number"><span class="counter">10</span>+</div>
                    <div class="wv-stats-label">Awards<br />Won</div>
                </div>
                <div class="wv-stats-divider-inline"></div>
                <div class="wv-stats-item-inline">
                    <div class="wv-stats-number"><span class="counter">200</span>+</div>
                    <div class="wv-stats-label">Clients<br />Served</div>
                </div>
                <div class="wv-stats-divider-inline"></div>
                <div class="wv-stats-item-inline">
                    <div class="wv-stats-number"><span class="counter">150</span>+</div>
                    <div class="wv-stats-label">States<br />Served</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<div class="cta-three__feature" style="padding: 160px 0 10px 0;">
    <div class="container">
        <div class="block-title text-center" style="margin-bottom:40px;">
            <img src="assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Why Choose Us</p>
            <h3 class="text-uppercase">What Makes Wavicle Different</h3>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Certified Expert <br />Instructors</h3>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Small Group <br />Sizes</h3>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cta-three__feature-box">
                    <div class="cta-three__feature-box-icon"><i class="scubo-icon-checked"></i></div>
                    <h3>Top-Quality <br />Equipment</h3>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include __DIR__ . '/includes/footer.php'; ?>