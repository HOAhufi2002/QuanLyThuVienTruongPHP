<?php
// Thông tin cấu hình để kết nối với SQL Server (không cần username và password)
$host = 'minhhoa';   // Tên máy chủ hoặc địa chỉ IP của SQL Server
$db = 'quanlytruonghoc';   // Tên cơ sở dữ liệu bạn muốn kết nối
$charset = 'utf8';            // Mã hóa ký tự

// Thiết lập DSN (Data Source Name) cho SQL Server với Windows Authentication
$dsn = "sqlsrv:Server=$host;Database=$db;TrustServerCertificate=true";

// Các tùy chọn cho PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Báo lỗi dưới dạng exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Lấy dữ liệu dưới dạng mảng kết hợp
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Sử dụng prepared statements thực sự
];

try {
    // Kết nối với SQL Server bằng PDO mà không cần username và password
    $pdo = new PDO($dsn, null, null, $options);
} catch (PDOException $e) {
    // Xử lý lỗi nếu không kết nối được
    die('Kết nối đến cơ sở dữ liệu thất bại: ' . $e->getMessage());
}
?>
