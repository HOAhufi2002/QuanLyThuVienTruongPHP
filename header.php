<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra nếu chưa đăng nhập thì chuyển hướng về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
?>

<!doctype html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="A landing page for an ebook website">
        <meta name="author" content="Your Name">

        <title>Quản Lý Thư Viện - Giao Diện Chuyên Nghiệp</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/templatemo-ebook-landing.css" rel="stylesheet">
        <style>.navbar .nav-link, 
.navbar-brand {
    color: #007bff; /* Màu xanh */
}

.navbar .nav-link:hover, 
.navbar-brand:hover {
    color: #0056b3; /* Màu xanh đậm khi hover */
}
</style>
    </head>
    
    <body>
        <main>
            <!-- Navbar -->
            <nav class="navbar navbar-expand">
                <div class="container">
                    <a class="navbar-brand" href="home.php">
                        <i class="navbar-brand-icon bi-book me-2"></i>
                        <span>Thư Viện</span>
                    </a>

    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-auto me-lg-4">


    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item">
        <a class="nav-link click-scroll" href="index.php">Trang Chủ</a>
    </li>
        <li class="nav-item">
        <a class="nav-link btn btn-primary rounded-pill me-2" href="quanlysach.php">Quản Lý Sách</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link click-scroll" href="quanlysach.php">Quản Lý Tài khoản</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link click-scroll" href="quanlyloaisach.php">Quản Lý Loại Sách</a>
        </li>
        <li class="nav-item">
            <a class="nav-link click-scroll" href="quanlymuonsach.php">Quản Lý Mượn Sách</a>
        </li>
        <li class="nav-item">
            <a class="nav-link click-scroll" href="borrow_return.php">Trả Sách</a>
        </li>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'nguoidung'): ?>
        <li class="nav-item">
        <a class="nav-link click-scroll" href="view_books.php">Xem Sách</a>
        </li>
        <li class="nav-item">
            <a class="nav-link click-scroll" href="MuonSach.php">Mượn Sách</a>
        </li>
        <li class="nav-item">
            <a class="nav-link click-scroll" href="userMuonSach.php">Sách Mượn</a>
        </li>
    <?php endif; ?>
</ul>
                        <?php if (isset($_SESSION['username'])): ?>
    <ul class="navbar-nav ms-lg-auto me-lg-4">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="usernameDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION['username']; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="usernameDropdown">
                <li><a class="dropdown-item" href="account_info.php">Thông Tin Tài Khoản</a></li>
                <li><a class="dropdown-item" href="logout.php">Đăng Xuất</a></li>
            </ul>
        </li>
    </ul>
<?php else: ?>
    <ul class="navbar-nav ms-lg-auto me-lg-4">
        <li class="nav-item">
            <a href="login.php" class="nav-link">Đăng Nhập</a>
        </li>
    </ul>
<?php endif; ?>


                        
                    </div>
                </div>
            </nav>
</main><br>     <br><br><br>   </main>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/click-scroll.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>
