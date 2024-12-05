<?php
include_once("database.php");
$conn = connect();

// Lấy từ khóa tìm kiếm từ yêu cầu GET
$sdt = $_POST['sdt'] ?? null;
$ngayBatDau = $_POST['ngayBatDau'] ?? null;
$ngayKetThuc = $_POST['ngayKetThuc'] ?? null;

// hàm tìm kiếm 
search_vemaybay($sdt, $ngayBatDau, $ngayKetThuc)

?>
