<?php
include("database.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soDT = isset($_POST['soDT']) ? trim($_POST['soDT']) : '';

    if (!empty($soDT)) {
        try {
            // Truy vấn thông tin khách hàng dựa trên số điện thoại
            $sql = "
                SELECT kh.id, kh.ho, kh.ten, kh.sdt, kh.email, kh.dia_chi
                FROM khachhang kh
                WHERE kh.sdt = :soDT
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':soDT', $soDT, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'khachhang' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy khách hàng với số điện thoại này.'
                ]);
            }
        } catch (PDOException $e) {
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
