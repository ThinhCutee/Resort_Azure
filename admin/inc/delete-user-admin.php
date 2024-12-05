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

            // Xóa nhân viên liên quan
            $sqlNhanVien = "DELETE FROM nhanvien WHERE id_admin = :id_admin";
            $stmtNhanVien = $conn->prepare($sqlNhanVien);
            $stmtNhanVien->bindParam(':id_admin', $id, PDO::PARAM_INT);
            $stmtNhanVien->execute();

            // Xóa admin
            $sqlAdmin = "DELETE FROM admin WHERE id = :id";
            $stmtAdmin = $conn->prepare($sqlAdmin);
            $stmtAdmin->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtAdmin->execute();

            // Hoàn tất giao dịch
            $conn->commit();

            $response = array(
                'status' => 'success',
                'message' => 'Tài khoản admin và nhân viên liên quan đã được xóa thành công.'
            );
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
