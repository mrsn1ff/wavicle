<?php
$pageTitle  = 'Gallery | Wavicle';
$activePage = 'pages';
include __DIR__ . '/includes/header.php';
?>

    <!-- Page Title -->
    <section class="course-one__title" style="padding: 140px 0 60px;">
        <div class="course-one__bg" style="background-image: url(assets/images/shapes/water-wave-bg.png)"></div>
        <div class="container">
            <div class="block-title text-center">
                <img src="assets/images/shapes/sec-line-1.png" alt="" />
                <p class="text-uppercase">Our Memories</p>
                <h3 class="text-uppercase">Photo Gallery</h3>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row" id="gallery-container">
                <?php
                $images = [
                    ['src' => 'assets/images/courses/course-1-1.jpg', 'cat' => 'Scuba Diving'],
                    ['src' => 'assets/images/courses/course-1-2.jpg', 'cat' => 'Extended Range'],
                    ['src' => 'assets/images/courses/course-1-3.jpg', 'cat' => 'Free Diving'],
                    ['src' => 'assets/images/courses/course-1-4.jpg', 'cat' => 'Rebreather'],
                    ['src' => 'assets/images/courses/course-1-5.jpg', 'cat' => 'Swimming'],
                    ['src' => 'assets/images/courses/course-1-6.jpg', 'cat' => 'Snorkeling'],
                    ['src' => 'assets/images/blog/blog-1-1.jpg',      'cat' => 'Events'],
                    ['src' => 'assets/images/blog/blog-1-2.jpg',      'cat' => 'Events'],
                    ['src' => 'assets/images/blog/blog-1-3.jpg',      'cat' => 'Events'],
                ];
                foreach ($images as $img): ?>
                <div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom:30px;">
                    <div style="position:relative; overflow:hidden; border-radius:8px;">
                        <a href="<?php echo $img['src']; ?>" class="popup-img">
                            <img src="<?php echo $img['src']; ?>" alt="" style="width:100%; height:250px; object-fit:cover; display:block; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
                        </a>
                        <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); padding:10px 15px; color:#fff; font-family:'Montserrat',sans-serif; font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase;">
                            <?php echo htmlspecialchars($img['cat'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
