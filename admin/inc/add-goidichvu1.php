<?php
session_start();
include("database.php");

$conn = connect(); // Kết nối đến CSDL

// Lấy dữ liệu từ yêu cầu POST
$ten_goi_dich_vu = $_POST['ten_goi_dich_vu'];
$gia = $_POST['gia'];
$mo_ta = $_POST['mo_ta'];
$selected_ids = $_POST['selected_ids']; // Mảng các ID dịch vụ cần cập nhật

// Chuyển mảng các ID thành chuỗi để dùng trong câu lệnh SQL
$selected_ids_str = implode(",", $selected_ids);

try {
    // Bắt đầu giao dịch
    $conn->beginTransaction();

    // Thêm gói dịch vụ mới vào bảng `goidichvu`
    $insertSql = "INSERT INTO goidichvu (ten_goi_dich_vu, gia, mo_ta) VALUES (:ten_goi_dich_vu, :gia, :mo_ta)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bindParam(':ten_goi_dich_vu', $ten_goi_dich_vu);
    $stmt->bindParam(':gia', $gia);
    $stmt->bindParam(':mo_ta', $mo_ta);
    $stmt->execute();

    // Lấy ID của gói dịch vụ vừa thêm
    $newId = $conn->lastInsertId();

    // Cập nhật các dịch vụ với ID gói dịch vụ mới
    $updateSql = "UPDATE dichvu SET id_goi_dich_vu = :id_goi_dich_vu WHERE id IN ($selected_ids_str)";
    $stmtUpdate = $conn->prepare($updateSql);
    $stmtUpdate->bindParam(':id_goi_dich_vu', $newId);
    $stmtUpdate->execute();

    // Hoàn tất giao dịch
    $conn->commit();

    // Phản hồi thành công
    echo json_encode([
        'success' => true,
        'message' => 'Thêm gói dịch vụ và cập nhật dịch vụ thành công!',
        'gia' => $gia
    ]);
} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollBack();

    // Phản hồi lỗi
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Đóng kết nối
$conn = null;
?>
