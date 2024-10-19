<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu chưa đăng nhập
    exit;
}

// Xử lý yêu cầu mượn sách
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Lấy mã người dùng từ session
    $book_id = $_POST['book_id']; // Lấy mã sách từ form
    $borrow_date = date('Y-m-d'); // Ngày mượn là ngày hiện tại

    // Truy vấn sách từ cơ sở dữ liệu để kiểm tra trạng thái sách
    $stmt = $pdo->prepare("SELECT * FROM Sach WHERE MaSach = :book_id");
    $stmt->bindParam(':book_id', $book_id);
    $stmt->execute();
    $book = $stmt->fetch();

    // Kiểm tra sách có sẵn không
    if ($book && $book['TinhTrang'] == 'con') {
        // Cập nhật thông tin mượn sách vào bảng MuonSach với trạng thái chờ duyệt
        $stmt = $pdo->prepare("INSERT INTO MuonSach (MaNguoiDung, MaSach, NgayMuon, SoLuong, TrangThaiDuyet) 
                               VALUES (:user_id, :book_id, :borrow_date, 1, 'choduyet')");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':book_id', $book_id);
        $stmt->bindParam(':borrow_date', $borrow_date);
        $stmt->execute();

        // Cập nhật trạng thái sách thành "đang mượn" hoặc "mất"
        $stmt = $pdo->prepare(query: "UPDATE Sach SET TinhTrang = 'mat' , SoLuong = SoLuong - 1  WHERE MaSach = :book_id");
        $stmt->bindParam(':book_id', $book_id);
        $stmt->execute();

        $success = "Bạn đã mượn sách thành công và yêu cầu đang chờ duyệt.";
    } else {
        $error = "Sách hiện không có sẵn để mượn!";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-5">Danh Sách Sách</h2>

    <!-- Hiển thị thông báo thành công hoặc lỗi -->
    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (!empty($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <?php
        // Truy vấn tất cả các sách trong cơ sở dữ liệu
        $stmt = $pdo->query("SELECT * FROM Sach WHERE IsDel = 1");
        $books = $stmt->fetchAll();

        foreach ($books as $book) {
            // Lấy đường dẫn hình ảnh sách (nếu không có, dùng ảnh mặc định)
            $imagePath = !empty($book['HinhAnh']) ? $book['HinhAnh'] : '/images/default_book.jpg';
            ?>
            <!-- Thiết kế thẻ card Bootstrap cho mỗi sách -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['TenSach']); ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($book['TenSach']); ?></h5>
                        <p class="card-text"><strong>Tác giả:</strong> <?php echo htmlspecialchars($book['TacGia']); ?></p>
                        <p class="card-text"><strong>Năm xuất bản:</strong> <?php echo htmlspecialchars($book['NamXuatBan']); ?></p>
                        <p class="card-text"><?php echo substr(htmlspecialchars($book['MoTa']), 0, 100); ?>...</p>

                        <!-- Kiểm tra trạng thái sách: nếu có sẵn thì hiển thị nút mượn -->
                        <?php if ($book['SoLuong'] != 0) : ?>
                            <form action="muonsach.php" method="POST">
                                <input type="hidden" name="book_id" value="<?php echo $book['MaSach']; ?>">
                                <button type="submit" class="btn btn-primary">Mượn Sách</button>
                            </form>
                        <?php else : ?>
                            <button class="btn btn-secondary" disabled>Không có sẵn</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
