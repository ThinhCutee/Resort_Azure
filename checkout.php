<?php
include("./config/connect.php");
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập để thanh toán.");
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
    <title>Check-out Booking</title>
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>
    <?php require('inc/header.php'); ?>
    <?php

    if (isset($_REQUEST['id'])) {

        $id = base64_decode(urldecode($_REQUEST['id']));
        $id_encode = $_REQUEST['id'];
        $sql_phong_dat = "SELECT * FROM phongdat WHERE id = '$id'";
        $query_phong_dat = mysqli_query($conn, $sql_phong_dat);
        $row_phong_dat = mysqli_fetch_assoc($query_phong_dat);

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
            $gia_goi_dich_vu = isset($row_goi_dich_vu['gia']) ? $row_goi_dich_vu['gia'] : 0;
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
        $ten_phong = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ten_phong FROM phong WHERE id = '$id_phong'"))['ten_phong'];
        $gia_phong = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gia FROM phong WHERE id = '$id_phong'"))['gia'];
        $tong_tien = $row_phong_dat['tong_tien'];
        $id_stt = 1;

        $tien_giam = 0;
        $ngay_nhan_phong_tinh = new DateTime($ngay_nhan_phong);
        $ngay_tra_phong_tinh = new DateTime($ngay_tra_phong);

        $interval = $ngay_nhan_phong_tinh->diff($ngay_tra_phong_tinh);
        $songay = $interval->days;

        $query_dich_vu_checkout = "SELECT * FROM dichvudat dvd JOIN dichvu dv ON dvd.id_dich_vu = dv.id WHERE id_phong_dat = '$id' AND dvd.trang_thai = 0";
        $result_dich_vu_checkout = mysqli_query($conn, $query_dich_vu_checkout);
        $co = mysqli_num_rows($result_dich_vu_checkout);
        if ($co > 0) {
            $action = "thanhtoan.php";
        } else {
            $action = "checkout.php";
        }
        $tong_tien_dung_them_update_database = 0;
        $tong_tien_dung_them = 0;
    }
    if (isset($_REQUEST['payment'])) {
        $payment = $_REQUEST['payment'];
        $id_phong_dat = $_SESSION['id_phong_dat_check_out'];
        $sql_check_out = "UPDATE phongdat SET is_check_out = 1 WHERE id = '$id_phong_dat'";
        $query_check_out = mysqli_query($conn, $sql_check_out);
        if ($query_check_out) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = 'mybooking.php';
                        }
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Check-out thất bại!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = 'checkout.php?id=$id_encode';
                        }
                    });
                });
            </script>";
        }
    }

    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Check-out</h2>
                <div style="font-size: 14px;">
                    <a href="mybooking.php" class="text-secondary text-decoration-none;">Lịch Sử Dịch Vụ</a>
                    <span class="text-secondary"> > </span>
                    <a href="checkout.php" class="text-secondary text-decoration-none;">Check-out</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form class="row g-3" method="post" action="<?php echo $action; ?>">
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
                                <label class="form-label">Thông Tin Trước Khi Đến</label>
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
                                        <?php
                                        if (isset($id_phong) && !empty($id_phong)) {
                                            echo '<tr>
                                            <td>' . $id_stt++ . '</td>
                                            <td>' . $ten_phong . '</td>
                                            <td>' . $songay . '</td>
                                            <td>' . number_format($gia_phong, 0, '.', '.') . ' VNĐ</td>
                                            <td>' . number_format($gia_phong * $songay, 0, '.', '.') . ' VNĐ</td>
                                        </tr>';
                                            $tien_giam += $gia_phong;
                                        }
                                        ?>
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
                                            $query_dich_vu = "
                                                SELECT DISTINCT 
                                                    dv.ten_dich_vu,
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

                                            while ($row_dich_vu = mysqli_fetch_assoc($result_dich_vu)) {
                                                echo '<tr>
                                                    <td>' . $id_stt++ . '</td>
                                                    <td>' . $row_dich_vu['ten_dich_vu'] . '</td>
                                                    <td>1</td>';

                                                if ($row_dich_vu['gia_dich_vu_hien_thi'] === NULL) {
                                                    echo '<td><s>--</s></td><td><s>--</s></td>';
                                                } else {
                                                    echo '<td>' . number_format($row_dich_vu['gia_dich_vu_hien_thi'], 0, '.', '.') . ' VNĐ</td>';
                                                    echo '<td>' . number_format($row_dich_vu['gia_dich_vu_hien_thi'], 0, '.', '.') . ' VNĐ</td>';
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
                                                        $tien_giam += $gia_bay_di;
                                                    } else {
                                                        $chuyen_di = "Chuyến bay đi từ " . $row_tt['id_san_bay_xuat_phat'] . " đến " . $row_tt['id_san_bay_den'];
                                                        $gia_bay_di = $row_tt['gia'];
                                                        $tien_giam += $gia_bay_di;
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
                                            $tien_giam = ($gia_giam_gia / 100) * $tien_giam;
                                            echo '<tr>
                                                <td>' . $id_stt++ . '</td>
                                                <td>' . $ten_giam_gia . '</td>
                                                <td>1</td>
                                                <td>Giảm ' . number_format($gia_giam_gia, 0, '.', '.') . ' %</td>
                                                <td>- ' . number_format($tien_giam, 0, '.', '.') . ' VNĐ</td>
                                            </tr>';
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Tổng Cộng</strong></td>
                                            <?php if (isset($tong_tien)) echo '<td><strong>' . number_format($tong_tien, 0, '.', '.') . ' VNĐ</strong></td>'; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            if (isset($id)) {
                                $id_sttt = 1;
                                $query_dich_vu_checkout = "SELECT * FROM dichvudat dvd JOIN dichvu dv ON dvd.id_dich_vu = dv.id WHERE id_phong_dat = '$id' AND dvd.so_lan_su_dung > dv.gioi_han";
                                $result_dich_vu_checkout = mysqli_query($conn, $query_dich_vu_checkout);
                                if (mysqli_num_rows($result_dich_vu_checkout) > 0) {
                                    echo '<div class="col-md-12">
                                        <label class="form-label">Thông Tin Sau Khi Đến</label>
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
                                            <td><strong>' . number_format($tong_tien_dung_them, 0, '.', '.') . ' VNĐ</strong></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>';
                                    $tong_tien_dung_them_update_database = $tong_tien + $tong_tien_dung_them;
                                    echo '<div class="col-md-6">
                                            <label class="form-label">Phương Thức Thanh Toán</label>
                                            <select class="form-select" name="payment_method">
                                                <option value="momo">Thanh Toán Momo</option>
                                                <option value="vnpay">Thanh Toán VNPAY</option>
                                            </select>
                                            </div>
                                            <input type="hidden" name="total" value=' . $tong_tien_dung_them . '>
                                            <div class="col-md-6 align-self-end">
                                                <button type="submit" class="btn btn-primary w-100" name="payment">Thanh Toán</button>
                                    </div>';
                                } else {
                                    echo '<div class="col-md-4">
                                            </div>
                                            <div class="col-md-4 align-self-end">
                                                <button type="submit" class="btn btn-primary w-100" name="payment">Check-out</button>
                                            </div>
                                            <div class="col-md-4">
                                            </div>';
                                }
                            }
                            ?>
                        </form>
                        <?php
                        $_SESSION['id_phong_dat_check_out'] = $id;
                        $_SESSION['tong_tien_check_out'] = $tong_tien_dung_them_update_database;
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
        function formatDate(dateString) {
            var dateParts = dateString.split('-');
            return dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
        }
        document.getElementById("ngaynhanphongthanhtoan").value = formatDate("<?php echo $ngay_nhan_phong; ?>");
        document.getElementById("ngaytraphongthanhtoan").value = formatDate("<?php echo $ngay_tra_phong; ?>");
    </script>
</body>

</html>