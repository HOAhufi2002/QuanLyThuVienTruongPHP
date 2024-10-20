<?php
session_start();
include 'config.php'; // Kết nối đến cơ sở dữ liệu

// Xử lý khi người dùng gửi form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Đăng nhập
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Truy vấn cơ sở dữ liệu để kiểm tra tài khoản và mật khẩu
        $stmt = $pdo->prepare("SELECT * FROM NguoiDung WHERE TenDangNhap = :username AND MatKhau = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch();

        // Kiểm tra tài khoản và mật khẩu
        if ($user) {
            $_SESSION['user_id'] = $user['MaNguoiDung'];  // Lưu ID người dùng vào session
            $_SESSION['username'] = $user['TenDangNhap']; // Lưu tên đăng nhập vào session
            $_SESSION['role'] = $user['VaiTro'];          // Lưu vai trò vào session

            // Chuyển hướng người dùng dựa trên vai trò
            if ($user['VaiTro'] == 'admin') {
                header("Location: home.php"); // Chuyển hướng tới trang quản trị
            } else {
                header("Location: home.php"); // Chuyển hướng tới trang chính
            }
            exit;
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    } elseif (isset($_POST['register'])) {
        // Đăng ký
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $hoTen = $_POST['hoTen'];
        $soDienThoai = $_POST['soDienThoai'];

        // Kiểm tra nếu tên đăng nhập đã tồn tại
        $stmt = $pdo->prepare("SELECT * FROM NguoiDung WHERE TenDangNhap = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu
            $stmt = $pdo->prepare("INSERT INTO NguoiDung (TenDangNhap, MatKhau, HoTen, Email, SoDienThoai, VaiTro, NgayTao) 
                                    VALUES (?, ?, ?, ?, ?, 'nguoidung', GETDATE())");
            $stmt->execute([$username, $password, $hoTen, $email, $soDienThoai]);

            $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quản Lý Thư Viện</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            background-image: url('https://i.imgur.com/mj2sQmj.jpg'); /* Thay bằng hình nền bạn muốn */
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }
        .container {
            position: relative;
            width: 400px;
            perspective: 1000px;
        }
        .form-box {
            position: relative;
            width: 100%;
            height: 500px;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        .form-box.flipped {
            transform: rotateY(180deg);
        }
        .form-container {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            backface-visibility: hidden;
        }
        .form-container h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        .form-container input {
            margin-bottom: 1rem;
        }
        .form-container button {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
            border-radius: 50px; /* Làm nút tròn */
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container a {
            display: block;
            margin-top: 1rem;
            color: #007bff;
            text-decoration: none;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        .form-back {
            transform: rotateY(180deg);
        }
        .toggle-btn {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 50px; /* Làm nút tròn */
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }
        .toggle-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box" id="formBox">
            <!-- Form Đăng Nhập -->
            <div class="form-container form-front">
                <h2>Đăng Nhập</h2>
                <form action="login.php" method="POST">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Tên Đăng Nhập" required>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật Khẩu" required>
                    <button type="submit" name="login">Đăng Nhập</button>
                </form>
                <p onclick="toggleForm()">Chuyển sang Đăng Ký</p>
            </div>

            <!-- Form Đăng Ký -->
            <div class="form-container form-back">
                <h2>Đăng Ký</h2>
                <form action="login.php" method="POST">
                    <input type="text" class="form-control" id="reg-username" name="username" placeholder="Tên Đăng Nhập" required>
                    <input type="text" class="form-control" id="reg-hoTen" name="hoTen" placeholder="Họ Tên" required>
                    <input type="text" class="form-control" id="reg-soDienThoai" name="soDienThoai" placeholder="Số Điện Thoại" required>
                    <input type="email" class="form-control" id="reg-email" name="email" placeholder="Email" required>
                    <input type="password" class="form-control" id="reg-password" name="password" placeholder="Mật Khẩu" required>
                    <button type="submit" name="register">Đăng Ký</button>
                </form>
                <p class="hover" onclick="toggleForm()">Chuyển sang Đăng Nhập</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleForm() {
            var formBox = document.getElementById('formBox');
            formBox.classList.toggle('flipped');
        }
    </script>
</body>
</html>
