<?php
// session_start();
include("database.php");
$conn = connect();

// Lấy danh sách ảnh của phòng này từ cơ sở dữ liệu
if (isset($_GET['id_goi_dich_vu'])) {
    $id_goi_dich_vu = intval($_GET['id_goi_dich_vu']);
    
    // Truy vấn lấy danh sách dịch vụ
    $sql = "SELECT * FROM dichvu WHERE id_goi_dich_vu = :id_goi_dich_vu";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();
    $goidv = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Truy vấn lấy thông tin gói dịch vụ
    $sql2 = "SELECT gia FROM goidichvu WHERE id = :id_goi_dich_vu";
    $stmt = $conn->prepare($sql2);  // Sửa lại tên query cho đúng
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();
    $gia = $stmt->fetch(PDO::FETCH_ASSOC); // Sử dụng fetch() thay vì fetchAll() để lấy một dòng duy nhất

} else {
    $_SESSION['response'] = array(
        'message' => 'ID gói không hợp lệ.',
        'success' => false
    );
    header("Location: ./chitiet-dichvu.php"); // Chuyển hướng người dùng về trang chủ hoặc trang khác tùy bạn
    exit;
}
?>
