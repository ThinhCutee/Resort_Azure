<?php
session_start();

try {
    // Lấy dữ liệu từ form
    $ngayNhanPhong = isset($_POST['ngayNhanPhong']) ? $_POST['ngayNhanPhong'] : '';
    $ngayTraPhong = isset($_POST['ngayTraPhong']) ? $_POST['ngayTraPhong'] : '';
    $soPhong = isset($_POST['soPhong']) ? $_POST['soPhong'] : '';
    $tenGoiDichVu = isset($_POST['tenGoiDichVu']) ? $_POST['tenGoiDichVu'] : null;
    $soNguoi = isset($_POST['soNguoi']) ? $_POST['soNguoi'] : '';
    $tenUuDai = isset($_POST['tenUuDai']) ? $_POST['tenUuDai'] : null;
    $soDT = isset($_POST['soDT']) ? $_POST['soDT'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $trangThai = 0;
    if(empty($tenUuDai))
    {
        $tenUuDai = NULL;
    }
    if(empty($tenGoiDichVu))
    {
        $tenGoiDichVu = NULL;
    }
    if (empty($ngayNhanPhong) || empty($ngayTraPhong) || empty($soPhong) || empty($soNguoi) || empty($soDT) || empty($email)) {
        throw new Exception("Không được để trống bất kỳ trường nào.");
    }

    // Include database connection
    include("database.php");
    $conn = connect();

    // Lấy ID khách hàng từ cơ sở dữ liệu
    $query = "SELECT id FROM khachhang WHERE sdt = ? AND email = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soDT, $email));
    $khachHang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$khachHang) {
        throw new Exception("Khách hàng không tồn tại.");
    }
    $khachHangID = $khachHang['id'];

    // Bắt đầu một giao dịch PDO
    $conn->beginTransaction();
    $transactionActive = true;

    // Tính toán giá trị hóa đơn
    $query = "SELECT gia FROM phong WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soPhong));
    $giaPhong = $stmt->fetchColumn();

    $giaGoiDichVu = 0;
    if (!empty($tenGoiDichVu)) {
        $query = "SELECT gia FROM goidichvu WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($tenGoiDichVu));
        $giaGoiDichVu = $stmt->fetchColumn();
    }

    $giamGia = 0;
    if (!empty($tenUuDai)) {
        $query = "SELECT gia_giam FROM uudai WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($tenUuDai));
        $giamGia = $stmt->fetchColumn();
    }

    $date1 = new DateTime($ngayNhanPhong);
    $date2 = new DateTime($ngayTraPhong);
    $soNgay = $date2->diff($date1)->days;

    $tongTien = ($giaPhong + $giaGoiDichVu) * (100 - $giamGia) / 100 * $soNgay;

    // Thêm phiếu đặt phòng
    $query = "INSERT INTO phongdat (id_phong, id_uu_dai, id_goi_dich_vu, id_khach_hang, so_nguoi, tong_tien, ngay_dat_phong, ngay_nhan_phong, trang_thai, ngay_tra_phong) 
              VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($soPhong, $tenUuDai, $tenGoiDichVu, $khachHangID, $soNguoi, $tongTien, $ngayNhanPhong, $trangThai, $ngayTraPhong));
    $phieuDatPhongID = $conn->lastInsertId();

    // Nếu có gói dịch vụ, thêm các dòng vào bảng `dichvudat`
    if (!empty($tenGoiDichVu)) {
        $query = "SELECT id, gioi_han 
                  FROM dichvu 
                  WHERE id_goi_dich_vu = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($tenGoiDichVu));
        $dichVus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dichVus as $dichVu) {
            $query = "INSERT INTO dichvudat (id, id_phong_dat, id_dich_vu, ngay_dat, trang_thai, so_lan_su_dung) 
                      VALUES (NULL, ?, ?, NOW(), ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($phieuDatPhongID, $dichVu['id'], $trangThai, '0'));
        }
    }

    // Commit giao dịch
    $conn->commit();
    $transactionActive = false;

    $response = array(
        'success' => true,
        'message' => "Dữ liệu đã được thêm thành công!"
    );
} catch (Exception $e) {
    if ($transactionActive) {
        $conn->rollBack();
    }
    $response = array(
        'success' => false,
        'message' => $e->getMessage()
    );
}

// Save response to session and redirect
$_SESSION['response'] = $response;
header("Location: ../phieudatphong.php");
exit();
?>
