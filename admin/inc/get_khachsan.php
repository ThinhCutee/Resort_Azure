<?php
include_once('database.php');

if (isset($_POST['ngayNhanPhong']) && isset($_POST['ngayTraPhong'])) {
    $ngayNhanPhong = $_POST['ngayNhanPhong'];
    $ngayTraPhong = $_POST['ngayTraPhong'];

    // Truy vấn danh sách khách sạn có phòng còn trống trong khoảng thời gian này
    $khachsan = showTK("SELECT DISTINCT ks.id, ks.ten_khach_san FROM khachsan ks
                      JOIN phong p ON ks.id = p.id_khach_san
                      WHERE p.id NOT IN (
                          SELECT id_phong FROM phongdat 
                          WHERE (ngay_nhan_phong <= ? AND ngay_tra_phong >= ?)
                      )", array($ngayTraPhong, $ngayNhanPhong));

    echo json_encode(array('khachsan' => $khachsan));
}
?>
