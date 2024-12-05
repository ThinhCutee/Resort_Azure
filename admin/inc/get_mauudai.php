<?php
include_once('database.php');

if (isset($_POST['tenUuDai'])) {
    $tenUuDai = $_POST['tenUuDai'];

    // Truy vấn để lấy mã ưu đãi tương ứng với tên ưu đãi đã chọn
    $query = "SELECT id FROM uudai WHERE ten_uu_dai = ?";
    $params = array($tenUuDai);
    $result = showTK($query, $params);

    // Trả về mã ưu đãi
    echo $result[0]['maUuDai'];
}
?>
