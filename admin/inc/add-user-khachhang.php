<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form

    $ho = isset($_POST['ho']) ? $_POST['ho'] : null;
    $ten = isset($_POST['ten']) ? $_POST['ten'] : null;
    $soDT = isset($_POST['soDT']) ? $_POST['soDT'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $diaChi = isset($_POST['diaChi']) ? $_POST['diaChi'] : null;

    try {
        // Kiểm tra dữ liệu đầu vào
        if (!$ho || !$ten || !$soDT || !$email || !$diaChi) {
            throw new Exception("Vui lòng nhập đầy đủ thông tin.");
        }

        include("database.php");
        $conn = connect();

        // Thêm nhân viên vào bảng nhanvien
        $sql = "INSERT INTO khachhang (ho, ten, email, dia_chi, sdt) 
        VALUES (:ho, :ten, :email, :dia_chi, :sdt)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ho', $ho, PDO::PARAM_STR);
        $stmt->bindParam(':ten', $ten, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':dia_chi', $diaChi, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $soDT, PDO::PARAM_STR);
        $stmt->execute();

        // Phản hồi thành công
        $response = array(
            'success' => true,
            'message' => "Dữ liệu đã được thêm thành công!"
        );
    } catch (PDOException $e) {
        $response = array(
            'success' => false,
            'message' => "Lỗi cơ sở dữ liệu: " . $e->getMessage()
        );
    } catch (Exception $e) {

        $response = array(
            'success' => false,
            'message' => "Lỗi xảy ra: " . $e->getMessage()
        );
    } finally {
        // Trả về phản hồi JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Nếu không phải request POST
    $response = array(
        'success' => false,
        'message' => "Yêu cầu không hợp lệ."
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
// Save response to session and redirect
$_SESSION['response'] = $response;
header("refresh:0; url='../user-khachhang.php'");
?>
