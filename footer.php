<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thư Viện - Giao Diện Chuyên Nghiệp</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome (cho icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Tùy chỉnh CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .navbar {
            background-color: #0056b3; /* Màu xanh đậm */
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Đổ bóng dưới navbar */
        }
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: transform 0.3s ease-in-out; /* Animation khi hover */
        }
        .navbar-brand:hover {
            transform: scale(1.1); /* Phóng to logo khi hover */
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }
        .navbar-nav .nav-link:hover {
            background-color: #004080; /* Màu xanh đậm khi hover */
            border-radius: 5px; /* Bo góc */
        }
        /* Hiệu ứng dropdown */
        .dropdown-menu {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        /* Footer */
        footer {
            background-color: #003366; /* Màu nền đậm hơn */
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto; /* Đảm bảo footer luôn ở cuối trang */
        }
        footer a {
            color: #ffd700; /* Màu vàng cho liên kết */
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }
        footer a:hover {
            color: #fff; /* Đổi thành màu trắng khi hover */
        }
        footer .social-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            transition: transform 0.3s ease-in-out;
        }
        footer .social-icons i:hover {
            transform: scale(1.2); /* Phóng to biểu tượng khi hover */
        }
        /* Animation */
        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .navbar {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>
<body>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 Thư Viện - Công ty Thuận Hải</p>
                </div>
                <div class="col-md-6">
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <p>Liên hệ: <a href="mailto:support@thuvien.com">support@thuvien.com</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS và FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
