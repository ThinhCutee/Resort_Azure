<?php
include("database.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dich_vu_dat = isset($_POST['id_dich_vu_dat']) ? $_POST['id_dich_vu_dat'] : null;

    if ($id_dich_vu_dat) {
        try {
            // Kiểm tra số lần sử dụng và giới hạn dịch vụ
            $sql = "SELECT dv.gioi_han, dvdat.so_lan_su_dung 
                    FROM dichvudat dvdat
                    JOIN dichvu dv ON dv.id = dvdat.id_dich_vu
                    WHERE dvdat.id = :id_dich_vu_dat";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_dich_vu_dat', $id_dich_vu_dat, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $gioi_han = $data['gioi_han'];
                $so_lan_su_dung = $data['so_lan_su_dung'];

                // Kiểm tra nếu số lần sử dụng vượt quá giới hạn
                if ($so_lan_su_dung > $gioi_han) {
                    // Nếu vượt quá giới hạn, yêu cầu xác nhận
                    echo json_encode(array(
                        'success' => true,
                        'confirm' => true,
                        'message' => "Số lần sử dụng đã vượt quá giới hạn. Bạn có muốn tiếp tục?"
                    ));
                } else {
                    // Nếu không, không cần xác nhận
                    echo json_encode(array(
                        'success' => true,
                        'confirm' => false,
                        'message' => 'Số lần sử dụng dịch vụ được cập nhật thành công!'
                    ));
                }
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Dịch vụ không tồn tại hoặc không hợp lệ.'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Lỗi xảy ra: ' . $e->getMessage()
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'ID dịch vụ không hợp lệ.'
        ));
    }
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ.'
    ));
}
?>
