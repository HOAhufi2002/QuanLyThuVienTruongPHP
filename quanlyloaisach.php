<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu không phải admin
    exit;
}

// Xử lý thêm loại sách
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $tenLoai = $_POST['TenLoai'];

    // Thêm loại sách mới vào cơ sở dữ liệu
    $stmt = $pdo->prepare("INSERT INTO LoaiSach (TenLoai, IsDel) VALUES (:tenLoai, 1)");
    $stmt->bindParam(':tenLoai', $tenLoai);
    $stmt->execute();

    $success = "Thêm loại sách thành công!";
}

// Xử lý sửa loại sách
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $maLoai = $_POST['MaLoai'];
    $tenLoai = $_POST['TenLoai'];

    // Cập nhật loại sách
    $stmt = $pdo->prepare("UPDATE LoaiSach SET TenLoai = :tenLoai WHERE MaLoai = :maLoai");
    $stmt->bindParam(':tenLoai', $tenLoai);
    $stmt->bindParam(':maLoai', $maLoai);
    $stmt->execute();

    $success = "Cập nhật loại sách thành công!";
}

// Xử lý xóa loại sách
if (isset($_GET['delete'])) {
    $maLoai = $_GET['delete'];

    // Cập nhật trạng thái loại sách thành đã xóa
    $stmt = $pdo->prepare("UPDATE LoaiSach SET IsDel = 0 WHERE MaLoai = :maLoai");
    $stmt->bindParam(':maLoai', $maLoai);
    $stmt->execute();

    $success = "Đã xóa loại sách thành công!";
}

// Tìm kiếm loại sách
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search_term'];
    $stmt = $pdo->prepare("SELECT * FROM LoaiSach WHERE IsDel = 1 AND TenLoai LIKE :search");
    $stmt->bindValue(':search', '%' . $search . '%');
} else {
    $stmt = $pdo->query("SELECT * FROM LoaiSach WHERE IsDel = 1");
}
$stmt->execute();
$loaiSach = $stmt->fetchAll();

include 'header.php'; // Bao gồm header
?>

<div class="container mt-5">
    <h2 class="text-center mb-5">Quản Lý Loại Sách</h2>

    <!-- Hiển thị thông báo thành công -->
    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="row mb-4">
        <!-- Form tìm kiếm loại sách -->
        <div class="col-md-6">
            <form action="" method="POST" class="input-group">
                <input type="text" class="form-control" name="search_term" placeholder="Tìm kiếm loại sách" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" name="search" class="btn btn-primary">Tìm</button>
            </form>
        </div>

        <!-- Form thêm loại sách -->
        <div class="col-md-6 text-end">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" class="form-control" name="TenLoai" placeholder="Nhập tên loại sách" required>
                    <button type="submit" name="add" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Loại</th>
                <th>Tên Loại</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loaiSach as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['MaLoai']); ?></td>
                    <td><?php echo htmlspecialchars($item['TenLoai']); ?></td>
                    <td>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $item['MaLoai']; ?>">
                            Sửa
                        </button>
                        <a href="?delete=<?php echo $item['MaLoai']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                    </td>
                </tr>

                <!-- Modal sửa loại sách -->
                <div class="modal fade" id="editModal<?php echo $item['MaLoai']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Sửa Loại Sách</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="MaLoai" value="<?php echo $item['MaLoai']; ?>">
                                    <div class="mb-3">
                                        <label for="TenLoai" class="form-label">Tên Loại</label>
                                        <input type="text" class="form-control" name="TenLoai" value="<?php echo htmlspecialchars($item['TenLoai']); ?>" required>
                                    </div>
                                    <button type="submit" name="edit" class="btn btn-primary">Cập Nhật</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'footer.php'; // Bao gồm footer ?>
