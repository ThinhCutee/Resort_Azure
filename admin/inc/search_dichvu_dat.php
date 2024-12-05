<?php
include_once("database.php");
$conn = connect();

// Lấy từ khóa tìm kiếm từ yêu cầu GET
$search = isset($_GET['search']) ? $_GET['search'] : '';
$loai_dich_vu = isset($_GET['loai_dich_vu']) ? $_GET['loai_dich_vu'] : '';
// hàm tìm kiếm 
searchDVDat($search, $loai_dich_vu);
?>
