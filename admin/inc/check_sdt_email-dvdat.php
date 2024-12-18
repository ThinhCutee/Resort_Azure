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
                SELECT kh.email, pd.id, p.so_phong
                FROM khachhang kh
                JOIN phongdat pd ON kh.id = pd.id_khach_hang
                JOIN phong p ON p.id = pd.id_phong 
                WHERE kh.sdt = :soDT
                  AND pd.ngay_nhan_phong <= :currentDate
                  AND pd.ngay_tra_phong >= :currentDate
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':soDT', $soDT, PDO::PARAM_STR);
            $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                // Lấy email từ kết quả
                $email = $result[0]['email'];
                $phongList = array_map(function ($row) {
                    return [
                    'so_phong' => $row['so_phong'], 
                    'id_phongdat'=> $row['id']
                    ];
                }, $result);

                // Trả về email và danh sách phòng
                echo json_encode([
                    'status' => 'success',
                    'email' => $email,
                    'phong' => $phongList
                ]);
            } else {
                // Nếu không tìm thấy phòng hợp lệ
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phòng đặt của khách hàng trong thời gian hiện tại!'
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
