<?php
include 'header.php';
include 'config.php'; // Kết nối cơ sở dữ liệu

// Thêm người dùng mới
if (isset($_POST['add_user'])) {
    $tenDangNhap = $_POST['TenDangNhap'];
    $matKhau = $_POST['MatKhau']; // Băm mật khẩu
    $hoTen = $_POST['HoTen'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $vaiTro = $_POST['VaiTro'];

    $stmt = $pdo->prepare("INSERT INTO NguoiDung (TenDangNhap, MatKhau, HoTen, Email, SoDienThoai, VaiTro, NgayTao, IsDel) VALUES (?, ?, ?, ?, ?, ?, GETDATE(), 0)");
    $stmt->execute([$tenDangNhap, $matKhau, $hoTen, $email, $soDienThoai, $vaiTro]);
}

// Cập nhật người dùng
if (isset($_POST['edit_user'])) {
    $maNguoiDung = $_POST['MaNguoiDung'];
    $tenDangNhap = $_POST['TenDangNhap'];
    $hoTen = $_POST['HoTen'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $vaiTro = $_POST['VaiTro'];

    $query = "UPDATE NguoiDung SET TenDangNhap = ?, HoTen = ?, Email = ?, SoDienThoai = ?, VaiTro = ?";

    // Kiểm tra nếu có nhập mật khẩu mới
    if (!empty($_POST['MatKhau'])) {
        $matKhau = $_POST['MatKhau'];
        $query .= ", MatKhau = ?";
        $params = [$tenDangNhap, $hoTen, $email, $soDienThoai, $vaiTro, $matKhau, $maNguoiDung];
    } else {
        $params = [$tenDangNhap, $hoTen, $email, $soDienThoai, $vaiTro, $maNguoiDung];
    }

    $query .= " WHERE MaNguoiDung = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}

// Xóa người dùng
if (isset($_POST['delete_user'])) {
    $maNguoiDung = $_POST['MaNguoiDung'];
    $stmt = $pdo->prepare("UPDATE NguoiDung SET IsDel = 0 WHERE MaNguoiDung = ?");
    $stmt->execute([$maNguoiDung]);
}

// Lấy danh sách người dùng
$stmt = $pdo->query("SELECT * FROM NguoiDung WHERE IsDel = 1");
$users = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1 class="text-center">Quản Lý Tài Khoản Người Dùng</h1>

    <!-- Bảng hiển thị danh sách người dùng -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Đăng Nhập</th>
                <th>Họ Tên</th>
                <th>Email</th>
                <th>Số Điện Thoại</th>
                <th>Vai Trò</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['MaNguoiDung']; ?></td>
                    <td><?php echo $user['TenDangNhap']; ?></td>
                    <td><?php echo $user['HoTen']; ?></td>
                    <td><?php echo $user['Email']; ?></td>
                    <td><?php echo $user['SoDienThoai']; ?></td>
                    <td><?php echo $user['VaiTro']; ?></td>
                    <td>
                        <!-- Nút Sửa -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['MaNguoiDung']; ?>">Sửa</button>

                        <!-- Nút Xóa -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="MaNguoiDung" value="<?php echo $user['MaNguoiDung']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Sửa Người Dùng -->
                <div class="modal fade" id="editModal<?php echo $user['MaNguoiDung']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Sửa Thông Tin Người Dùng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="MaNguoiDung" value="<?php echo $user['MaNguoiDung']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Tên Đăng Nhập</label>
                                        <input type="text" name="TenDangNhap" class="form-control" value="<?php echo $user['TenDangNhap']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Họ Tên</label>
                                        <input type="text" name="HoTen" class="form-control" value="<?php echo $user['HoTen']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="Email" class="form-control" value="<?php echo $user['Email']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số Điện Thoại</label>
                                        <input type="text" name="SoDienThoai" class="form-control" value="<?php echo $user['SoDienThoai']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Vai Trò</label>
                                        <input type="text" name="VaiTro" class="form-control" value="<?php echo $user['VaiTro']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mật Khẩu (để trống nếu không thay đổi)</label>
                                        <input type="password" name="MatKhau" class="form-control">
                                    </div>
                                    <button type="submit" name="edit_user" class="btn btn-primary">Lưu Thay Đổi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Thêm Người Dùng Mới -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Thêm Người Dùng Mới</button>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm Người Dùng Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên Đăng Nhập</label>
                            <input type="text" name="TenDangNhap" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật Khẩu</label>
                            <input type="password" name="MatKhau" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ Tên</label>
                            <input type="text" name="HoTen" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" name="SoDienThoai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vai Trò</label>
                            <input type="text" name="VaiTro" class="form-control">
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary">Thêm Người Dùng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>