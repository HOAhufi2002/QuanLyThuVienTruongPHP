<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra nếu chưa đăng nhập thì chuyển hướng về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>ebook landing page template</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-ebook-landing.css" rel="stylesheet">
    <!--

TemplateMo 588 ebook landing

https://templatemo.com/tm-588-ebook-landing

-->
</head>
    
    <body>

        <main>
            <!-- Navbar -->
            <!-- End Navbar -->

            <!-- Hero Section -->
            <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-5 pb-5 pb-lg-0 mb-lg-0">
                        <h6>Welcome to Ebook</h6>
                        <h1 class="text-white mb-4">Book Management</h1>

                        <!-- Kiểm tra nếu đã đăng nhập -->
                        <?php if (isset($_SESSION['username'])): ?>
                            <p class="text-white">Xin chào, <?php echo $_SESSION['username']; ?>!</p>
                            <a href="./index.php" class="btn custom-btn smoothscroll me-3">Đi tới chức năng</a>
                            <a href="./logout.php" class="btn custom-btn smoothscroll me-3">Đăng Xuất</a>
                        <?php else: ?>
                            <a href="./login.php" class="btn custom-btn smoothscroll me-3">Đăng Nhập</a>
                            <a href="#section_3" class="link link--kale smoothscroll">Đăng Ký</a>
                        <?php endif; ?>
                    </div>

                    <div class="hero-image-wrap col-lg-6 col-12 mt-3 mt-lg-0">
                        <img src="images/education-online-books.png" class="hero-image img-fluid" alt="education online books">
                    </div>
                </div>
            </div>
        </section>
            <!-- End Hero Section -->

            <!-- Featured Section -->
            <section class="featured-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <div class="avatar-group d-flex flex-wrap align-items-center">
                                <img src="images/avatar/portrait-beautiful-young-woman-standing-grey-wall.jpg" class="img-fluid avatar-image" alt="">
                                <img src="images/avatar/portrait-young-redhead-bearded-male.jpg" class="img-fluid avatar-image avatar-image-left" alt="">
                                <img src="images/avatar/pretty-blonde-woman.jpg" class="img-fluid avatar-image avatar-image-left" alt="">
                                <img src="images/avatar/studio-portrait-emotional-happy-funny-smiling-boyfriend.jpg" class="img-fluid avatar-image avatar-image-left" alt="">

                                <div class="reviews-group mt-3 mt-lg-0">
                                    <strong>4.5</strong>
                                    <i class="bi-star-fill"></i>
                                    <i class="bi-star-fill"></i>
                                    <i class="bi-star-fill"></i>
                                    <i class="bi-star-fill"></i>
                                    <i class="bi-star"></i>
                                    <small class="ms-3">2,564 reviews</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Featured Section -->

     
        </main>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>
