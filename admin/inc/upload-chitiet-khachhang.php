<?php
// session_start();
include("database.php");
$conn = connect();

// Lấy danh sách ảnh của phòng này từ cơ sở dữ liệu
if (isset($_GET['id_khach_hang'])) {
    $id_khach_hang = intval($_GET['id_khach_hang']);

    // Truy vấn lấy danh sách dịch vụ đặt
    $sql = "SELECT 
        dvdat.id AS id_dich_vu_dat,
        dv.ten_dich_vu as ten_dich_vu,
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
    WHERE pd.id_khach_hang = :id_khach_hang
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
    $stmt->bindParam(':id_khach_hang', $id_khach_hang, PDO::PARAM_INT);
    $stmt->execute();
    $dvDat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Truy vấn lấy danh sách phòng đặt
    $sql1 = "SELECT * 
    FROM phongdat
    WHERE id_khach_hang = :id_khach_hang";
    $stmt = $conn->prepare($sql1);
    $stmt->bindParam(':id_khach_hang', $id_khach_hang, PDO::PARAM_INT);
    $stmt->execute();
    $phongdat = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // Truy vấn lấy thông tin vé máy bay
    $sql2 = "SELECT 
        v.id as id_ve,
        v.ngay_dat_ve as ngay_dat_ve,
        v.trang_thai as trang_thai,
        k.sdt as so_khachhang,
        g.so_ghe as so_ghe,
        -- khach hang
        k.id as id_kh,
        k.ho as ho,
        k.ten as ten,
        k.sdt as sdt,
        k.email as email,
        k.dia_chi as dia_chi,
        -- nguoi bay
        nb.id as id_nb,
        nb.ho as ho_nb,
        nb.ten as ten_nb,
        nb.ngay_sinh as ngay_sinh,
        nb.gioi_tinh as gioi_tinh,
        nb.cccd as cccd,
        nb.sdt as sdt_nb,
        nb.quoc_tich as quoc_tich
        FROM 
        vemaybay v join khachhang k ON v.id_khach_hang = k.id
        join ghe_chuyenbay gcb ON v.id_ghe_chuyenbay = gcb.id
        join ghe g ON g.id = gcb.id_ghe
        join thongtinnguoibay nb on nb.id = v.id_nguoi_bay
        WHERE v.id_khach_hang = :id_khach_hang
        
";
$stmt = $conn->prepare($sql2);
$stmt->bindParam(':id_khach_hang', $id_khach_hang, PDO::PARAM_INT);
$stmt->execute();
$ve = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    $_SESSION['response'] = array(
        'message' => 'ID khách hàng không hợp lệ.',
        'success' => false
    );
    header("Location: ./chitiet-khachhang.php"); // Chuyển hướng người dùng về trang chủ hoặc trang khác tùy bạn
    exit;
}
?>
