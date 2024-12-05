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
            
            // Kiểm tra trạng thái thanh toán và số lần sử dụng
            $sqlCheck = "SELECT trang_thai, so_lan_su_dung FROM dichvudat WHERE id = :id";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            $checkTT = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra nếu trạng thái đã thanh toán hoặc số lần sử dụng > 0 thì không cho hủy
            if ($checkTT['trang_thai'] == 1) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Dịch vụ đã thanh toán, không thể hủy!'
                );
            } elseif ($checkTT['so_lan_su_dung'] > 0) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Dịch vụ đã được sử dụng, không thể hủy!'
                );
            } else {
                // Nếu chưa thanh toán và chưa sử dụng, thực hiện xóa dịch vụ
                $sqlDelete = "DELETE FROM dichvudat WHERE id = :id AND trang_thai = 0";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtDelete->execute();

                // Hoàn tất giao dịch
                $conn->commit();

                $response = array(
                    'status' => 'success',
                    'message' => 'Hủy dịch vụ đặt thành công!'
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
