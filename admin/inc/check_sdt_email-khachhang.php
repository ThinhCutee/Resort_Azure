<?php
include("database.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soDT = isset($_POST['soDT']) ? trim($_POST['soDT']) : '';

    if (!empty($soDT)) {
        try {
            $currentDate = date("Y-m-d");

            // Truy vấn để lấy email và danh sách mã phòng
            $sql = "
                SELECT kh.email
                FROM khachhang kh
                WHERE kh.sdt = :soDT
                 
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':soDT', $soDT, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                // Lấy email từ kết quả
                $email = $result[0]['email'];

                // Trả về email và danh sách phòng
                echo json_encode([
                    'status' => 'success',
                    'email' => $email,
                ]);
            } else {
                // Nếu không tìm thấy phòng hợp lệ
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm khách hàng !!!'
                ]);
            }
        } catch (PDOException $e) {
            // Lỗi kết nối hoặc truy vấn
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Số điện thoại không hợp lệ.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
}
?>
