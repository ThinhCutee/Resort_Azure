<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nhận dữ liệu từ yêu cầu Ajax
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($id) {
        try {
            include("database.php");
            $conn = connect();

            // Sử dụng prepare statement để tránh SQL injection
            $sql = "DELETE FROM image_phong WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Xóa thành công!'
                );
            } else {
                $errorInfo = $stmt->errorInfo();
                $response = array(
                    'status' => 'error',
                    'message' => 'Không thể xóa dữ liệu.',
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
