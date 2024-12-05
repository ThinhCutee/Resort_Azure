<?php
session_start();
include("database.php");

$conn = connect(); // Kết nối đến CSDL

// Lấy dữ liệu từ yêu cầu POST
$sdt = $_POST['sdt'];
$email = $_POST['email'];
$gia = $_POST['gia'];
$trang_thai = $_POST['trang_thai'];
$selected_ids = $_POST['selected_ids']; // Mảng các ID dịch vụ cần cập nhật

// Kiểm tra dữ liệu đầu vào
if (empty($sdt) || empty($email) || empty($selected_ids)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập đầy đủ thông tin và chọn ít nhất một dịch vụ.'
    ]);
    exit;
}

try {
    // Bắt đầu giao dịch
    $conn->beginTransaction();

    // Kiểm tra khách hàng có tồn tại không
    $queryKhachHang = "SELECT k.id 
    FROM phongdat d JOIN khachhang k ON d.id_khach_hang = k.id 
    WHERE k.sdt = :sdt 
    AND ( CURDATE() BETWEEN d.ngay_nhan_phong AND d.ngay_tra_phong OR TIMESTAMPDIFF(HOUR, d.ngay_tra_phong, NOW()) <= 24 );";
    $stmtKhachHang = $conn->prepare($queryKhachHang);
    $stmtKhachHang->bindParam(':sdt', $sdt);
    $stmtKhachHang->execute();

    $khachHang = $stmtKhachHang->fetch(PDO::FETCH_ASSOC);

    if (!$khachHang) {
        throw new Exception('Khách hàng không tồn tại trong hệ thống.');
    }

    $khachHangID = $khachHang['id']; // Sửa lại lấy đúng id khách hàng

    // Lấy ID phòng đặt của khách hàng
    $queryPhongDat = "SELECT d.id
    FROM phongdat d 
    WHERE d.id_khach_hang = :id_khach_hang 
    AND ( CURDATE() BETWEEN d.ngay_nhan_phong AND d.ngay_tra_phong OR TIMESTAMPDIFF(HOUR, d.ngay_tra_phong, NOW()) <= 24 );";
    $stmtPhongDat = $conn->prepare($queryPhongDat);
    $stmtPhongDat->bindParam(':id_khach_hang', $khachHangID);
    $stmtPhongDat->execute();

    $phongDat = $stmtPhongDat->fetch(PDO::FETCH_ASSOC);

    if (!$phongDat) {
        throw new Exception('Khách hàng chưa đặt phòng.');
    }

    $idPhongDat = $phongDat['id'];

    // Insert dữ liệu vào bảng dichvudat cho mỗi dịch vụ được chọn
    foreach ($selected_ids as $idDichVu) {
        // Lấy thông tin về dịch vụ từ ID
        $queryDichVu = "SELECT * FROM dichvu WHERE id = :id_dich_vu";
        $stmtDichVu = $conn->prepare($queryDichVu);
        $stmtDichVu->bindParam(':id_dich_vu', $idDichVu);
        $stmtDichVu->execute();

        $dichVu = $stmtDichVu->fetch(PDO::FETCH_ASSOC);

        if ($dichVu) {
            $gioiHan = $dichVu['gioi_han']; // Giới hạn sử dụng từ dịch vụ

            // Insert dữ liệu vào bảng dichvudat
            $insertSql = "INSERT INTO dichvudat (id_phong_dat, id_dich_vu, ngay_dat, trang_thai, so_lan_su_dung) 
                          VALUES (:id_phong_dat, :id_dich_vu, NOW(), :trang_thai, :so_lan_su_dung)";
            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bindParam(':id_phong_dat', $idPhongDat);
            $stmtInsert->bindParam(':id_dich_vu', $idDichVu);
            $stmtInsert->bindParam(':trang_thai', $trang_thai);
            $stmtInsert->bindParam(':so_lan_su_dung', $gioiHan);
            $stmtInsert->execute();
        }
    }

    // Hoàn tất giao dịch
    $conn->commit();

    // Phản hồi thành công
    echo json_encode([
        'success' => true,
        'message' => 'Dịch vụ đã được thêm thành công!',
        'gia' => $gia
    ]);
} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollBack();

    // Phản hồi lỗi
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Đóng kết nối
$conn = null;
?>
