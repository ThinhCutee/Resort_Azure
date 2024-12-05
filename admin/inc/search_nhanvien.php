<?php
include_once("database.php");
$conn = connect();

// Lấy từ khóa tìm kiếm từ yêu cầu GET
$searchTen = isset($_GET['search']) ? $_GET['search'] : '';
$phongBan = isset($_GET['phongBan']) ? $_GET['phongBan'] : '';

// hàm tìm kiếm 
searchNV($searchTen, $phongBan);
?>
