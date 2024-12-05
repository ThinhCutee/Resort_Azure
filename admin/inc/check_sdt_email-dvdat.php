<?php
include_once('database.php');

if (isset($_POST['soDT'])) {
    $soDT = $_POST['soDT'];

    // Kết nối cơ sở dữ liệu
    $conn = connect();

    // Truy vấn kiểm tra số điện thoại và lấy email nếu hợp lệ
    $query = "SELECT k.email 
              FROM phongdat d 
              JOIN khachhang k ON d.id_khach_hang = k.id 
              WHERE k.sdt = ? 
                AND (
                    CURDATE() BETWEEN d.ngay_nhan_phong AND d.ngay_tra_phong
                    OR TIMESTAMPDIFF(HOUR, d.ngay_tra_phong, NOW()) <= 24
                )";
    $stmt = $conn->prepare($query);
    $stmt->execute([$soDT]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Trả về email nếu hợp lệ
    if ($result) {
        echo $result['email'];
    } else {
        // Trả về thông báo lỗi
        echo 'EXPIRED'; // Gửi mã lỗi "EXPIRED" về cho Ajax
    }

    $conn = null;
}
?>
