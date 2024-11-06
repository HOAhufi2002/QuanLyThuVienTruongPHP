<?php
session_start();
include 'config.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Truy vấn danh sách người đã mượn và trả sách
$stmt = $pdo->prepare("SELECT DISTINCT nguoidung.MaNguoiDung, nguoidung.HoTen, nguoidung.SoDienThoai, sach.TenSach, trasach.NgayTra, trasach.SoLuongTra
                       FROM NguoiDung nguoidung
                       JOIN MuonSach muonsach ON nguoidung.MaNguoiDung = muonsach.MaNguoiDung
                       JOIN TraSach trasach ON muonsach.MaMuon = trasach.MaMuon
                       JOIN Sach sach ON muonsach.MaSach = sach.MaSach
                       WHERE muonsach.TrangThaiDuyet = 'Đã trả sách'
                       ORDER BY trasach.NgayTra DESC");
$stmt->execute();
$traSachList = $stmt->fetchAll();

include 'header.php'; // Bao gồm header
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Danh sách Người Đã Mượn và Trả Sách</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Cập nhật thành công!</div>
    <?php endif; ?>

    <!-- Ô tìm kiếm -->

    <form style="border-radius: 50px;" class="col-md-6 text-end">
        <div class="input-group">
            <input style="border-radius: 50px;" type="text" id="search" class="form-control" placeholder="Tìm kiếm theo tên người mượn">
        </div>
    </form>
    <br>
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="traSachTable">
            <thead>
                <tr>
                    <th>Mã Người Dùng</th>
                    <th>Họ Tên</th>
                    <th>Số Điện Thoại</th>
                    <th>Tên Sách</th>
                    <th>Ngày Trả</th>
                    <th>Số Lượng Trả</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($traSachList as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['MaNguoiDung']); ?></td>
                        <td><?php echo htmlspecialchars($item['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($item['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($item['TenSach']); ?></td>
                        <td><?php echo htmlspecialchars($item['NgayTra']); ?></td>
                        <td><?php echo htmlspecialchars($item['SoLuongTra']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script tìm kiếm -->
<script>
    document.getElementById("search").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#traSachTable tbody tr");

        rows.forEach(row => {
            let nameCell = row.querySelector("td:nth-child(2)"); // Lấy ô thứ hai (Họ Tên)
            if (nameCell) {
                let name = nameCell.textContent.toLowerCase();
                row.style.display = name.includes(filter) ? "" : "none";
            }
        });
    });
</script>

<?php include 'footer.php'; // Bao gồm footer 
?>