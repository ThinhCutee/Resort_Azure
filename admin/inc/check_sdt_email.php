<?php
include_once('database.php');

if (isset($_POST['soDT'])) {
    $soDT = $_POST['soDT'];

    // Truy vấn để kiểm tra số điện thoại trong cơ sở dữ liệu và lấy email tương ứng
    $query = "SELECT email FROM khachhang WHERE sdt = ?";
    $params = array($soDT);
    $result = showTK($query, $params);

    // Trả về email nếu số điện thoại tồn tại trong cơ sở dữ liệu, ngược lại trả về chuỗi trống
    if (!empty($result)) {
        echo $result[0]['email'];
    } else {
        echo '';
    }
}
?>
