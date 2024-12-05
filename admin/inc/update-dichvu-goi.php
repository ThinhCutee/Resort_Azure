<?php
session_start();

try {
    include("database.php");
    $conn = connect();
    // Kiểm tra nếu có 'id_goi_dich_vu' trong mảng $_POST
if (isset($_POST['id_goi_dich_vu']) && isset($_POST['selected_ids'])) {
    $id_goi_dich_vu = $_POST['id_goi_dich_vu']; // ID của gói dịch vụ mới
    $selected_ids = $_POST['selected_ids']; // Mảng các ID dịch vụ cần cập nhật

    // Chuyển mảng các ID thành chuỗi
    $selected_ids_str = implode(",", $selected_ids); // Các ID cách nhau bởi dấu phẩy
    // Chuẩn bị câu lệnh SQL UPDATE
    $sql = "UPDATE dichvu 
            SET id_goi_dich_vu = :id_goi_dich_vu 
            WHERE id IN ($selected_ids_str)"; // Chèn chuỗi các ID vào câu lệnh SQL

    // Chuẩn bị câu lệnh
    $stmt = $conn->prepare($sql);

    // Ràng buộc tham số
    $stmt->bindParam(':id_goi_dich_vu', $id_goi_dich_vu);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo "Cập nhật thành công cho các ID: " . implode(', ', $selected_ids);
    } else {
        echo "Lỗi: Không thể cập nhật.";
    }
} else {
    echo "Lỗi: Thiếu dữ liệu trong yêu cầu.";
}

// Đóng kết nối
$conn = null;
} catch (Exception $e) {
    $response = array(
        'success' => false,
        'message' => $e->getMessage()
    );
}

// Save response to session and redirect
// $_SESSION['response'] = $response;
// header("refresh:0; url='../goidichvu.php'");
?>
