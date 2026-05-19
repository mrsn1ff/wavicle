<?php require __DIR__ . '/site.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $base; ?>/assets/images/fav.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $base; ?>/assets/images/fav.png" />
    <link rel="manifest" href="<?php echo $base; ?>/assets/images/favicon/site.webmanifest" />

    <!-- Fonts: Montserrat (Headings) + ITC Avant Garde Gothic via Adobe (Body Text) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet" />

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/fontawesome-all.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/jquery.mCustomScrollbar.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/animate.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/hover-min.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/scubo-icons.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/css/responsive.css" />

    <!-- Font Override: apply Montserrat to all headings, Avant Garde to body text -->
    <style>
        body,
        p,
        li,
        td,
        span,
        a,
        input,
        textarea,
        select,
        label,
        .thm-btn,
        .footer-widget__links-list a,
        .blog-one__content p,
        .service-one__single p,
        .course-one__content p,
        .testimonials-one__content p {
            font-family: 'itc-avant-garde-gothic', 'Avant Garde', sans-serif;
        }

        /* Headings → Montserrat */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .block-title h3,
        .block-title h2,
        .slider-one__content h3,
        .cta-two h3,
        .cta-three__title,
        .cta-four__content h3,
        .cta-five__content h3,
        .funfact-one__count,
        .course-one__content h3,
        .blog-one__content h3,
        .testimonials-one__infos-content h3,
        .service-one__single h3,
        .footer-widget__title,
        .page-title-one__title h2,
        .main-nav-one a,
        .side-menu__block-inner nav a {
            font-family: 'Montserrat', sans-serif;
        }

        .support-trigger-btn {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 500;
            padding: 5px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .support-trigger-btn:hover {
            background: rgba(26, 159, 216, 0.25);
            border-color: #1a9fd8;
        }

        .support-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(10, 25, 60, 0.6);
            backdrop-filter: blur(3px);
            align-items: center;
            justify-content: center;
        }

        .support-overlay.active {
            display: flex;
        }

        .support-modal {
            background: #fff;
            border-radius: 16px;
            width: 420px;
            max-width: 95vw;
            overflow: hidden;
            animation: supportSlideUp 0.25s ease;
        }

        @keyframes supportSlideUp {
            from {
                transform: translateY(24px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .support-modal__header {
            background: linear-gradient(135deg, #1a3a6e, #154f9e);
            padding: 28px;
            text-align: center;
            position: relative;
        }

        .support-modal__header h3 {
            color: #fff;
            font-size: 18px;
            margin: 0;
        }

        .support-modal__header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 4px 0 0;
        }

        .support-modal__wave {
            width: 60px;
            height: 3px;
            background: #1a9fd8;
            border-radius: 2px;
            margin: 12px auto 0;
        }

        .support-modal__close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .support-modal__close:hover {
            background: rgba(255, 255, 255, 0.22);
        }


        .support-modal__body {
            padding: 28px;
        }

        .support-form-group {
            margin-bottom: 18px;
        }

        .support-form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #1a3a6e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .support-form-group input,
        .support-form-group textarea {
            width: 100%;
            border: 1px solid #dde6f5;
            border-radius: 7px;
            padding: 10px 13px;
            font-family: inherit;
            font-size: 13px;
            color: #1a3a6e;
            background: #f8fbff;
            outline: none;
            transition: border-color 0.15s, background 0.15s;
        }

        .support-form-group input:focus,
        .support-form-group textarea:focus {
            border-color: #1a9fd8;
            background: #fff;
        }

        .support-form-group textarea {
            height: 90px;
            resize: none;
        }

        .support-submit-btn {
            width: 100%;
            padding: 12px;
            background: #1a9fd8;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }

        .support-submit-btn:hover {
            background: #1589be;
        }

        .support-success {
            text-align: center;
            padding: 20px 0;
        }

        .support-success i {
            font-size: 40px;
            color: #1a9fd8;
        }

        .support-success h4 {
            color: #1a3a6e;
            margin: 10px 0 4px;
        }

        .support-success p {
            color: #5a7a9e;
            font-size: 13px;
        }

        .cta-two__moc {
            max-width: 210px !important;
            height: auto !important;
        }

        .main-nav-one__infos {
            gap: 8px !important;
        }

        .service-one__single p {
            text-align: justify !important;
        }

        /* ── Stats Section ── */
        .wv-stats-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            padding: 10px 0;
        }

        .wv-stat-item {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            justify-content: center;
        }

        .wv-stat-number {
            font-size: 44px;
            font-weight: 800;
            color: #ffff;
            font-family: 'Montserrat', sans-serif;
            line-height: 1;
            white-space: nowrap;
        }

        .wv-stat-label {
            font-size: 11px;
            font-weight: 700;
            color: #ffff;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            line-height: 1.5;
            white-space: nowrap;
        }

        .wv-stat-divider {
            width: 1px;
            height: 45px;
            background: rgba(5, 27, 53, .25);
            flex-shrink: 0;
        }

        .course-one__image-inner img {
            width: 100% !important;
            height: 370px !important;
            object-fit: contain !important;
        }

        .course-one__single {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
        }

        .course-one__content {
            flex: 1 !important;
        }

        .course-one__content h3 {
            min-height: 50px !important;
        }

        .course-one__content p {
            display: -webkit-box !important;
            -webkit-line-clamp: 3 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        @media (max-width: 991px) {
            .nav-info-text {
                display: none;
            }

            .main-nav-one__infos {
                gap: 8px;
            }
        }

        @media (max-width: 768px) {
            .topbar-one__center {
                display: none;
            }

            .cta-two__moc {
                max-width: 170px !important;
            }

            /* .main-nav-one__infos {
                display: none;
            } */

            .slider-one__content a img {
                height: 45px !important;
                max-width: 160px !important;
            }

            .wv-stats-row {
                flex-wrap: wrap;
                gap: 24px;
            }

            .wv-stat-item {
                flex: 0 0 45%;
                justify-content: flex-start;
            }

            .wv-stat-divider {
                display: none;
            }

            .wv-stat-number {
                font-size: 32px;
            }
        }

        @media (max-width: 480px) {
            .slider-one__content a img {
                height: 35px !important;
                max-width: 130px !important;
            }

            .wv-stat-item {
                flex: 0 0 100%;
            }

            .wv-stat-number {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

    <div class="preloader">
        <img src="<?php echo $base; ?>/assets/images/fav.png" class="preloader__image" alt="" />
    </div><!-- /.preloader -->

    <div class="page-wrapper">

        <!-- Topbar -->
        <div class="topbar-one">
            <div class="container" style="display:flex; align-items:center; justify-content:space-between;">
                <div class="topbar-one__left">
                    <button class="support-trigger-btn" onclick="document.getElementById('supportModal').classList.add('active')">
                        <i class="fa fa-headset"></i> Customer Support
                    </button>
                </div>

                <div class="topbar-one__center" style="position:absolute; left:50%; transform:translateX(-50%); font-family:'Montserrat',sans-serif; font-size:16px;font-weight:600; color:#ffffff; letter-spacing:2px; text-transform:uppercase; white-space:nowrap;">
                    INDIA's LEADING POOL EQUIPMENT SUPPLIER
                </div>

                <div class="topbar-one__social">
                    <a href="<?php echo $social['facebook']; ?>"><i class="fab fa-facebook-square"></i></a>
                    <a href="<?php echo $social['twitter']; ?>"><i class="fab fa-youtube"></i></a>
                    <a href="<?php echo $social['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div><!-- /.topbar-one -->

        <!-- Main Nav -->
        <nav class="main-nav-one stricky">
            <div class="container">
                <div class="main-nav-one__infos">
                    <a class="main-nav-one__infos-email" href="<?php echo $emailHref; ?>"
                        style="display:inline-flex; align-items:center; gap:6px;">
                        <img src="<?php echo $base; ?>/assets/images/mail.png" alt="Email"
                            style="width:32px; height:32px; object-fit:contain;"
                            onerror="this.parentElement.style.display='none'" />
                        <span class="nav-info-text"><?php echo htmlspecialchars($emailDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                    <a class="main-nav-one__infos-phone" href="<?php echo $phoneHref; ?>"
                        style="display:inline-flex; align-items:center; gap:6px;">
                        <img src="<?php echo $base; ?>/assets/images/phn.png" alt="Phone"
                            style="width:32px; height:32px; object-fit:contain;"
                            onerror="this.parentElement.style.display='none'" />
                        <span class="nav-info-text"><?php echo htmlspecialchars($phoneDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                </div>
                <div class="inner-container">
                    <div class="logo-box">
                        <a href="<?php echo $base; ?>/index.php" class="main-logo">
                            <img src="<?php echo $logoPath; ?>" alt="<?php echo htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8'); ?>" width='240' />
                        </a>
                        <a href="#" class="side-menu__toggler"><i class="fa fa-bars"></i></a>
                    </div>
                    <div class="main-nav__main-navigation">
                        <ul class="main-nav__navigation-box">
                            <?php include __DIR__ . '/nav.php'; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Customer Support Modal -->
        <div class="support-overlay" id="supportModal" onclick="if(event.target===this) this.classList.remove('active')">
            <div class="support-modal">
                <div class="support-modal__header">
                    <button class="support-modal__close" onclick="document.getElementById('supportModal').classList.remove('active')">&times;</button>
                    <h3>Customer Support</h3>
                    <p>Designing Water, Defining Luxury</p>
                    <div class="support-modal__wave"></div>
                </div>
                <div class="support-modal__body">
                    <div id="supportFormContent">
                        <div class="support-form-group">
                            <label>Your Name*</label>
                            <input type="text" id="supportName" placeholder="Enter your full name" />
                        </div>
                        <div class="support-form-group">
                            <label>Phone Number*</label>
                            <input type="tel" id="supportPhone" placeholder="10 digit mobile number" maxlength="10" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)" onkeypress="return /[0-9]/.test(event.key)" />
                        </div>
                        <div class="support-form-group">
                            <label>Message (Optional)</label>
                            <textarea id="supportMessage" placeholder="How can we help you?"></textarea>
                        </div>
                        <button class="support-submit-btn" onclick="submitSupportForm()">Send Message</button>
                    </div>
                    <div class="support-success" id="supportSuccess" style="display:none;">
                        <i class="fa fa-check-circle"></i>
                        <h4>Message Sent!</h4>
                        <p>Our team will reach out to you shortly.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var SITE_BASE = '<?php echo rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\"); ?>/';

            function submitSupportForm() {
                var name = document.getElementById('supportName').value.trim();
                var phone = document.getElementById('supportPhone').value.trim();
                var message = document.getElementById('supportMessage').value.trim();
                if (!name || !phone) {
                    alert('Please fill name and phone.');
                    return;
                }
                var btn = document.querySelector('.support-submit-btn');
                btn.disabled = true;
                btn.textContent = 'Sending...';
                fetch(SITE_BASE + 'support-submit.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'name=' + encodeURIComponent(name) + '&phone=' + encodeURIComponent(phone) + '&message=' + encodeURIComponent(message)
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            document.getElementById('supportFormContent').style.display = 'none';
                            document.getElementById('supportSuccess').style.display = 'block';
                            setTimeout(function() {
                                document.getElementById('supportModal').classList.remove('active');
                                document.getElementById('supportFormContent').style.display = 'block';
                                document.getElementById('supportSuccess').style.display = 'none';
                                document.getElementById('supportName').value = '';
                                document.getElementById('supportPhone').value = '';
                                document.getElementById('supportMessage').value = '';
                                btn.disabled = false;
                                btn.textContent = 'Send Message';
                            }, 2500);
                        } else {
                            alert(data.message || 'Something went wrong.');
                            btn.disabled = false;
                            btn.textContent = 'Send Message';
                        }
                    })
                    .catch(function() {
                        alert('Network error. Please try again.');
                        btn.disabled = false;
                        btn.textContent = 'Send Message';
                    });
            }
        </script><!-- /.main-nav-one -->