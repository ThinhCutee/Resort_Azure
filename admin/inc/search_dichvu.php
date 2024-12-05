<?php
include_once("database.php");
$conn = connect();

// Lấy từ khóa tìm kiếm từ yêu cầu GET
$search = isset($_GET['search']) ? $_GET['search'] : '';

// hàm tìm kiếm 
searchDV($search);
?>
