<?php
$pageTitle  = 'Contact Us | Wavicle';
$activePage = 'contact';
include __DIR__ . '/includes/header.php';
?>

    <!-- Page Title -->
    <section class="course-one__title" style="padding: 140px 0 60px;">
        <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
        <div class="container">
            <div class="block-title text-center">
                <img src="assets/images/shapes/sec-line-1.png" alt="" />
                <p class="text-uppercase">Reach Out</p>
                <h3 class="text-uppercase">Contact Us</h3>
            </div>
        </div>
    </section>

    <!-- Contact Info + Form -->
    <section style="padding: 60px 0 80px;">
        <img src="assets/images/shapes/swimmer-contact-1.png" class="contact-one__swimmer" alt="" />
        <div class="container">
            <div class="row">
                <!-- Contact Info -->
                <div class="col-lg-4" style="margin-bottom:40px;">
                    <div class="block-title text-left" style="margin-bottom:25px;">
                        <img src="assets/images/shapes/sec-line-1.png" alt="" />
                        <p class="text-uppercase">Get in Touch</p>
                        <h3 class="text-uppercase">We'd Love to <br />Hear From You</h3>
                    </div>
                    <div style="margin-bottom:20px;">
                        <h5 style="font-family:'Montserrat',sans-serif; margin-bottom:5px;"><i class="fa fa-phone-alt" style="margin-right:8px;"></i> Phone</h5>
                        <p><a href="<?php echo $phoneHref; ?>"><?php echo htmlspecialchars($phoneDisplay, ENT_QUOTES, 'UTF-8'); ?></a></p>
                    </div>
                    <div style="margin-bottom:20px;">
                        <h5 style="font-family:'Montserrat',sans-serif; margin-bottom:5px;"><i class="fa fa-envelope" style="margin-right:8px;"></i> Email</h5>
                        <p><a href="<?php echo $emailHref; ?>"><?php echo htmlspecialchars($emailDisplay, ENT_QUOTES, 'UTF-8'); ?></a></p>
                    </div>
                    <div>
                        <h5 style="font-family:'Montserrat',sans-serif; margin-bottom:5px;"><i class="fa fa-map-marker-alt" style="margin-right:8px;"></i> Address</h5>
                        <p><?php echo htmlspecialchars($addressDisplay, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div style="background:#f5f5f5; padding:40px; border-radius:8px;">
                        <h3 style="font-family:'Montserrat',sans-serif; margin-bottom:25px;">Send Us a Message</h3>
                        <?php
                        $successMsg = '';
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $name    = htmlspecialchars(trim($_POST['name']    ?? ''), ENT_QUOTES, 'UTF-8');
                            $email   = htmlspecialchars(trim($_POST['email']   ?? ''), ENT_QUOTES, 'UTF-8');
                            $phone   = htmlspecialchars(trim($_POST['phone']   ?? ''), ENT_QUOTES, 'UTF-8');
                            $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
                            if ($name && $email && $message) {
                                // Add mail() or SMTP logic here
                                $successMsg = 'Thank you for contacting Wavicle! We will get back to you shortly.';
                            }
                        }
                        if ($successMsg): ?>
                        <div style="background:#d4edda; color:#155724; padding:15px 20px; border-radius:6px; margin-bottom:20px;">
                            <?php echo $successMsg; ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="contact.php">
                            <div class="row">
                                <div class="col-md-6" style="margin-bottom:20px;">
                                    <input type="text" name="name" placeholder="Your Full Name *" required
                                        style="width:100%; padding:12px 18px; border:1px solid #ddd; border-radius:6px; font-family:'itc-avant-garde-gothic',sans-serif; font-size:15px;" />
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                    <input type="email" name="email" placeholder="Your Email Address *" required
                                        style="width:100%; padding:12px 18px; border:1px solid #ddd; border-radius:6px; font-family:'itc-avant-garde-gothic',sans-serif; font-size:15px;" />
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                    <input type="tel" name="phone" placeholder="Phone Number"
                                        style="width:100%; padding:12px 18px; border:1px solid #ddd; border-radius:6px; font-family:'itc-avant-garde-gothic',sans-serif; font-size:15px;" />
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                    <input type="text" name="subject" placeholder="Subject"
                                        style="width:100%; padding:12px 18px; border:1px solid #ddd; border-radius:6px; font-family:'itc-avant-garde-gothic',sans-serif; font-size:15px;" />
                                </div>
                                <div class="col-12" style="margin-bottom:20px;">
                                    <textarea name="message" rows="6" placeholder="Your Message *" required
                                        style="width:100%; padding:12px 18px; border:1px solid #ddd; border-radius:6px; font-family:'itc-avant-garde-gothic',sans-serif; font-size:15px; resize:vertical;"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="thm-btn">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
