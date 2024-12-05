<?php
include("./config/connect.php");
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập để thanh toán.");
    window.location.href = "rooms.php";
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
    <title>Confirm - Booking</title>
    <?php require('inc/links.php'); ?>
</head>

<body>
    <?php require('inc/header.php'); ?>
    <?php
    if (isset($_REQUEST['id'])) {

        $id = base64_decode(urldecode($_REQUEST['id']));

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

        $id_encoded = urlencode(base64_encode($id_phong));
        $tien_giam = 0;
        $ngay_nhan_phong_tinh = new DateTime($ngay_nhan_phong);
        $ngay_tra_phong_tinh = new DateTime($ngay_tra_phong);

        $interval = $ngay_nhan_phong_tinh->diff($ngay_tra_phong_tinh);
        $songay = $interval->days;
    } else {
        exit;
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Thanh Toán</h2>
                <div style="font-size: 14px;">
                    <a href="rooms.php" class="text-secondary text-decoration-none;">Phòng</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none;">Thanh Toán Đặt Phòng</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form class="row g-3" method="post" action="thanhtoan.php">
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
                                                WHERE dvd.id_phong_dat = '$id'";

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
                                            <td><strong><?php echo number_format($tong_tien, 0, '.', '.'); ?> VNĐ</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Phương Thức Thanh Toán</label>
                                <select class="form-select" name="payment_method">
                                    <option value="momo">Thanh Toán Momo</option>
                                    <option value="vnpay">Thanh Toán VNPAY</option>
                                </select>
                            </div>
                            <input type="hidden" name="total" value="<?php echo $tong_tien; ?>">
                            <div class="col-md-4 align-self-end">
                                <button type="submit" class="btn btn-primary w-100" name="payment">Thanh Toán</button>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <a href="booking.php?id=<?php echo $id_encoded; ?>" class="btn w-100 btn-outline-dark">Quay Lại</a>
                            </div>
                            <?php
                            $_SESSION['id_phong_dat'] = $id;
                            ?>
                        </form>
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