<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu ajax
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $ho = isset($_POST['ho']) ? $_POST['ho'] : null;
    $ten = isset($_POST['ten']) ? $_POST['ten'] : null;
    $soDT = isset($_POST['sdt']) ? $_POST['sdt'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $diaChi = isset($_POST['dia_chi']) ? $_POST['dia_chi'] : null;
    
    try {
        include("database.php");
        $conn = connect();
        // Kiểm tra dữ liệu đầu vào
        if (!$ho || !$ten || !$soDT || !$email || !$diaChi) {
            throw new Exception("Vui lòng nhập đầy đủ thông tin.");
        }
        // Sử dụng prepare statement để tránh SQL injection
        $sql = "UPDATE khachhang SET 
        ho = :ho, ten = :ten, sdt = :sdt,
        email = :email, dia_chi = :dia_chi 
        WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ho', $ho, PDO::PARAM_STR);
        $stmt->bindParam(':ten', $ten, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $soDT, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':dia_chi', $diaChi, PDO::PARAM_STR);

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
