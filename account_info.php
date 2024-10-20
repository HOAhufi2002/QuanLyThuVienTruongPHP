<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu chưa đăng nhập
    exit;
}

// Lấy thông tin người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM NguoiDung WHERE MaNguoiDung = :user_id AND IsDel = 1");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    // Nếu không tìm thấy người dùng, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit;
}
?>
<?php include 'header.php'; ?>
<div class="container mt-5">
        <h2 class="text-center">Thông Tin Tài Khoản</h2>
        <table class="table table-bordered mt-4">
            <tr>
                <th>Tên Đăng Nhập</th>
                <td><?php echo htmlspecialchars($user['TenDangNhap']); ?></td>
            </tr>
            <tr>
                <th>Họ Tên</th>
                <td><?php echo htmlspecialchars($user['HoTen']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['Email']); ?></td>
            </tr>
            <tr>
                <th>Số Điện Thoại</th>
                <td><?php echo htmlspecialchars($user['SoDienThoai']); ?></td>
            </tr>
            <tr>
                <th>Vai Trò</th>
                <td><?php echo htmlspecialchars($user['VaiTro']); ?></td>
            </tr>
            <tr>
                <th>Ngày Tạo</th>
                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($user['NgayTao']))); ?></td>
            </tr>
        </table>
  
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'footer.php'; ?>
