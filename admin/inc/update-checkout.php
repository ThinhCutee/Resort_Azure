<?php
include("database.php");
$conn = connect();  // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy ID phòng đặt từ request POST
    if (isset($_POST['id_phong_dat'])) {
        $id_phong_dat = $_POST['id_phong_dat'];
        
        // Cập nhật trạng thái phòng
        $update_room_sql = "UPDATE phongdat SET trang_thai = 1, is_check_out = 1 WHERE id = :id_phong_dat";
        $stmt = $conn->prepare($update_room_sql);
        $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
        $room_updated = $stmt->execute();

        // Cập nhật trạng thái dịch vụ (tất cả các dịch vụ liên quan đến phòng đặt)
        $update_service_sql = "UPDATE dichvudat SET trang_thai = 1 WHERE id_phong_dat = :id_phong_dat";
        $stmt = $conn->prepare($update_service_sql);
        $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
        $service_updated = $stmt->execute();

        // Kiểm tra xem có cập nhật thành công hay không
        if ($room_updated && $service_updated) {
            // Trả về phản hồi thành công
            $response = array(
                'status' => 'success',
                'message' => 'Thanh toán thành công!'
            );
        } else {
            // Trả về phản hồi lỗi
            $response = array(
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật!'
            );
        }
    } else {
        // Nếu không có id_phong_dat
        $response = array(
            'status' => 'error',
            'message' => 'Yêu cầu không hợp lệ'
        );
    }
} else {
    // Phản hồi nếu không phải POST
    $response = array(
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ'
    );
}

// Trả về phản hồi dưới dạng JSON
echo json_encode($response);
?>
