<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php"); // Chuyển hướng tới trang đăng nhập nếu không phải admin
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
        $stmt = $pdo->prepare("UPDATE Sach SET TrangThai = 'Tốt' WHERE MaSach = (SELECT MaSach FROM MuonSach WHERE MaMuon = :maMuon)");
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

// Kiểm tra xem có mã sách được gửi không
if (isset($_GET['MaSach'])) {
    $maSach = $_GET['MaSach'];

    // Truy vấn chi tiết sách
    $stmt = $pdo->prepare("SELECT Soluong, Sach.MaSach, Sach.TenSach, Sach.TacGia, Sach.MoTa, Sach.HinhAnh, LoaiSach.TenLoai, Sach.NamXuatBan, Sach.TrangThai 
                           FROM Sach 
                           JOIN LoaiSach ON Sach.MaLoai = LoaiSach.MaLoai 
                           WHERE Sach.MaSach = ? AND Sach.IsDel = 1");
    $stmt->execute([$maSach]);
    $row = $stmt->fetch();

    if ($row) {
        // Hiển thị chi tiết sách trong modal
        $imagePath = !empty($row['HinhAnh']) ? $row['HinhAnh'] : '/images/default_book.jpg';

        echo '
        <div class="modal-header">
            <h5 class="modal-title">' . $row['TenSach'] . '</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="' . $imagePath . '" class="img-fluid rounded shadow-sm" alt="' . $row['TenSach'] . '">
                </div>
                <div class="col-md-8">
                    <h5 class="mb-3">Thông Tin Chi Tiết</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Tác giả:</strong> ' . $row['TacGia'] . '</li>
                        <li class="list-group-item"><strong>Thể loại:</strong> ' . $row['TenLoai'] . '</li>
                        <li class="list-group-item"><strong>Năm xuất bản:</strong> ' . $row['NamXuatBan'] . '</li>
                        <li class="list-group-item"><strong>Tình trạng:</strong> ' . ($row['TrangThai'] == 'Tốt' ? '<span class="badge bg-success">Còn</span>' : '<span class="badge bg-danger">Hỏng</span>') . '</li>
                        <li class="list-group-item"><strong>Số lượng còn:</strong> ' . $row['Soluong'] . '</li>

                        </ul>
                    <div class="mt-3">
                        <h6>Mô tả</h6>
                        <p>' . nl2br($row['MoTa']) . '</p>
                    </div>
               
                </div>
            </div>
        </div>
        <div class="modal-footer">
        </div>';
    } else {
        echo '<div class="modal-body">Không tìm thấy sách.</div>';
    }
} else {
    echo '<div class="modal-body">Lỗi: Không có thông tin sách.</div>';
}
?>
