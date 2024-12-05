<?php
include_once('database.php');

if (isset($_POST['soPhong'])) {
    $soPhong = $_POST['soPhong'];

    // Truy vấn để lấy tên phòng tương ứng với số phòng đã chọn
    $query = "SELECT ten_phong FROM phong WHERE id = ?";
    $params = array($soPhong);
    $result = showTK($query, $params);

    // Trả về tên phòng
    echo $result[0]['ten_phong'];
}
?>
