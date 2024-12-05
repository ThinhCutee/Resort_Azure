<?php
include("./config/connect.php");
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập.");
    window.location.href = "index.php";
    </script>';
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết dịch vụ</title>
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="css/css_detail.css">
</head>

<body>
    <?php include('inc/header.php'); ?>
    <?php
    function getDetailRoom($id_phong)
    {
        global $conn;
        $sql_phong = "SELECT * FROM phong p JOIN image_phong imgp on p.id = imgp.id_phong WHERE p.id = '$id_phong'";
        $result_phong = mysqli_query($conn, $sql_phong);
        return $result_phong;
    }
    function getDanhGia($id)
    {
        global $conn;
        $sql_danh_gia = "SELECT p.id AS id_phong, pd.id AS id_phong_dat, dg.*
                                    FROM danhgia dg
                                    JOIN phongdat pd ON dg.id_phong_dat = pd.id
                                    JOIN phong p ON p.id = pd.id_phong
                                    WHERE pd.id_phong = '$id'";
        $query_danh_gia = mysqli_query($conn, $sql_danh_gia);

        $tong_sao = 0;
        $so_luong_danh_gia = 0;

        while ($row = mysqli_fetch_assoc($query_danh_gia)) {
            $tong_sao += $row['danh_gia'];
            $so_luong_danh_gia++;
        }

        $trung_binh_sao = $so_luong_danh_gia > 0 ? $tong_sao / $so_luong_danh_gia : 0;
        return [
            'so_luong_danh_gia' => $so_luong_danh_gia,
            'trung_binh_sao' => $trung_binh_sao
        ];
    }
    function getDetailDV($id)
    {
        global $conn;
        $query_dich_vu = "
                        SELECT DISTINCT 
                            dv.ten_dich_vu, dvd.so_lan_su_dung as so_lan_su_dung,
                            CASE 
                                WHEN dvd.id_dich_vu IN (
                                    SELECT dv_in_goi.id 
                                    FROM dichvu dv_in_goi
                                    WHERE dv_in_goi.id_goi_dich_vu = pd.id_goi_dich_vu
                                ) THEN NULL
                                ELSE dv.don_gia
                            END AS gia_dich_vu_hien_thi
                        FROM dichvudat dvd
                        INNER JOIN dichvu dv ON dvd.id_dich_vu = dv.id
                        INNER JOIN phongdat pd ON dvd.id_phong_dat = pd.id
                        WHERE dvd.id_phong_dat = '$id' AND dvd.trang_thai = 1";

        $result_dich_vu = mysqli_query($conn, $query_dich_vu);
        return $result_dich_vu;
    }
    function getPhieu($id_phieu)
    {
        global $conn;
        $sql_phong_dat = "SELECT * FROM phongdat WHERE id = '$id_phieu'";
        $query_phong_dat = mysqli_query($conn, $sql_phong_dat);
        $row_phong_dat = mysqli_fetch_assoc($query_phong_dat);
        $is_check_out = $row_phong_dat['is_check_out'];

        $id_user = $row_phong_dat['id_khach_hang'];
        $sql_user = "SELECT * FROM khachhang WHERE id = '$id_user'";
        $query_user = mysqli_query($conn, $sql_user);
        $row_user = mysqli_fetch_assoc($query_user);

        $fullname = $row_user['ho'] . " " . $row_user['ten'];
        $emailbooking = $row_user['email'];
        $diachibooking = $row_user['dia_chi'];
        $sdtbooking = $row_user['sdt'];

        $ma_giam_gia = $row_phong_dat['id_uu_dai'] ? $row_phong_dat['id_uu_dai'] : NULL;
        $goi_dich_vu = $row_phong_dat['id_goi_dich_vu'] ? $row_phong_dat['id_goi_dich_vu'] : NULL;

        if ($goi_dich_vu != NULL) {
            $sql_goi_dich_vu = "SELECT * FROM goidichvu WHERE id = '$goi_dich_vu'";
            $query_goi_dich_vu = mysqli_query($conn, $sql_goi_dich_vu);
            $row_goi_dich_vu = mysqli_fetch_assoc($query_goi_dich_vu);
            $ten_goi_dich_vu = $row_goi_dich_vu['ten_goi_dich_vu'];
            $gia_goi_dich_vu = $row_goi_dich_vu['gia'];
        }

        if ($ma_giam_gia != NULL) {
            $sql_ma_giam_gia = "SELECT * FROM uudai WHERE id = '$ma_giam_gia'";
            $query_ma_giam_gia = mysqli_query($conn, $sql_ma_giam_gia);
            $row_ma_giam_gia = mysqli_fetch_assoc($query_ma_giam_gia);
            $ten_giam_gia = $row_ma_giam_gia['ten_uu_dai'];
            $gia_giam_gia = $row_ma_giam_gia['gia_giam'];
        }

        $ngay_nhan_phong = $row_phong_dat['ngay_nhan_phong'];
        $ngay_tra_phong = $row_phong_dat['ngay_tra_phong'];

        $id_phong = $row_phong_dat['id_phong'];
        $tong_tien = $row_phong_dat['tong_tien'];
        $trang_thai = $row_phong_dat['trang_thai'];
        return [
            'fullname' => $fullname,
            'emailbooking' => $emailbooking,
            'diachibooking' => $diachibooking,
            'sdtbooking' => $sdtbooking,
            'ma_giam_gia' => $ma_giam_gia,
            'goi_dich_vu' => $goi_dich_vu,
            'ten_goi_dich_vu' => $ten_goi_dich_vu,
            'gia_goi_dich_vu' => $gia_goi_dich_vu,
            'ten_giam_gia' => $ten_giam_gia,
            'gia_giam_gia' => $gia_giam_gia,
            'ngay_nhan_phong' => $ngay_nhan_phong,
            'ngay_tra_phong' => $ngay_tra_phong,
            'tong_tien' => $tong_tien,
            'id_phong' => $id_phong,
            'trang_thai' => $trang_thai,
            'is_check_out' => $is_check_out,
        ];
    }
    if (isset($_REQUEST['id_phong_dat'])) {
        $id = base64_decode(urldecode($_REQUEST['id_phong_dat']));
        $id_phong = getPhieu($id)['id_phong'];
        $fullname = getPhieu($id)['fullname'];
        $emailbooking = getPhieu($id)['emailbooking'];
        $diachibooking = getPhieu($id)['diachibooking'];
        $sdtbooking = getPhieu($id)['sdtbooking'];
        $ma_giam_gia = getPhieu($id)['ma_giam_gia'];
        $ten_giam_gia = getPhieu($id)['ten_giam_gia'];
        $gia_giam_gia = getPhieu($id)['gia_giam_gia'];
        $goi_dich_vu = getPhieu($id)['goi_dich_vu'];
        $ten_goi_dich_vu = getPhieu($id)['ten_goi_dich_vu'];
        $gia_goi_dich_vu = getPhieu($id)['gia_goi_dich_vu'];
        $ngay_nhan_phong = getPhieu($id)['ngay_nhan_phong'];
        $ngay_tra_phong = getPhieu($id)['ngay_tra_phong'];
        $tong_tien = getPhieu($id)['tong_tien'];
        $id_stt = 1;
        $tien_giam = 0;
        $trang_thai = getPhieu($id)['trang_thai'];
        $result_phong = getDetailRoom($id_phong);
        $is_check_out = getPhieu($id)['is_check_out'];
        if (mysqli_num_rows($result_phong) > 0) {
            $row_phong = mysqli_fetch_assoc($result_phong);
            $hang_phong = $row_phong['hang_phong'];
            if ($hang_phong == 0) {
                $hang_phong = "Thường";
            } elseif ($hang_phong == 1) {
                $hang_phong = "VIP";
            } else {
                $hang_phong = "Tổng Thống";
            }
            $gia = $row_phong['gia'];
            $loai_phong = $row_phong['loai_phong'] == 0 ? "Giường Đơn" : "Giường Đôi";
            $dien_tich = $row_phong['dien_tich'];
            $so_nguoi = $row_phong['so_nguoi'];
            $ten_phong = $row_phong['ten_phong'];
            mysqli_data_seek($result_phong, 0);
        } else {
            echo "Không tìm thấy phòng.";
            exit;
        }
    } else {
        exit;
    }
    $get_danh_gia = getDanhGia($id_phong);
    $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
    $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Lịch Sử Dịch Vụ - <?php echo $row_phong['ten_phong'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="mybooking.php" class="text-secondary text-decoration-none;">Lịch Sử Dịch Vụ</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none;">Chi Tiết Phòng Và Dịch Vụ Đã Đặt</a>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $active = 'active';
                        while ($row_img = mysqli_fetch_assoc($result_phong)) {
                            echo '<div class="carousel-item ' . $active . '">
                                <img src="./admin/uploads/' . $row_img['anh_phong'] . '" class="d-block w-100" alt="...">
                            </div>';
                            $active = '';
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3 mt-0">
                    <div class="card-body">
                        <?php
                        if ($trang_thai == 0) {
                            echo ' <div class="payment-status-pending">
                                        Chưa thanh toán
                                    </div>';
                        } else {
                            echo '<div class="payment-status">
                                        Đã thanh toán
                                    </div>';
                        }
                        echo '
                            <div class="row g-0 p-4 align-items-center">
                                <div class="col-md-12">
                                    <div class="info-group">
                                        <h6>Loại Phòng:</h6>
                                        <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $loai_phong . '</span>
                                    </div>
                                    <div class="info-group">
                                        <h6>Giá:</h6>
                                        <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . number_format($gia) . ' VND per night</span>
                                    </div>
                                    <div class="info-group">
                                        <h6>Diện Tích:</h6>
                                        <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $dien_tich . ' m²</span>
                                    </div>
                                    <div class="info-group">
                                        <h6>Hạng Phòng:</h6>
                                        <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hang_phong . '</span>
                                    </div>
                                    <div class="info-group">
                                        <h6>Số Người:</h6>
                                        <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $so_nguoi . '</span>
                                    </div>
                                    <div class="info-danh-gia">
                                            <h6 >Đánh Giá:</h6>
                                            <div class="info-dg">';
                        $tb_sao = ($tb_sao > 0) ? round($tb_sao, 1) : 0;
                        $so_sao_day = floor($tb_sao);
                        $sao_nua = ($tb_sao - $so_sao_day) >= 0.5 ? 1 : 0;
                        $sao_rong = 5 - ($so_sao_day + $sao_nua);

                        for ($i = 0; $i < $so_sao_day; $i++) {
                            echo '<span><i class="fas fa-star" style="color: gold;"></i></span>';
                        }
                        if ($sao_nua) {
                            echo '<span><i class="fas fa-star-half-alt" style="color: gold;"></i></span>';
                        }
                        for ($i = 0; $i < $sao_rong; $i++) {
                            echo '<span><i class="far fa-star" style="color: gold;"></i></span>';
                        }
                        echo '
                                            ' . $count_danh_gia . ' Đánh Giá
                                            </div>
                                            
                                        </div>
                                    <br>
                                    <a href="mybooking.php" class = "btn w-100 text-while btn-outline-dark shadow-none mb-2">Xác Nhận</a>
                                    <a href="mybooking.php" class="btn btn-sm w-100 btn-outline-dark" role="button">Thoát</a>
                                </div>
                            </div>
                            ';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form class="row g-3" method="post" action="">
                            <div class="col-md-4">
                                <label for="inputName" class="form-label">Họ Tên</label>
                                <input type="text" class="form-control" value="<?php echo $fullname; ?>" name="hotenthanhtoan" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail4" class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo $emailbooking; ?>" name="emailthanhtoan" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="inputAddress" class="form-label">Địa Chỉ</label>
                                <input type="text" class="form-control" value="<?php echo $diachibooking; ?>" name="diachithanhtoan" disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="inputCity" class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control" value="<?php echo $sdtbooking; ?>" name="sdtthanhtoan" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày Nhận Phòng</label>
                                <input type="text" class="form-control" name="ngaynhanphongthanhtoan" id="ngaynhanphongthanhtoan" value="<?php echo $ngay_nhan_phong; ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày Trả Phòng</label>
                                <input type="text" class="form-control" name="ngaytraphongthanhtoan" id="ngaytraphongthanhtoan" value="<?php echo $ngay_tra_phong; ?>" disabled>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Số Thứ Tự</th>
                                            <th>Tên</th>
                                            <th>Số Lượng</th>
                                            <th>Đơn Giá</th>
                                            <th>Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $id_stt++; ?></td>
                                            <td><?php echo $ten_phong; ?></td>
                                            <td>1</td>
                                            <td><?php echo number_format($gia, 0, '.', '.'); ?> VNĐ</td>
                                            <td><?php echo number_format($gia, 0, '.', '.');
                                                $tien_giam += $gia; ?> VNĐ</td>
                                        </tr>
                                        <?php
                                        if ($goi_dich_vu) {
                                            echo '<tr>
                                                <td>' . $id_stt++ . '</td>
                                                <td>' . $ten_goi_dich_vu . '</td>
                                                <td>1</td>
                                                <td>' . number_format($gia_goi_dich_vu, 0, '.', '.') . ' VNĐ</td>
                                                <td>' . number_format($gia_goi_dich_vu, 0, '.', '.') . ' VNĐ</td>
                                            </tr>';
                                        }
                                        $tien_giam += $gia_goi_dich_vu;
                                        if (isset($id)) {
                                            $result_dich_vu = getDetailDV($id);
                                            while ($row_dich_vu = mysqli_fetch_assoc($result_dich_vu)) {
                                                echo '<tr>
                                                    <td>' . $id_stt++ . '</td>
                                                    <td>' . $row_dich_vu['ten_dich_vu'] . '</td>
                                                    <td>' . $row_dich_vu['so_lan_su_dung'] . '</td>';

                                                if ($row_dich_vu['gia_dich_vu_hien_thi'] === NULL) {
                                                    echo '<td><s>--</s></td><td><s>--</s></td>';
                                                } else {
                                                    echo '<td>' . number_format($row_dich_vu['gia_dich_vu_hien_thi'], 0, '.', '.') . ' VNĐ</td>';
                                                    echo '<td>' . number_format($row_dich_vu['gia_dich_vu_hien_thi'] * $row_dich_vu['so_lan_su_dung'], 0, '.', '.') . ' VNĐ</td>';
                                                    $tien_giam += $row_dich_vu['gia_dich_vu_hien_thi'];
                                                }
                                                echo '</tr>';
                                            }
                                        }
                                        if (isset($id)) {
                                            $sql_bay = "SELECT id_ghe_chuyenbay FROM vemaybay WHERE id_phong_dat = '$id'";
                                            $result_bay = mysqli_query($conn, $sql_bay);
                                            if (mysqli_num_rows($result_bay) > 0) {
                                                while ($row_bay = mysqli_fetch_assoc($result_bay)) {
                                                    $id_ghe_chuyenbay = $row_bay['id_ghe_chuyenbay'];
                                                    $sql_tt = "SELECT * FROM vemaybay vmb JOIN ghe_chuyenbay gcb on vmb.id_ghe_chuyenbay = gcb.id join chuyenbay cb on gcb.id_chuyenbay = cb.id join ghe g on g.id = gcb.id_ghe where vmb.id_ghe_chuyenbay = '$id_ghe_chuyenbay'";
                                                    $result_tt = mysqli_query($conn, $sql_tt);
                                                    $row_tt = mysqli_fetch_assoc($result_tt);
                                                    $row_tt_chuyen_di = $row_tt['id_san_bay_xuat_phat'];
                                                    $row_tt_chuyen_ve = $row_tt['id_san_bay_den'];
                                                    if ($row_tt_chuyen_di != "CXR") {
                                                        $chuyen_di = "Chuyến bay đi từ " . $row_tt['id_san_bay_xuat_phat'] . " đến " . $row_tt['id_san_bay_den'];
                                                        $gia_bay_di = $row_tt['gia'];
                                                    } else {
                                                        $chuyen_di = "Chuyến bay đi từ " . $row_tt['id_san_bay_xuat_phat'] . " đến " . $row_tt['id_san_bay_den'];
                                                        $gia_bay_di = $row_tt['gia'];
                                                    }
                                                    echo '<tr>
                                                    <td>' . $id_stt++ . '</td>
                                                    <td>' . $chuyen_di . '</td>
                                                    <td>1</td>
                                                    <td>' . number_format($gia_bay_di, 0, '.', '.') . ' VNĐ</td>
                                                    <td>' . number_format($gia_bay_di, 0, '.', '.') . ' VNĐ</td>
                                                    </tr>';
                                                }
                                            }
                                        }
                                        if ($ma_giam_gia) {
                                            if ($gia_giam_gia == 0) {
                                                $tien_giam = 0;
                                            } else {
                                                $tien_giam = ($gia_giam_gia / 100) * $tien_giam;
                                            }
                                            echo '<tr>
                                                <td>' . $id_stt++ . '</td>
                                                <td>' . $ten_giam_gia . '</td>
                                                <td>1</td>
                                                <td> Giảm ' . number_format($gia_giam_gia, 0, '.', '.') . ' %</td>
                                                <td>- ' . number_format($tien_giam, 0, '.', '.') . ' VNĐ</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Tổng Cộng</strong></td>
                                            <td><strong><?php echo number_format($tong_tien, 0, '.', '.'); ?> VNĐ</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            if (isset($id)) {
                                $id_sttt = 1;
                                $query_dich_vu_checkout = "SELECT * FROM dichvudat dvd JOIN dichvu dv ON dvd.id_dich_vu = dv.id WHERE id_phong_dat = '$id' AND dvd.so_lan_su_dung > dv.gioi_han";
                                $result_dich_vu_checkout = mysqli_query($conn, $query_dich_vu_checkout);

                                if (mysqli_num_rows($result_dich_vu_checkout) > 0 && $is_check_out == 1) {
                                    echo '<div class="col-md-12">
                                        <label class="form-label">Bảng thông tin dịch vụ phát sinh sau khi check-out <span class="text-danger">(Lưu ý: Số tiền đã được tính vào số tiền tổng)</span></label>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Số Thứ Tự</th>
                                                    <th>Tên</th>
                                                    <th>Số Lượng</th>
                                                    <th>Đơn Giá</th>
                                                    <th>Tổng</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                    while ($row_dich_vu = mysqli_fetch_assoc($result_dich_vu_checkout)) {
                                        $so_lan_vuot_qua = $row_dich_vu['so_lan_su_dung'];
                                        if ($so_lan_vuot_qua > $row_dich_vu['gioi_han']) {
                                            $so_lan_vuot_qua = $so_lan_vuot_qua - $row_dich_vu['gioi_han'];
                                        } else {
                                            $so_lan_vuot_qua = 0;
                                        }
                                        $ten_dich_vu_vuot_qua = $row_dich_vu['ten_dich_vu'];
                                        $gia_dich_vu_vuot_qua = $row_dich_vu['don_gia'];
                                        $tong_tien_dung_them += $so_lan_vuot_qua * $gia_dich_vu_vuot_qua;
                                        echo '<tr>
                                                    <td>' . $id_sttt++ . '</td>
                                                    <td>' . $ten_dich_vu_vuot_qua . '</td>
                                                    <td>' . $so_lan_vuot_qua . '</td>
                                                    <td>' . number_format($gia_dich_vu_vuot_qua, 0, '.', '.') . ' VNĐ</td>
                                                    <td>' . number_format($so_lan_vuot_qua * $gia_dich_vu_vuot_qua, 0, '.', '.') . ' VNĐ</td>
                                                </tr>';
                                    }
                                    echo '<tr>
                                            <td colspan="4" class="text-right"><strong>Tổng Cộng</strong></td>
                                            <td><strong>+ ' . number_format($tong_tien_dung_them, 0, '.', '.') . ' VNĐ</strong></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>';
                                }
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                        if ($hang_phong == "Thường") {
                            echo '<h5>Mô Tả:</h5>';
                            echo '<p>' . $row_phong['mo_ta'] . '</p>';
                            echo '<h5>Tiện Ích:</h5>';
                            echo '<div class="utility-icons-container">
                                <div><span class="icon"><i class="fas fa-heartbeat"></i></span> Cân sức khỏe</div>
                                <div><span class="icon"><i class="fas fa-phone"></i></span> Điện thoại</div>
                                <div><span class="icon"><i class="fas fa-shower"></i></span> Vòi sen</div>
                                <div><span class="icon"><i class="fas fa-snowflake"></i></span> Điều hoà không khí</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Dép đi trong nhà</div>
                                <div><span class="icon"><i class="fas fa-utensils"></i></span> Máy sấy tóc</div>
                                <div><span class="icon"><i class="fas fa-wifi"></i></span> Mạng tốc độ cao</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Áo choàng tắm</div>
                                <div><span class="icon"><i class="fas fa-tv"></i></span> Flat-screen TV</div>
                                <div><span class="icon"><i class="fas fa-tshirt"></i></span> Tủ quần áo</div>
                                <div><span class="icon"><i class="fas fa-lock"></i></span> Két sắt điện tử</div>
                                <div><span class="icon"><i class="fas fa-wifi"></i></span> Wifi miễn phí</div>
                                <div><span class="icon"><i class="fas fa-temperature-high"></i></span> Ấm điện</div>
                                <div><span class="icon"><i class="fas fa-coffee"></i></span> Trà & Cafe miễn phí</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Bồn tắm</div>
                                <div><span class="icon"><i class="fas fa-laptop"></i></span> Bàn làm việc</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Khăn tắm</div>
                                <div><span class="icon"><i class="fas fa-swimming-pool"></i></span> Bể bơi</div>
                                <div><span class="icon"><i class="fas fa-umbrella"></i></span> Ô dù</div>
                                <div><span class="icon"><i class="fas fa-cocktail"></i></span> Minibar</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Giường lớn</div>
                            </div>';
                        } else if ($hang_phong == "VIP") {
                            echo '<h5>Mô Tả:</h5>';
                            echo '<p>' . $row_phong['mo_ta'] . '</p>';
                            echo '<h5>Tiện Ích:</h5>';
                            echo '<div class="utility-icons-container">
                                <div><span class="icon"><i class="fas fa-heartbeat"></i></span> Cân sức khỏe</div>
                                <div><span class="icon"><i class="fas fa-phone"></i></span> Điện thoại</div>
                                <div><span class="icon"><i class="fas fa-shower"></i></span> Vòi sen</div>
                                <div><span class="icon"><i class="fas fa-snowflake"></i></span> Điều hoà không khí</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Dép đi trong nhà</div>
                                <div><span class="icon"><i class="fas fa-utensils"></i></span> Máy sấy tóc</div>
                                <div><span class="icon"><i class="fas fa-wifi"></i></span> Mạng tốc độ cao</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Áo choàng tắm</div>
                                <div><span class="icon"><i class="fas fa-tv"></i></span> Flat-screen TV</div>
                                <div><span class="icon"><i class="fas fa-tshirt"></i></span> Tủ quần áo</div>
                                <div><span class="icon"><i class="fas fa-lock"></i></span> Két sắt điện tử</div>
                                <div><span class="icon"><i class="fas fa-coffee"></i></span> Wifi miễn phí</div>
                                <div><span class="icon"><i class="fas fa-temperature-high"></i></span> Ấm điện</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Trà & Cafe miễn phí</div>
                                <div><span class="icon"><i class="fas fa-laptop"></i></span> Bồn tắm</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Bàn làm việc</div>
                                <div><span class="icon"><i class="fas fa-swimming-pool"></i></span> Khăn tắm</div>
                                <div><span class="icon"><i class="fas fa-umbrella"></i></span> Ô dù</div>
                                <div><span class="icon"><i class="fas fa-cocktail"></i></span> Minibar</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Giường lớn</div>
                            </div>';
                        } else {
                            echo '<h5>Mô Tả:</h5>';
                            echo '<p>' . $row_phong['mo_ta'] . '</p>';
                            echo '<h5>Tiện Ích:</h5>';
                            echo '<div class="utility-icons-container">
                                <div><span class="icon"><i class="fas fa-heartbeat"></i></span> Cân sức khỏe</div>
                                <div><span class="icon"><i class="fas fa-phone"></i></span> Điện thoại</div>
                                <div><span class="icon"><i class="fas fa-shower"></i></span> Vòi sen</div>
                                <div><span class="icon"><i class="fas fa-snowflake"></i></span> Điều hoà không khí</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Dép đi trong nhà</div>
                                <div><span class="icon"><i class="fas fa-utensils"></i></span> Máy sấy tóc</div>
                                <div><span class="icon"><i class="fas fa-wifi"></i></span> Mạng tốc độ cao</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Áo choàng tắm</div>
                                <div><span class="icon"><i class="fas fa-tv"></i></span> Flat-screen TV</div>
                                <div><span class="icon"><i class="fas fa-tshirt"></i></span> Tủ quần áo</div>
                                <div><span class="icon"><i class="fas fa-couch"></i></span> Phòng khách</div>
                                <div><span class="icon"><i class="fas fa-lock"></i></span> Két sắt điện tử</div>
                                <div><span class="icon"><i class="fas fa-wifi"></i></span> Wifi miễn phí</div>
                                <div><span class="icon"><i class="fas fa-temperature-high"></i></span> Ấm điện</div>
                                <div><span class="icon"><i class="fas fa-coffee"></i></span> Trà & Cafe miễn phí</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Bồn tắm</div>
                                <div><span class="icon"><i class="fas fa-laptop"></i></span> Bàn làm việc</div>
                                <div><span class="icon"><i class="fas fa-bath"></i></span> Khăn tắm</div>
                                <div><span class="icon"><i class="fas fa-swimming-pool"></i></span> Bể bơi</div>
                                <div><span class="icon"><i class="fas fa-umbrella"></i></span> Ô dù</div>
                                <div><span class="icon"><i class="fas fa-cocktail"></i></span> Minibar</div>
                                <div><span class="icon"><i class="fas fa-bed"></i></span> Giường lớn</div>
                                <div><span class="icon"><i class="fas fa-temperature-low"></i></span> Tủ lạnh</div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3 mt-0">
                    <div class="card-body">
                        <h5 class="mb-4">Bình Luận</h5>
                        <?php
                        function get_danh_gia($id)
                        {
                            global $conn;
                            $sql = "SELECT p.id AS id_phong, pd.id AS id_phong_dat, dg.*
                                    FROM danhgia dg
                                    JOIN phongdat pd ON dg.id_phong_dat = pd.id
                                    JOIN phong p ON p.id = pd.id_phong
                                    WHERE pd.id_phong = '$id' ORDER BY dg.thoi_gian DESC
                                    ";
                            return mysqli_query($conn, $sql);
                        }
                        $danh_gia = get_danh_gia($id_phong);
                        if (mysqli_num_rows($danh_gia) > 0) {
                            while ($row = mysqli_fetch_assoc($danh_gia)) {
                                $ten_nguoi_dung = (!empty($row['ten_nguoi_danh_gia']) && $row['trang_thai'] != 0)
                                    ? $row['ten_nguoi_danh_gia']
                                    : 'Ẩn Danh';
                                $noi_dung = $row['binh_luan'];
                                $sao = $row['danh_gia'];
                                $ngay_binh_luan = $row['thoi_gian'];
                        ?>
                                <hr>
                                <div class="d-flex align-items-start mb-3">

                                    <div class="me-3">
                                        <i class="fas fa-user-circle fa-2x" style="color: #6c757d;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw mb-1"><?php echo htmlspecialchars($ten_nguoi_dung); ?></h6>
                                        <div class="mb-2">
                                            <?php
                                            for ($i = 1; $i <= $sao; $i++) {
                                                echo '<i class="fas fa-star" style="color: gold;"></i>';
                                            }
                                            for ($i = $sao + 1; $i <= 5; $i++) {
                                                echo '<i class="far fa-star" style="color: gold;"></i>';
                                            }
                                            ?>
                                        </div>
                                        <p class="text-muted mb-1"><?php echo htmlspecialchars($noi_dung); ?></p>
                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($ngay_binh_luan)); ?></small>
                                    </div>
                                </div>
                                <hr>
                        <?php
                            }
                        } else {
                            echo '<p class="text-muted">Chưa có bình luận nào.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('inc/footer.php'); ?>
    <!-- Chatra {literal} -->
    <script>
        (function(d, w, c) {
            w.ChatraID = 'CBQ3fukS8PFGnSbiW';
            var s = d.createElement('script');
            w[c] = w[c] || function() {
                (w[c].q = w[c].q || []).push(arguments);
            };
            s.async = true;
            s.src = 'https://call.chatra.io/chatra.js';
            if (d.head) d.head.appendChild(s);
        })(document, window, 'Chatra');
    </script>
    <!-- /Chatra {/literal} -->
    <script>
        var signupSuccess = document.getElementById('signup-success');
        signupSuccess.style.display = 'block';
        setTimeout(function() {
            signupSuccess.style.display = 'none';
        }, 10000);
        var signupFailure = document.getElementById('signup-failure');
        signupFailure.style.display = 'block';
        setTimeout(function() {
            signupFailure.style.display = 'none';
        }, 10000);
    </script>
    <script>
        function formatDate(dateString) {
            var dateParts = dateString.split('-');
            return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
        }
        document.getElementById("ngaynhanphongthanhtoan").value = formatDate("<?php echo $ngay_nhan_phong; ?>");
        document.getElementById("ngaytraphongthanhtoan").value = formatDate("<?php echo $ngay_tra_phong; ?>");
    </script>
</body>

</html>