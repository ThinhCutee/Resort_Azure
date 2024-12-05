<?php
session_start();

header('Content-Type: application/json');

// Kiểm tra phương thức yêu cầu
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
    exit;
}

// Lấy dữ liệu từ POST
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$id_goi_dich_vu = isset($_POST['id_goi_dich_vu']) ? intval($_POST['id_goi_dich_vu']) : null;

// Kiểm tra tính hợp lệ của dữ liệu
if (!$id || !$id_goi_dich_vu) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Dữ liệu không hợp lệ.'
    ]);
    exit;
}

try {
    include("database.php");
    $conn = connect();

    // Bắt đầu giao dịch
    $conn->beginTransaction();

    // Lấy giá của gói dịch vụ
    $stmt = $conn->prepare("SELECT gia FROM goidichvu WHERE id = :id_goi_dich_vu");
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();
    $giaGoiDichVu = $stmt->fetchColumn();

    if (!$giaGoiDichVu) {
        throw new Exception("Không tìm thấy gói dịch vụ với ID: $id_goi_dich_vu");
    }

    // Lấy giá của dịch vụ cần xóa
    $stmt = $conn->prepare("SELECT don_gia FROM dichvu WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $giaXoa = $stmt->fetchColumn();

    if (!$giaXoa) {
        throw new Exception("Không tìm thấy dịch vụ với ID: $id");
    }

    // Tính giá mới
    $giaUpdate = (($giaGoiDichVu / 0.9) - $giaXoa) * 0.9;

    // Cập nhật giá gói dịch vụ
    $stmt = $conn->prepare("UPDATE goidichvu SET gia = :gia WHERE id = :id_goi_dich_vu");
    $stmt->bindParam(':gia', $giaUpdate, PDO::PARAM_STR);
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu, PDO::PARAM_INT);
    $stmt->execute();

    // Xóa liên kết dịch vụ
    $stmt = $conn->prepare("UPDATE dichvu SET id_goi_dich_vu = NULL WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Hoàn tất giao dịch
    $conn->commit();

    // Phản hồi thành công
    echo json_encode([
        'status' => 'success',
        'message' => 'Xóa thành công!',
        'gia_moi' => number_format($giaUpdate, 0, '.', ',') . ' ₫'
    ]);
    exit;
} catch (Exception $e) {
    // Rollback nếu có lỗi
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // Phản hồi lỗi
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi: ' . $e->getMessage()
    ]);
    exit;
} finally {
    if (isset($stmt)) $stmt->closeCursor();
    if (isset($conn)) $conn = null;
}
