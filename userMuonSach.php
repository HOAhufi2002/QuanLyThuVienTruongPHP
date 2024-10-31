<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu chưa đăng nhập
    exit;
}

// Lấy mã người dùng từ session
$maNguoiDung = $_SESSION['user_id'];

// Truy vấn thông tin mượn sách của người dùng
$stmt = $pdo->prepare("
    SELECT 
        MuonSach.MaMuon,
        Sach.TenSach,
        Sach.TacGia,
        MuonSach.NgayMuon,
        MuonSach.SoLuong,
        MuonSach.TrangThaiDuyet
    FROM 
        MuonSach
    JOIN 
        Sach ON MuonSach.MaSach = Sach.MaSach
    WHERE 
        MuonSach.MaNguoiDung = :maNguoiDung 
        AND MuonSach.IsDel = 1
");
$stmt->bindParam(':maNguoiDung', $maNguoiDung);
$stmt->execute();
$books = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Thông Tin Mượn Sách</h2>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Mã Mượn</th>
                <th>Tên Sách</th>
                <th>Tác Giả</th>
                <th>Ngày Mượn</th>
                <th>Số Lượng</th>
                <th>Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['MaMuon']); ?></td>
                        <td><?php echo htmlspecialchars($book['TenSach']); ?></td>
                        <td><?php echo htmlspecialchars($book['TacGia']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($book['NgayMuon']))); ?></td>
                        <td><?php echo htmlspecialchars($book['SoLuong']); ?></td>
                        <td><?php echo htmlspecialchars($book['TrangThaiDuyet']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Không có sách nào được mượn.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php


?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'footer.php'; ?>