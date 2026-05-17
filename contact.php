<?php
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_contains($line, '=') && $line[0] !== '#') {
            [$k, $v] = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v);
        }
    }
}
$base = rtrim($_ENV['SITE_BASE'] ?? '/wavicle', '/');
$pageTitle  = 'Contact Us | Wavicle';
$activePage = 'contact';
include __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<section class="course-one__title" style="padding: 140px 0 60px;">
    <div class="course-one__bg" style="background-image: url(<?php echo $base; ?>/assets/images/shapes/water-wave-bg.png)"></div>
    <div class="container">
        <div class="block-title text-center">
            <img src="<?php echo $base; ?>/assets/images/shapes/sec-line-1.png" alt="" />
            <p class="text-uppercase">Reach Out</p>
            <h3 class="text-uppercase">Contact Us</h3>
        </div>
    </div>
</section>

<!-- Contact Info + Form -->
<section style="padding: 60px 0 80px;">
    <img src="<?php echo $base; ?>/assets/images/shapes/swimmer-contact-1.png" class="contact-one__swimmer" alt="" />
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4" style="margin-bottom:40px;">
                <div class="block-title text-left" style="margin-bottom:25px;">
                    <img src="<?php echo $base; ?>/assets/images/shapes/sec-line-1.png" alt="" />
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

                    <div id="contactFormContent">
                        <div class="support-form-group">
                            <label>Your Name <span style="color:#dc3545;">*</span></label>
                            <input type="text" id="contactName" placeholder="Enter your full name" />
                        </div>
                        <div class="support-form-group">
                            <label>Phone Number <span style="color:#dc3545;">*</span></label>
                            <input type="tel" id="contactPhone" placeholder="10 digit mobile number" maxlength="10"
                                oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
                                onkeypress="return /[0-9]/.test(event.key)" />
                        </div>
                        <div class="support-form-group">
                            <label>Message <span style="font-weight:400;color:#adb5bd;">(Optional)</span></label>
                            <textarea id="contactMessage" placeholder="How can we help you?"></textarea>
                        </div>
                        <button onclick="submitContactForm()"
                            style="width:100%;padding:14px;background:#0e3c7d;color:#fff;border:none;border-radius:7px;font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:background .2s;"
                            onmouseover="this.style.background='#59b5e8';"
                            onmouseout="this.style.background='#0e3c7d';">
                            Send Message
                        </button>
                    </div>

                    <div id="contactSuccess" style="display:none;text-align:center;padding:30px 0;">
                        <i class="fa fa-check-circle" style="font-size:48px;color:#0e3c7d;display:block;margin-bottom:14px;"></i>
                        <h4 style="font-family:'Montserrat',sans-serif;color:#051b35;margin-bottom:8px;">Message Sent!</h4>
                        <p style="color:#6c757d;font-size:14px;">Our team will reach out to you shortly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var CONTACT_BASE = '<?php echo rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\"); ?>/';

    function submitContactForm() {
        var name = document.getElementById('contactName').value.trim();
        var phone = document.getElementById('contactPhone').value.trim();
        var message = document.getElementById('contactMessage').value.trim();
        if (!name || !phone) {
            alert('Please fill your name and phone number.');
            return;
        }
        if (phone.length !== 10) {
            alert('Please enter a valid 10 digit phone number.');
            return;
        }
        var btn = document.querySelector('#contactFormContent button');
        btn.disabled = true;
        btn.textContent = 'Sending...';
        fetch(CONTACT_BASE + 'support-submit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'name=' + encodeURIComponent(name) +
                    '&phone=' + encodeURIComponent(phone) +
                    '&message=' + encodeURIComponent(message)
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                if (data.success) {
                    document.getElementById('contactFormContent').style.display = 'none';
                    document.getElementById('contactSuccess').style.display = 'block';
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
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>