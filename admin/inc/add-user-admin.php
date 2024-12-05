<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $admin_name = isset($_POST['tenAdmin']) ? trim($_POST['tenAdmin']) : null;
    $admin_pass = isset($_POST['matKhau']) ? trim($_POST['matKhau']) : null;
    $trangThai = isset($_POST['trangThai']) ? intval($_POST['trangThai']) : null;

    $hoTen = isset($_POST['hoTen']) ? trim($_POST['hoTen']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $diaChi = isset($_POST['diaChi']) ? trim($_POST['diaChi']) : null;
    $phongBan = isset($_POST['phongBan']) ? trim($_POST['phongBan']) : null;

    // Kiểm tra dữ liệu đầu vào
    $errors = [];
    if (empty($admin_name)) $errors[] = "Tên tài khoản admin không được để trống.";
    if (empty($admin_pass)) $errors[] = "Mật khẩu không được để trống.";
    if (empty($hoTen)) $errors[] = "Họ tên không được để trống.";
    if (empty($email)) {
        $errors[] = "Email không được để trống.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }
    if (!empty($errors)) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    try {
        include("database.php");
        $conn = connect();
        $conn->beginTransaction();

        // Xử lý thêm hoặc cập nhật admin
        if (!$id) {
            // Chèn mới admin
            $sql = "INSERT INTO admin (admin_name, admin_pass, trang_thai) VALUES (:admin_name, :admin_pass, :trangThai)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':admin_name', $admin_name, PDO::PARAM_STR);
            $stmt->bindParam(':admin_pass', $admin_pass, PDO::PARAM_STR);
            $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);
            $stmt->execute();
            $id_admin = $conn->lastInsertId();
        } else {
            // Cập nhật admin
            $sql = "UPDATE admin SET admin_name = :admin_name, admin_pass = :admin_pass, trang_thai = :trangThai WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':admin_name', $admin_name, PDO::PARAM_STR);
            $stmt->bindParam(':admin_pass', $admin_pass, PDO::PARAM_STR);
            $stmt->bindParam(':trangThai', $trangThai, PDO::PARAM_INT);
            $stmt->execute();
            $id_admin = $id;
        }

        // Xử lý nhân viên
        $sqlNhanVien = "INSERT INTO nhanvien (ho_ten, email, dia_chi, phong_ban, id_admin) 
                        VALUES (:ho_ten, :email, :dia_chi, :phong_ban, :id_admin)
                        ON DUPLICATE KEY UPDATE ho_ten = :ho_ten, email = :email, dia_chi = :dia_chi, phong_ban = :phong_ban";
        $stmtNhanVien = $conn->prepare($sqlNhanVien);
        $stmtNhanVien->bindParam(':ho_ten', $hoTen, PDO::PARAM_STR);
        $stmtNhanVien->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtNhanVien->bindParam(':dia_chi', $diaChi, PDO::PARAM_STR);
        $stmtNhanVien->bindParam(':phong_ban', $phongBan, PDO::PARAM_STR);
        $stmtNhanVien->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
        $stmtNhanVien->execute();

        // Hoàn tất giao dịch
        $conn->commit();

        $response = array(
            'success' => true,
            'message' => "Dữ liệu đã được thêm thành công!"
        );
    } catch (PDOException $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array(
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
// Save response to session and redirect
$_SESSION['response'] = $response;
header("refresh:0; url='../user-admin.php'");
?>
