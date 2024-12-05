<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nhận dữ liệu từ yêu cầu POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if (!$id) {
        $response = array(
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ.'
        );
    } else {
        try {
            include("database.php");
            $conn = connect();

            // Bắt đầu giao dịch
            $conn->beginTransaction();

            // Kiểm tra xem khách hàng có tài khoản liên quan hay không
            $sqlCheck = "SELECT COUNT(*) AS total FROM khachhang WHERE id_tai_khoan = :id";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                // Nếu có nhân viên liên quan, không cho phép xóa
                $response = array(
                    'status' => 'error',
                    'message' => 'Không thể xóa. Khách hàng này đã có tài khoản'
                );
                $conn->rollBack();
            } else {
                // Xóa tài khoản admin
                $sqlAdmin = "DELETE FROM khachhang WHERE id = :id";
                $stmtAdmin = $conn->prepare($sqlAdmin);
                $stmtAdmin->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtAdmin->execute();

                // Hoàn tất giao dịch
                $conn->commit();

                $response = array(
                    'status' => 'success',
                    'message' => 'Xóa thông tin khách hàng thành công.'
                );
            }
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $response = array(
                'status' => 'error',
                'message' => 'Lỗi xảy ra: ' . $e->getMessage()
            );
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $response = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }

        // Đóng kết nối
        $conn = null;
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
