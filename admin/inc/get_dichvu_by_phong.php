<?php
include("database.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_phong_dat = isset($_POST['id_phong_dat']) ? trim($_POST['id_phong_dat']) : '';
    if (!empty($id_phong_dat)) {
        try {
            // Truy vấn danh sách dịch vụ theo mã phòng
            $sql = "
                SELECT 
                dvdat.id AS id_dich_vu, 
                dv.ten_dich_vu AS ten_dich_vu, 
                dvdat.so_lan_su_dung, 
                dv.don_gia AS gia,
                dvdat.ngay_dat as ngay_dat,
                dv.gioi_han as gioi_han,
                dvdat.trang_thai as trang_thai
                FROM dichvudat dvdat
                JOIN dichvu dv ON dvdat.id_dich_vu = dv.id
                WHERE dvdat.id_phong_dat = :id_phong_dat AND dvdat.trang_thai = 0
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'dichVu' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy dịch vụ nào cho phòng này.'
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
            'message' => 'Mã phòng không hợp lệ.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
}
?>
