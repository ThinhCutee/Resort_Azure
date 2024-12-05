<?php
session_start();
include("database.php");

$conn = connect(); // Kết nối đến CSDL

// Lấy dữ liệu từ yêu cầu POST
$selected_ids = isset($_POST['selected_ids']) ? $_POST['selected_ids'] : [];
$id_goi_dich_vu = isset($_POST['id_goi_dich_vu']) ? intval($_POST['id_goi_dich_vu']) : null;

if (empty($selected_ids) || !$id_goi_dich_vu) {
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ hoặc thiếu thông tin.'
    ]);
    exit;
}

try {
    // Bắt đầu giao dịch
    $conn->beginTransaction();

    // Lấy tổng giá của các dịch vụ hiện tại trong gói
    $stmt = $conn->prepare("SELECT SUM(don_gia) AS tong_gia_hien_tai FROM dichvu WHERE id_goi_dich_vu = :id_goi_dich_vu");
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();
    $tongGiaHienTai = $stmt->fetchColumn();

    // Nếu không có dịch vụ nào trong gói, đặt giá hiện tại là 0
    if (!$tongGiaHienTai) {
        $tongGiaHienTai = 0;
    }

    // Lấy tổng giá của các dịch vụ mới được thêm vào gói
    $selected_ids_str = implode(',', array_map('intval', $selected_ids)); // Bảo vệ chống SQL Injection
    $stmt = $conn->prepare("SELECT SUM(don_gia) AS tong_gia_moi FROM dichvu WHERE id IN ($selected_ids_str)");
    $stmt->execute();
    $tongGiaMoi = $stmt->fetchColumn();

    // Nếu không có dịch vụ nào trong danh sách thêm mới, đặt giá là 0
    if (!$tongGiaMoi) {
        $tongGiaMoi = 0;
    }

    // Tính tổng giá trị của gói dịch vụ mới
    $tongGiaDichVu = $tongGiaHienTai + $tongGiaMoi;

    // Áp dụng giảm giá 10% để tính giá gói
    $giaMoi = $tongGiaDichVu * 0.9;

    // Cập nhật giá gói dịch vụ
    $stmt = $conn->prepare("UPDATE goidichvu SET gia = :gia WHERE id = :id_goi_dich_vu");
    $stmt->bindParam(':gia', $giaMoi, PDO::PARAM_STR);
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();

    // Cập nhật id_goi_dich_vu trong bảng dịch vụ mới
    $stmt = $conn->prepare("UPDATE dichvu SET id_goi_dich_vu = :id_goi_dich_vu WHERE id IN ($selected_ids_str)");
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();

    // Hoàn tất giao dịch
    $conn->commit();

    // Phản hồi thành công
    echo json_encode([
        'success' => true,
        'message' => 'Gói dịch vụ đã được cập nhật thành công!',
        'gia_moi' => $giaMoi
    ]);
} catch (Exception $e) {
    // Rollback nếu có lỗi
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Phản hồi lỗi
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Đóng kết nối
$conn = null;
?>
