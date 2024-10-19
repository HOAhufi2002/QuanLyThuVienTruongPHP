<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: home.php"); // Chuyển hướng tới trang đăng nhập nếu không phải admin
    exit;
}

// Xử lý yêu cầu phê duyệt hoặc từ chối mượn sách
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maMuon = $_POST['MaMuon'];

    if (isset($_POST['duyet'])) {
        // Phê duyệt yêu cầu mượn sách
        $stmt = $pdo->prepare("UPDATE MuonSach SET TrangThaiDuyet = 'duyet' WHERE MaMuon = :maMuon");
        $stmt->bindParam(':maMuon', $maMuon);
        $stmt->execute();

        // Cập nhật trạng thái sách thành "đang mượn"
        $stmt = $pdo->prepare("UPDATE Sach SET TinhTrang = 'mat' WHERE MaSach = (SELECT MaSach FROM MuonSach WHERE MaMuon = :maMuon)");
        $stmt->bindParam(':maMuon', $maMuon);
        $stmt->execute();

        $success = "Yêu cầu đã được duyệt!";
    }

    if (isset($_POST['tuchoi'])) {
        // Truy xuất mã sách từ bảng MuonSach
        $stmt = $pdo->prepare("SELECT MaSach FROM MuonSach WHERE MaMuon = :maMuon");
        $stmt->bindParam(':maMuon', $maMuon);
        $stmt->execute();
        $maSach = $stmt->fetchColumn();

        // Từ chối yêu cầu mượn sách
        $stmt = $pdo->prepare("UPDATE MuonSach SET TrangThaiDuyet = 'tuchoi' WHERE MaMuon = :maMuon");
        $stmt->bindParam(':maMuon', $maMuon);
        $stmt->execute();

        // Cộng lại số lượng sách trong bảng Sach
        $stmt = $pdo->prepare("UPDATE Sach SET SoLuong = SoLuong + 1 WHERE MaSach = :maSach");
        $stmt->bindParam(':maSach', $maSach);
        $stmt->execute();

        $error = "Yêu cầu đã bị từ chối và số lượng sách đã được cập nhật lại!";
    }
}

// Truy vấn danh sách các yêu cầu mượn sách
$stmt = $pdo->query("SELECT MuonSach.MaMuon, MuonSach.NgayMuon, MuonSach.TrangThaiDuyet, MuonSach.SoLuong, 
                     NguoiDung.HoTen, Sach.TenSach, Sach.TacGia
                     FROM MuonSach
                     JOIN NguoiDung ON MuonSach.MaNguoiDung = NguoiDung.MaNguoiDung
                     JOIN Sach ON MuonSach.MaSach = Sach.MaSach
                     WHERE MuonSach.TrangThaiDuyet = 'choduyet'");
$requests = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-5">Quản Lý Yêu Cầu Mượn Sách</h2>

    <!-- Hiển thị thông báo thành công hoặc lỗi -->
    <?php if (!empty($success)) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (!empty($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Tên Người Mượn</th>
                            <th>Tên Sách</th>
                            <th>Tác Giả</th>
                            <th>Số Lượng</th>
                            <th>Ngày Mượn</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['HoTen']); ?></td>
                                <td><?php echo htmlspecialchars($request['TenSach']); ?></td>
                                <td><?php echo htmlspecialchars($request['TacGia']); ?></td>
                                <td><?php echo htmlspecialchars($request['SoLuong']); ?></td>
                                <td><?php echo htmlspecialchars($request['NgayMuon']); ?></td>
                                <td><?php echo htmlspecialchars($request['TrangThaiDuyet']); ?></td>
                                <td>
                                    <form method="POST" action="quanlymuonsach.php">
                                        <input type="hidden" name="MaMuon" value="<?php echo $request['MaMuon']; ?>">
                                        <button type="submit" name="duyet" class="btn btn-success btn-sm">Duyệt</button>
                                        <button type="submit" name="tuchoi" class="btn btn-danger btn-sm">Từ chối</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (count($requests) == 0): ?>
                    <div class="alert alert-info">Không có yêu cầu mượn sách nào đang chờ duyệt.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
