<?php
session_start();

// Kiểm tra xem request có phải là POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $tenGoi = isset($_POST['tenGoi']) ? $_POST['tenGoi'] : null;
    $gia = isset($_POST['gia']) ? doubleval($_POST['gia']) : null;
    $moTa = isset($_POST['moTa']) ? $_POST['moTa'] : null;
    

    try {
        include("database.php");
        $conn = connect();

        // Prepare the SQL statement with named placeholders
        $sql = "UPDATE goidichvu SET ten_goi_dich_vu = :tenGoi, mo_ta = :moTa WHERE id = :id";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':tenGoi', $tenGoi, PDO::PARAM_STR);
        //$stmt->bindParam(':gia', $gia, PDO::PARAM_STR);
        $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            );
            direct("../goidichvu.php");
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
        direct("../goidichvu.php");
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

