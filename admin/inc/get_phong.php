<?php
include_once('database.php');

if (isset($_POST['khachSanId']) && isset($_POST['ngayNhanPhong']) && isset($_POST['ngayTraPhong'])) {
    $khachSanId = $_POST['khachSanId'];
    $ngayNhanPhong = $_POST['ngayNhanPhong'];
    $ngayTraPhong = $_POST['ngayTraPhong'];

    // Truy vấn danh sách phòng còn trống
    $phong = showTK("SELECT id, so_phong, hang_phong,ten_phong, so_nguoi FROM phong 
                   WHERE id_khach_san = ? AND id NOT IN (
                       SELECT id_phong FROM phongdat 
                       WHERE (ngay_nhan_phong <= ? AND ngay_tra_phong >= ?)
                   )", array($khachSanId, $ngayTraPhong, $ngayNhanPhong));

    echo json_encode(array('phong' => $phong));
}
?>
