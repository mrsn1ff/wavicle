<?php
$pageTitle  = 'Course Details | Wavicle';
$activePage = 'courses';
include __DIR__ . '/includes/header.php';
?>

    <!-- Page Title -->
    <section class="course-one__title" style="padding: 140px 0 60px;">
        <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
        <div class="container">
            <div class="block-title text-center">
                <img src="assets/images/shapes/sec-line-1.png" alt="" />
                <p class="text-uppercase">Course Info</p>
                <h3 class="text-uppercase">Scuba Diving — Advanced</h3>
            </div>
        </div>
    </section>

    <!-- Course Detail Content -->
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <img src="assets/images/courses/course-1-1.jpg" alt="" style="width:100%; border-radius:8px; margin-bottom:30px;" />
                    <div class="block-title text-left" style="margin-bottom:20px;">
                        <img src="assets/images/shapes/sec-line-1.png" alt="" />
                        <p class="text-uppercase">About This Course</p>
                        <h3 class="text-uppercase">Scuba Diving — Advanced</h3>
                    </div>
                    <p>This course is designed for divers who want to push beyond the basics and explore deeper waters with confidence. Under the guidance of our certified instructors, you will develop advanced buoyancy control, navigation skills and emergency response techniques.</p>
                    <p style="margin-top:15px;">The course consists of five open-water dives, including a deep dive and an underwater navigation dive. All equipment is provided, and sessions are held in small groups to maximise individual attention.</p>

                    <h4 style="margin-top:30px; font-family:'Montserrat',sans-serif;">What You Will Learn</h4>
                    <ul style="margin-top:15px; padding-left:20px; line-height:2;">
                        <li>Advanced buoyancy and trim control</li>
                        <li>Underwater navigation using compass and natural references</li>
                        <li>Deep diving to 30m with proper ascent protocol</li>
                        <li>Night diving and limited visibility techniques</li>
                        <li>Emergency procedures and dive planning</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div style="background:#f5f5f5; padding:30px; border-radius:8px;">
                        <h4 style="font-family:'Montserrat',sans-serif; margin-bottom:20px;">Course Details</h4>
                        <ul class="list-unstyled" style="line-height:2.2;">
                            <li><strong>Level:</strong> Advanced</li>
                            <li><strong>Duration:</strong> 3 Days</li>
                            <li><strong>Dives:</strong> 5 Open-Water Dives</li>
                            <li><strong>Max Depth:</strong> 30m</li>
                            <li><strong>Certification:</strong> PADI Advanced OW</li>
                            <li><strong>Group Size:</strong> Max 6 Students</li>
                        </ul>
                        <a href="contact.php" class="thm-btn" style="display:block; text-align:center; margin-top:20px;">Book This Course</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
