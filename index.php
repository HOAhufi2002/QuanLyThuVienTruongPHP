<?php include 'header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Thống Kê Số Lượng Sách Theo Loại</h1>

    <!-- Biểu đồ cột -->
    <div class="row mt-5">
        <div class="col-md-6">
            <canvas id="barChart"></canvas>
        </div>

        <!-- Biểu đồ tròn -->
        <!-- Biểu đồ tròn nhỏ hơn -->
        <div class="col-md-5">
            <canvas id="pieChart" style="width: 3px; height: 1030px;"></canvas> <!-- Điều chỉnh kích thước ở đây -->
        </div>
    </div>
    <br>
</div>

<?php
// Kết nối cơ sở dữ liệu
include 'config.php';

// Truy vấn dữ liệu số lượng sách theo loại sách
$stmt = $pdo->query("SELECT LoaiSach.TenLoai, COUNT(Sach.MaSach) AS SoLuong
                     FROM Sach
                     JOIN LoaiSach ON Sach.MaLoai = LoaiSach.MaLoai
                     WHERE Sach.IsDel = 1
                     GROUP BY LoaiSach.TenLoai");

$loaiSach = [];
$soLuongSach = [];

// Lưu kết quả truy vấn vào mảng
while ($row = $stmt->fetch()) {
    $loaiSach[] = $row['TenLoai'];
    $soLuongSach[] = $row['SoLuong'];
}
?>

<!-- Include thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript để vẽ biểu đồ -->
<script>
    // Biểu đồ cột (Bar Chart)
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctxBar, {
        type: 'bar', // Loại biểu đồ cột
        data: {
            labels: <?php echo json_encode($loaiSach); ?>, // Gán tên loại sách
            datasets: [{
                label: 'Số lượng sách',
                data: <?php echo json_encode($soLuongSach); ?>, // Gán số lượng sách tương ứng
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Bắt đầu từ 0 trên trục Y
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            }
        }
    });

    // Biểu đồ tròn (Pie Chart)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie', // Loại biểu đồ tròn
        data: {
            labels: <?php echo json_encode($loaiSach); ?>, // Gán tên loại sách
            datasets: [{
                label: 'Số lượng sách',
                data: <?php echo json_encode($soLuongSach); ?>, // Gán số lượng sách tương ứng
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            }
        }
    });
</script>

<?php include 'footer.php'; ?>