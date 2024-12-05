<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $admin_name = isset($_POST['admin_name']) ? $_POST['admin_name'] : null;
    $admin_pass = isset($_POST['admin_pass']) ? $_POST['admin_pass'] : null;
    $trangThai = isset($_POST['trangThai']) ? intval($_POST['trangThai']) : null;
    
    try {
        include("database.php");
        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection
        $sql = "UPDATE admin SET admin_name = :admin_name, admin_pass = :admin_pass, trang_thai = :trangThai WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':admin_name', $admin_name, PDO::PARAM_STR);
        $stmt->bindParam(':admin_pass', $admin_pass, PDO::PARAM_STR);
        $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            );
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể cập nhật dữ liệu.',
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

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Nếu không phải là request POST, trả về lỗi
    $response = array(
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ'
    );

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
