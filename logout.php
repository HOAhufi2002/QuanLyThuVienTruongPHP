<?php
session_start(); // Bắt đầu session

// Hủy tất cả session của người dùng
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập (login.php)
header("Location: login.php");
exit;
?>
