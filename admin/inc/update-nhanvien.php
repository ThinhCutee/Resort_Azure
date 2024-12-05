<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $ho_ten = isset($_POST['ho_ten']) ? $_POST['ho_ten'] : null;
    $dia_chi = isset($_POST['dia_chi']) ? $_POST['dia_chi'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $phong_ban = isset($_POST['phong_ban']) ? $_POST['phong_ban'] : null;
    
    try {
        include("database.php");
        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection
        $sql = "UPDATE nhanvien 
        SET ho_ten = :ho_ten, email= :email , dia_chi = :dia_chi, phong_ban = :phong_ban 
        WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ho_ten', $ho_ten, PDO::PARAM_STR);
        $stmt->bindParam(':dia_chi', $dia_chi, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phong_ban', $phong_ban, PDO::PARAM_STR);

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
