<?php
// session_start();
include("database.php");
$conn = connect();

// Lấy danh sách ảnh của phòng này từ cơ sở dữ liệu
if (isset($_GET['id_phong_dat'])) {
    $id_phong_dat = intval($_GET['id_phong_dat']);

    // Truy vấn lấy danh sách dịch vụ đặt
    $sql = "SELECT 
        dvdat.id AS id_dich_vu_dat,
        dv.ten_dich_vu as ten_dich_vu,
        dv.gioi_han as gioi_han,
        kh.sdt AS so_dien_thoai_khach_hang,
        dvdat.ngay_dat as ngay_dat,
        dv.don_gia as don_gia,
        dvdat.trang_thai as trang_thai,
        dvdat.so_lan_su_dung AS so_lan_su_dung
    FROM 
        dichvudat dvdat
    JOIN 
        dichvu dv ON dvdat.id_dich_vu = dv.id
    JOIN 
        phongdat pd ON dvdat.id_phong_dat = pd.id
    JOIN 
        khachhang kh ON pd.id_khach_hang = kh.id
    WHERE pd.id = :id_phong_dat
    Group by
    dvdat.id ,
        dv.ten_dich_vu ,
        kh.sdt ,
        dvdat.ngay_dat ,
        dv.don_gia ,
        dvdat.trang_thai ,
        dvdat.so_lan_su_dung
    order by  dvdat.id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
    $stmt->execute();
    $dvDat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Truy vấn lấy danh sách phòng đặt
    $sql1 = "SELECT * 
    FROM phongdat
    WHERE id = :id_phong_dat";
    $stmt = $conn->prepare($sql1);
    $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
    $stmt->execute();
    $phongdat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tính tổng tiền phòng (trang_thai = 0)
    $sql2 = "SELECT SUM(tong_tien) AS total_room_money 
             FROM phongdat
             WHERE id = :id_phong_dat AND trang_thai = 0";
    $stmt = $conn->prepare($sql2);
    $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
    $stmt->execute();
    $total_room_money = $stmt->fetch(PDO::FETCH_ASSOC)['total_room_money'];

    // Tính tổng tiền dịch vụ (trang_thai = 0)
    $sql3 = "
    SELECT SUM(
        CASE 
            WHEN dvdat.so_lan_su_dung <= dv.gioi_han THEN dv.don_gia
            ELSE (dv.don_gia + ((dvdat.so_lan_su_dung - dv.gioi_han) * dv.don_gia)) 
        END
    ) AS total_service_money
    FROM dichvudat dvdat
    JOIN dichvu dv ON dvdat.id_dich_vu = dv.id
    WHERE dvdat.id_phong_dat = :id_phong_dat AND dvdat.trang_thai = 0";
    $stmt = $conn->prepare($sql3);
    $stmt->bindParam(':id_phong_dat', $id_phong_dat, PDO::PARAM_INT);
    $stmt->execute();
    $total_service_money = $stmt->fetch(PDO::FETCH_ASSOC)['total_service_money'];

    // Tính tổng tiền (phòng + dịch vụ)
    $total_money = $total_room_money + $total_service_money;

   

} else {
    $_SESSION['response'] = array(
        'message' => 'ID phòng đặt không hợp lệ.',
        'success' => false
    );
    header("Location: ./chitiet-checkout.php"); // Chuyển hướng người dùng về trang chủ hoặc trang khác tùy bạn
    exit;
}
?>
