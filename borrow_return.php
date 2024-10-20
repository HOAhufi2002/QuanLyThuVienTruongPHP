<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu chưa đăng nhập
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maMuon = $_POST['MaMuon'];
    $maNguoiDung = $_SESSION['user_id']; // Lấy mã người dùng từ session
    $ngayTra = date('Y-m-d'); // Ngày trả sách là ngày hiện tại
    $soLuongTra = $_POST['SoLuongTra'];
    $tinhTrangSach = $_POST['TinhTrangSach'];
    $ghiChu = "Trả sách vào ngày " . $ngayTra; // Ghi chú tự động

    // Cập nhật thông tin trả sách
    $stmt = $pdo->prepare("INSERT INTO TraSach (MaMuon, MaNguoiDung, NgayTra, SoLuongTra, TinhTrangSach, GhiChu, IsDel) 
                            VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->execute([$maMuon, $maNguoiDung, $ngayTra, $soLuongTra, $tinhTrangSach, $ghiChu]);

    // Cập nhật trạng thái sách trong bảng Sach
    $stmt = $pdo->prepare("UPDATE Sach SET SoLuong = SoLuong + ? WHERE MaSach = (SELECT MaSach FROM MuonSach WHERE MaMuon = ?)");
    $stmt->execute([$soLuongTra, $maMuon]);

    // Cập nhật trạng thái duyệt trong bảng MuonSach thành NULL
    $stmt = $pdo->prepare("UPDATE MuonSach SET TrangThaiDuyet = Đã trả sách WHERE MaMuon = ?");
    $stmt->execute([$maMuon]);

    header("Location: trat_sach.php?success=1"); // Chuyển hướng đến trang này với thông báo thành công
    exit;
}

// Lấy danh sách sách đang được mượn của người dùng
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT MuonSach.MaMuon,nguoidung.HoTen ,nguoidung.SoDienThoai , NgayMuon, Sach.TenSach, MuonSach.SoLuong 
FROM MuonSach ,  Sach , nguoidung  where  MuonSach.MaSach = Sach.MaSach  and NguoiDung.MaNguoiDung = MuonSach.MaNguoiDung
                        and  MuonSach.TrangThaiDuyet = 'duyet' AND MuonSach.IsDel = 1");
$stmt->execute([$userId]);
$muonSachList = $stmt->fetchAll();

include 'header.php'; // Bao gồm header
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Trả Sách</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Trả sách thành công!</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table">
                <tr>
                    <th>Mã Mượn</th>
                    <th>Họ tên</th>
                    <th>Số điện thoại</th>
                    <th>Tên Sách</th>
                    <th>Số Lượng Mượn</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($muonSachList as $item): ?>
                    <tr>
                        <td><?php echo $item['MaMuon']; ?></td>
                        <td><?php echo htmlspecialchars($item['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($item['SoDienThoai']); ?></td>

                        <td><?php echo htmlspecialchars($item['TenSach']); ?></td>

                        <td><?php echo $item['SoLuong']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#returnModal<?php echo $item['MaMuon']; ?>">Trả</button>
                        </td>
                    </tr>

                    <!-- Modal Nhập Ghi Chú Trả Sách -->
                    <div class="modal fade" id="returnModal<?php echo $item['MaMuon']; ?>" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Trả Sách: <?php echo htmlspecialchars($item['TenSach']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="MaMuon" value="<?php echo $item['MaMuon']; ?>">
                                        <div class="mb-3">
                                            <label for="SoLuongTra" class="form-label">Số Lượng Trả</label>
                                            <input type="number" class="form-control" name="SoLuongTra" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="TinhTrangSach" class="form-label">Tình Trạng Sách</label>
                                            <select class="form-select" name="TinhTrangSach" required>
                                                <option value="tot">Tốt</option>
                                                <option value="hong">Hỏng</option>
                                                <option value="mat">Mất</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Trả Sách</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; // Bao gồm footer ?>
