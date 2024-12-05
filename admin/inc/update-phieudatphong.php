<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nhận dữ liệu từ yêu cầu Ajax
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $trangThai = 3;
    if ($id) {
        try {
            include("database.php");
            $conn = connect();

            // Sử dụng prepare statement để tránh SQL injection
            $sql = "UPDATE phongdat SET trang_thai = :trangThai WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Hủy phiếu đặt phòng thành công!'
                );
            } else {
                $errorInfo = $stmt->errorInfo();
                $response = array(
                    'status' => 'error',
                    'message' => 'Không thể update dữ liệu.',
                    'errorInfo' => $errorInfo
                );
            }

            $stmt->closeCursor();
            $conn = null;
        } catch (PDOException $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Lỗi xảy ra: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ.'
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    );
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
