<?php
include("database.php");

$response = array('success' => false, 'data' => [], 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_goi_dich_vu'])) {
    $idGoiDichVu = $_POST['id_goi_dich_vu'];

    try {
        $conn = connect();

        // Lấy danh sách dịch vụ thuộc gói
        $stmt = $conn->prepare("SELECT ten_dich_vu, don_gia, loai_dich_vu 
                                FROM dichvu 
                                WHERE id_goi_dich_vu = :id_goi_dich_vu");
        $stmt->bindParam(':id_goi_dich_vu', $idGoiDichVu, PDO::PARAM_INT);
        $stmt->execute();

        $dichVuList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // if (!empty($dichVuList)) {
            $response['success'] = true;
            $response['data'] = $dichVuList;
        // } else {
        //     $response['message'] = 'Không có dịch vụ nào trong gói này.';
        // }

        $conn = null;
    } catch (Exception $e) {
        $response['message'] = 'Lỗi khi truy xuất dữ liệu: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Yêu cầu không hợp lệ.';
}

// Trả về kết quả JSON
echo json_encode($response);
