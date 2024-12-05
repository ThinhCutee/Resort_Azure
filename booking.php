<?php
include("./config/connect.php");
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập để đặt phòng.");
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
    <title>Booking</title>
    <?php require('inc/links.php'); ?>
    <style>
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }

        .carousel-inner {
            border-radius: 8px;
            overflow: hidden;
        }

        .info-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-group h6 {
            margin: 0;
            font-weight: bold;
        }

        .info-group span {
            padding: 10px;
            font-size: 14px;
        }

        .badge {
            padding: 10px;
            font-size: 14px;
        }

        .container h2 {
            margin-bottom: 20px;
        }

        .utility-icons-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .utility-icons-container>div {
            flex: 1;
            margin: 10px;
            text-align: center;
            max-width: 200px;
        }

        .info-danh-gia {
            display: flex;
            justify-content: space-between;
        }

        .info-danh-gia h6 {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
    <script>
        $(document).ready(function() {
            $(".chzn-select").chosen({
                width: "100%",
                no_results_text: "Không tìm thấy dịch vụ"
            });

            $('#goiDichVu').change(function() {
                let goiDichVuId = $(this).val();

                $.ajax({
                    url: 'fetch_services.php',
                    type: 'POST',
                    data: {
                        goi_dich_vu_id: goiDichVuId
                    },
                    dataType: 'json',
                    success: function(response) {
                        let dichVuSelect = $('#dichVu');
                        dichVuSelect.empty();

                        response.all_services.forEach(function(service) {
                            let isSelected = response.selected_services.includes(service.id);
                            dichVuSelect.append('<option value="' + service.id + '" ' + (isSelected ? 'selected' : '') + '>' + service.ten_dich_vu + '</option>');
                        });

                        dichVuSelect.trigger('chosen:updated');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching services:', error);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <!-- Nội dung của trang web -->
    <?php require('inc/header.php'); ?>
    <?php
    if (isset($_REQUEST['id'])) {
        $id_phong_details = base64_decode(urldecode($_REQUEST['id']));
        $sql_phong = "SELECT * FROM phong p JOIN image_phong imgp on p.id = imgp.id_phong WHERE p.id = '$id_phong_details'";

        $id_user = $user['id'];
        $sql_user = "SELECT * FROM khachhang WHERE id = '$id_user'";
        $query_user = mysqli_query($conn, $sql_user);
        $row_user = mysqli_fetch_assoc($query_user);

        $user_name = $row_user['ho'] . " " . $row_user['ten'];
        $user_email = $row_user['email'];
        $user_diachi = $row_user['dia_chi'];
        $user_sdt = $row_user['sdt'];

        $get_danh_gia = getDanhGia($id_phong_details);

        $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
        $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;

        $result_phong = mysqli_query($conn, $sql_phong);

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
            mysqli_data_seek($result_phong, 0);
        } else {
            echo "Không tìm thấy phòng.";
            exit;
        }
    } else {
        header('location: index.php');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $magiamgiabooking = $_POST["magiamgiabooking"] ?? "";
        $goidichvubooking = $_POST['goidichvubooking'] ?? NULL;
        $dichvu = isset($_POST["dichvu"]) && is_array($_POST["dichvu"]) ? $_POST["dichvu"] : [];
        $date_pay = date('Y-m-d');
        $ngaynhanphongbooking = $_POST["ngaynhanphongbooking"];
        $ngaytraphongbooking = $_POST["ngaytraphongbooking"];
        $gia_phong = $gia;
        $giagiam = 0;

        $ngay_nhan_phong = new DateTime($ngaynhanphongbooking);
        $ngay_tra_phong = new DateTime($ngaytraphongbooking);

        $interval = $ngay_nhan_phong->diff($ngay_tra_phong);
        $songay = $interval->days;

        if ($magiamgiabooking) {
            $query_giagiam = "SELECT gia_giam FROM uudai WHERE id = '$magiamgiabooking' AND ngay_bat_dau <= '$date_pay' AND ngay_ket_thuc >= '$date_pay'";
            $result_giagiam = mysqli_query($conn, $query_giagiam);
            $row_giagiam = mysqli_fetch_assoc($result_giagiam);
            $giagiam = $row_giagiam['gia_giam'] ?? 0;
        }

        if (!$goidichvubooking) {
            $giagoidichvu = 0;
        } else {
            $query_tiengoidichvu = "SELECT gia, ten_goi_dich_vu FROM goidichvu WHERE id = '$goidichvubooking'";
            $result_tiengoidichvu = mysqli_query($conn, $query_tiengoidichvu);
            $row_tiengoidichvu = mysqli_fetch_assoc($result_tiengoidichvu);
            $giagoidichvu = $row_tiengoidichvu['gia'] ?? 0;
        }

        $so_nguoi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT so_nguoi FROM phong WHERE id = '$id_phong_details'"))["so_nguoi"];

        if (isset($_SESSION['booking_data'])) {
            $booking_data = $_SESSION['booking_data'];
            $going_flight = base64_decode(urldecode($booking_data['going_flight']));
            $return_flight = base64_decode(urldecode($booking_data['return_flight']));
            $going_seat_id_decode = base64_decode(urldecode($booking_data['going_seat_id']));
            $return_seat_id_decode = base64_decode(urldecode($booking_data['return_seat_id']));
            $going_price_decode = base64_decode(urldecode($booking_data['going_price']));
            $return_price_decode = base64_decode(urldecode($booking_data['return_price']));
            $so_nguoi_decode = base64_decode(urldecode($booking_data['so_nguoi_bay']));

            $so_phong_decode = base64_decode(urldecode($booking_data['so_phong_bay']));

            $sql_di = "SELECT gcb.id FROM ghe_chuyenbay gcb 
            JOIN chuyenbay cb ON gcb.id_chuyenbay = cb.id
            JOIN ghe g ON gcb.id_ghe = g.id 
            WHERE gcb.id_chuyenbay = $going_flight AND g.id = $going_seat_id_decode";

            $sql_ve = "SELECT gcb.id FROM ghe_chuyenbay gcb 
            JOIN chuyenbay cb ON gcb.id_chuyenbay = cb.id
            JOIN ghe g ON gcb.id_ghe = g.id 
            WHERE gcb.id_chuyenbay = $return_flight AND g.id = $return_seat_id_decode";
            $result_di = mysqli_query($conn, $sql_di);
            $result_ve = mysqli_query($conn, $sql_ve);
            $id_ghe_chuyenbay_di = mysqli_fetch_assoc($result_di)['id'];
            $id_ghe_chuyenbay_ve = mysqli_fetch_assoc($result_ve)['id'];
            $txt = " và vé máy bay";
        } else {
            $going_price_decode = 0;
            $return_price_decode = 0;
            $so_nguoi_decode = 1;
            $so_phong_decode = 1;
            $txt = "";
        }
        if (isset($_SESSION['data_check_in'])) {
            $data_check_in = $_SESSION['data_check_in'];
            $so_phong_data_check_in = $data_check_in['so_phong'];
        } else {
            $so_phong_data_check_in = 1;
        }
        function tinh_tong_tien($gia_phong, $giagiam, $giagoidichvu, $dichvu = [], $goidichvubooking = NULL, $conn, $songay, $going_price_decode, $return_price_decode, $so_nguoi_decode)
        {
            $tong_tien_gia_dich_vu_ngoai_goi = 0;
            if ($goidichvubooking) {
                $dichvu_trong_goi = [];
                $query_goi = "SELECT id FROM dichvu WHERE id_goi_dich_vu = '$goidichvubooking'";
                $result_goi = mysqli_query($conn, $query_goi);
                while ($row_goi = mysqli_fetch_assoc($result_goi)) {
                    $dichvu_trong_goi[] = $row_goi['id'];
                }

                foreach ($dichvu as $id_dichvu) {
                    if (!in_array($id_dichvu, $dichvu_trong_goi)) {
                        $query_dichvu = "SELECT don_gia FROM dichvu WHERE id = '$id_dichvu'";
                        $result_dichvu = mysqli_query($conn, $query_dichvu);
                        if ($row_dichvu = mysqli_fetch_assoc($result_dichvu)) {
                            $tong_tien_gia_dich_vu_ngoai_goi += $row_dichvu["don_gia"];
                        }
                    }
                }

                $total_amount = $gia_phong * $songay + $giagoidichvu + $tong_tien_gia_dich_vu_ngoai_goi + $going_price_decode * $so_nguoi_decode + $return_price_decode * $so_nguoi_decode;
            } else {
                foreach ($dichvu as $id_dichvu) {
                    $query_dichvu = "SELECT don_gia FROM dichvu WHERE id = '$id_dichvu'";
                    $result_dichvu = mysqli_query($conn, $query_dichvu);
                    if ($row_dichvu = mysqli_fetch_assoc($result_dichvu)) {
                        $tong_tien_gia_dich_vu_ngoai_goi += $row_dichvu["don_gia"];
                    }
                }
                $total_amount = $gia_phong * $songay + $tong_tien_gia_dich_vu_ngoai_goi + $going_price_decode * $so_nguoi_decode + $return_price_decode * $so_nguoi_decode;
            }

            if ($giagiam) {
                $total_amount -= ($giagiam / 100) * $total_amount;
            }

            return $total_amount;
        }

        function insertPhieu($id_uu_dai = NULL, $id_khach_hang, $id_goi_dich_vu = NULL, $id_phong, $so_nguoi, $tong_tien, $ngay_nhan_phong, $ngay_tra_phong, $trang_thai)
        {
            global $conn;
            $id_uu_dai_value = $id_uu_dai ? "'$id_uu_dai'" : "NULL";
            $id_goi_dich_vu_value = $id_goi_dich_vu ? "'$id_goi_dich_vu'" : "NULL";
            $sql = "INSERT INTO phongdat (id_uu_dai, id_khach_hang, id_goi_dich_vu, id_phong, so_nguoi, tong_tien, ngay_nhan_phong, ngay_tra_phong, trang_thai)
                    VALUES ($id_uu_dai_value, '$id_khach_hang', $id_goi_dich_vu_value, '$id_phong', '$so_nguoi', '$tong_tien', '$ngay_nhan_phong', '$ngay_tra_phong', '$trang_thai')";
            return mysqli_query($conn, $sql);
        }
        function insertDichVu($id_phong_dat, $id_dich_vu, $trang_thai, $so_lan_su_dung)
        {
            global $conn;
            $sql = "INSERT INTO dichvudat (id_phong_dat, id_dich_vu, trang_thai, so_lan_su_dung) VALUES ('$id_phong_dat', '$id_dich_vu', '$trang_thai', '$so_lan_su_dung')";
            return mysqli_query($conn, $sql);
        }
        if ($so_phong_decode > 1) {
            for ($i = 0; $i < $so_phong_decode; $i++) {
                $tinh = tinh_tong_tien($gia_phong, $giagiam, $giagoidichvu, $dichvu, $goidichvubooking, $conn, $songay, $going_price_decode, $return_price_decode, $so_nguoi_decode);
                $insert_phong_dat = insertPhieu($magiamgiabooking, $id_user, $goidichvubooking, $id_phong_details, $so_nguoi, $tinh, $ngaynhanphongbooking, $ngaytraphongbooking, '0');
                if ($insert_phong_dat) {
                    $id_phong_dat = mysqli_insert_id($conn);
                    $all_dichvu = [];
                    if ($dichvu) {
                        foreach ($dichvu as $id_dichvu) {
                            if (!in_array($id_dichvu, $all_dichvu)) {
                                $all_dichvu[] = $id_dichvu;
                            }
                        }
                    }
                    if ($goidichvubooking) {
                        $sql_insert_dich_vu_goi = "SELECT id FROM dichvu WHERE id_goi_dich_vu = '$goidichvubooking'";
                        $result_insert_dich_vu_goi = mysqli_query($conn, $sql_insert_dich_vu_goi);
                        while ($row_insert_dich_vu_goi = mysqli_fetch_assoc($result_insert_dich_vu_goi)) {
                            if (!in_array($row_insert_dich_vu_goi['id'], $all_dichvu)) {
                                $all_dichvu[] = $row_insert_dich_vu_goi['id'];
                            }
                        }
                    }
                    foreach ($all_dichvu as $id_dichvu) {
                        $insert_dich_vu = insertDichVu($id_phong_dat, $id_dichvu, '0', '0');
                    }
                }
            }
        } elseif ($so_phong_data_check_in > 1) {
            for ($i = 0; $i < $so_phong_data_check_in; $i++) {
                $tinh = tinh_tong_tien($gia_phong, $giagiam, $giagoidichvu, $dichvu, $goidichvubooking, $conn, $songay, $going_price_decode, $return_price_decode, $so_nguoi_decode);
                $insert_phong_dat = insertPhieu($magiamgiabooking, $id_user, $goidichvubooking, $id_phong_details, $so_nguoi, $tinh, $ngaynhanphongbooking, $ngaytraphongbooking, '0');
                if ($insert_phong_dat) {
                    $id_phong_dat = mysqli_insert_id($conn);
                    $all_dichvu = [];
                    if ($dichvu) {
                        foreach ($dichvu as $id_dichvu) {
                            if (!in_array($id_dichvu, $all_dichvu)) {
                                $all_dichvu[] = $id_dichvu;
                            }
                        }
                    }
                    if ($goidichvubooking) {
                        $sql_insert_dich_vu_goi = "SELECT id FROM dichvu WHERE id_goi_dich_vu = '$goidichvubooking'";
                        $result_insert_dich_vu_goi = mysqli_query($conn, $sql_insert_dich_vu_goi);
                        while ($row_insert_dich_vu_goi = mysqli_fetch_assoc($result_insert_dich_vu_goi)) {
                            if (!in_array($row_insert_dich_vu_goi['id'], $all_dichvu)) {
                                $all_dichvu[] = $row_insert_dich_vu_goi['id'];
                            }
                        }
                    }
                    foreach ($all_dichvu as $id_dichvu) {
                        $insert_dich_vu = insertDichVu($id_phong_dat, $id_dichvu, '0', '0');
                    }
                }
            }
        } else {
            $tinh = tinh_tong_tien($gia_phong, $giagiam, $giagoidichvu, $dichvu, $goidichvubooking, $conn, $songay, $going_price_decode, $return_price_decode, $so_nguoi_decode);
            $insert_phong_dat = insertPhieu($magiamgiabooking, $id_user, $goidichvubooking, $id_phong_details, $so_nguoi, $tinh, $ngaynhanphongbooking, $ngaytraphongbooking, '0');
            if ($insert_phong_dat) {
                $id_phong_dat = mysqli_insert_id($conn);
                $all_dichvu = [];
                if ($dichvu) {
                    foreach ($dichvu as $id_dichvu) {
                        if (!in_array($id_dichvu, $all_dichvu)) {
                            $all_dichvu[] = $id_dichvu;
                        }
                    }
                }
                if ($goidichvubooking) {
                    $sql_insert_dich_vu_goi = "SELECT id FROM dichvu WHERE id_goi_dich_vu = '$goidichvubooking'";
                    $result_insert_dich_vu_goi = mysqli_query($conn, $sql_insert_dich_vu_goi);
                    while ($row_insert_dich_vu_goi = mysqli_fetch_assoc($result_insert_dich_vu_goi)) {
                        if (!in_array($row_insert_dich_vu_goi['id'], $all_dichvu)) {
                            $all_dichvu[] = $row_insert_dich_vu_goi['id'];
                        }
                    }
                }
                foreach ($all_dichvu as $id_dichvu) {
                    $insert_dich_vu = insertDichVu($id_phong_dat, $id_dichvu, '0', '0');
                }
            }
        }

        function update_phong($id_phong)
        {
            global $conn;
            $sql_check = "
                SELECT COUNT(*) as total 
                FROM phongdat 
                WHERE id_phong = '$id_phong'
                ";
            $result_check = mysqli_query($conn, $sql_check);
            $row = mysqli_fetch_assoc($result_check);

            if ($row['total'] >= 10) {
                $sql_update = "UPDATE phong SET trang_thai = '0' WHERE id = '$id_phong'";
                return mysqli_query($conn, $sql_update);
            }
            return false;
        }
        $update_phong_da_dat = update_phong($id_phong_details);
        $encoded_id = urlencode(base64_encode($id_phong_details));
        function insertTTNB($ho, $ten, $ngay_sinh, $gioi_tinh, $sdt, $cccd, $quoc_tich)
        {
            global $conn;
            $sql = "INSERT INTO thongtinnguoibay (ho, ten, ngay_sinh, gioi_tinh, sdt, cccd, quoc_tich) 
            VALUES ('$ho', '$ten', '$ngay_sinh', '$gioi_tinh', '$sdt', '$cccd', '$quoc_tich')";
            if (mysqli_query($conn, $sql)) {
                return mysqli_insert_id($conn);
            } else {
                return false;
            }
        }
        function insertVeMayBay($id_ghe_chuyenbay, $id_khach_hang, $id_nguoi_bay, $id_phong_dat)
        {
            global $conn;
            $sql = "INSERT INTO vemaybay (ngay_dat_ve, trang_thai, id_ghe_chuyenbay, id_khach_hang, id_nguoi_bay, id_phong_dat) VALUES (CURRENT_TIMESTAMP(), 1, $id_ghe_chuyenbay, $id_khach_hang, $id_nguoi_bay, $id_phong_dat)";
            return mysqli_query($conn, $sql);
        }
        if (isset($_REQUEST['inputHoDem']) && is_array($_REQUEST['inputHoDem'])) {
            foreach ($_REQUEST['inputHoDem'] as $key => $value) {
                $ho_dem = $value;
                $ten = $_REQUEST['inputTen'][$key];
                $ngay_sinh = $_REQUEST['ngaysinh'][$key];
                $gioi_tinh = $_REQUEST['gioitinh'][$key];
                $sdtbay = $_REQUEST['sdtbay'][$key];
                $cccd = $_REQUEST['cccd'][$key];
                $quoc_tich = $_REQUEST['quoctich'][$key];
                $insertTTNB_Di = insertTTNB($ho_dem, $ten, $ngay_sinh, $gioi_tinh, $sdtbay, $cccd, $quoc_tich);
                if ($insertTTNB_Di) {
                    $id_nguoi_bay_di = $insertTTNB_Di;
                    $insertVeMayBayDi = insertVeMayBay($id_ghe_chuyenbay_di, $id_user, $id_nguoi_bay_di, $id_phong_dat);
                }
                $insertTTNB_Ve = insertTTNB($ho_dem, $ten, $ngay_sinh, $gioi_tinh, $sdtbay, $cccd, $quoc_tich);
                if ($insertTTNB_Ve) {
                    $id_nguoi_bay_ve = $insertTTNB_Ve;
                    $insertVeMayBayVe = insertVeMayBay($id_ghe_chuyenbay_ve, $id_user, $id_nguoi_bay_ve, $id_phong_dat);
                }
            }
        }

        if ($insert_phong_dat) {
            $message = 'Đặt phòng' . $txt . ' thành công!';
            $redirect_url = "mybooking.php";
            unset($_SESSION['booking_data']);
        } else {
            $message = 'Đặt phòng' . $txt . ' thất bại!';
            $redirect_url = "booking.php?id=$encoded_id";
        }
    }
    ?>
    <?php if (isset($message)): ?>
        <div id="signup-notification" class="alert <?php echo ($insert_phong_dat) ? 'alert-success' : 'alert-danger'; ?> text-center" role="alert">
            <?php echo $message; ?>
        </div>
        <script type="text/javascript">
            setTimeout(function() {
                window.location.href = "<?php echo $redirect_url; ?>";
            }, 1000);
        </script>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Đặt phòng - <?php echo $row_phong['ten_phong'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="#" class="text-secondary text-decoration-none;">Phòng</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none;">Đặt Phòng</a>
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
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form class="row g-3" method="post" action="" id="bookingForm">
                            <div class="col-md-5">
                                <label class="form-label">Họ Tên</label>
                                <input type="text" class="form-control" value="<?php echo $user_name; ?>" name="hotenbooking">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo $user_email; ?>" name="emailbooking">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa Chỉ</label>
                                <input type="text" class="form-control" value="<?php echo $user_diachi; ?>" name="diachibooking">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control" value="<?php echo $user_sdt; ?>" name="sdtbooking">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mã Giảm Giá</label>
                                <input type="text" class="form-control" name="magiamgiabooking">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Gói Dịch Vụ</label>
                                <?php
                                $query_goidichvu = "SELECT * FROM goidichvu";
                                $result_goidichvu = mysqli_query($conn, $query_goidichvu);
                                if (mysqli_num_rows($result_goidichvu) > 0) {
                                    echo '<select class="form-select" name="goidichvubooking" id="goiDichVu">';
                                    echo '<option value="" selected>Không</option>';
                                    while ($rowdv = mysqli_fetch_assoc($result_goidichvu)) {
                                        echo '<option value="' . $rowdv['id'] . '">' . $rowdv['ten_goi_dich_vu'] . '</option>';
                                    }
                                    echo '</select>';
                                } else {
                                    echo 'Chưa có gói dịch vụ';
                                }
                                ?>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Dịch Vụ</label>
                                <?php
                                $query_dichvu = "SELECT * FROM dichvu";
                                $result_dichvu = mysqli_query($conn, $query_dichvu);
                                if (mysqli_num_rows($result_dichvu) > 0) {
                                    echo '<select class="chzn-select" multiple="true" name="dichvu[]" id="dichVu">';
                                    while ($rowdv = mysqli_fetch_assoc($result_dichvu)) {
                                        echo '<option value="' . $rowdv['id'] . '">' . $rowdv['ten_dich_vu'] . '</option>';
                                    }
                                    echo '</select>';
                                }
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày Nhận Phòng</label>
                                <input type="date" class="form-control" name="ngaynhanphongbooking" id="ngaynhanphong" onblur="validateDate('ngaynhanphong')">
                                <span id="ngaynhanphong-error" class="error-message"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày Trả Phòng</label>
                                <input type="date" class="form-control" name="ngaytraphongbooking" id="ngaytraphong" onblur="validateDate('ngaytraphong')">
                                <span id="ngaytraphong-error" class="error-message"></span>
                            </div>
                            <button type="submit" class="btn w-40 btn-outline-dark " name="paying">Xác Nhận</button>
                            <a href="rooms.php" class="btn btn-sm w-100 btn-outline-dark">Quay Lại</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="row g-0 p-4">
                            <?php
                            if (isset($_SESSION['booking_data'])) {
                                $booking_data = $_SESSION['booking_data'];
                                $so_nguoi_decode = base64_decode(urldecode($booking_data['so_nguoi_bay']));
                                $col = 6;
                                for ($i = 0; $i < $so_nguoi_decode; $i++) {
                                    echo '
                                    <div class="col-md-' . $col . '">';
                                    echo '<h5>Thông tin vé ' . ($i + 1) . '</h5>';
                                    echo '<div class="row">';
                                    echo '<div class="col-md-6 mb-2">
                                        <label for="inputHoDem" class="form-label">Họ Đệm</label>
                                        <input type="text" class="form-control" name="inputHoDem[]" >
                                    </div>';
                                    echo '<div class="col-md-5 mb-2">
                                        <label for="inputTen" class="form-label">Tên</label>
                                        <input type="text" class="form-control" name="inputTen[]" >
                                    </div>';
                                    echo '<div class="col-md-6 mb-2">
                                        <label for="ngaysinh" class="form-label">Ngày Sinh</label>
                                        <input type="date" class="form-control" name="ngaysinh[]" >
                                    </div>';
                                    echo '<div class="col-md-5 mb-2">
                                        <label for="gioitinh' . $i . '" class="form-label">Giới Tính</label>
                                        <select class="form-select" id="gioitinh' . $i . '" name="gioitinh[]" >
                                            <option value="">Chọn giới tính</option>
                                            <option value="1">Nam</option>
                                            <option value="0">Nữ</option>
                                        </select>
                                    </div>';
                                    echo '<div class="col-md-5 mb-2">
                                        <label for="cccd" class="form-label">CCCD</label>
                                        <input type="text" class="form-control" name="cccd[]">
                                    </div>';
                                    echo '<div class="col-md-3 mb-2">
                                        <label for="sdtbay" class="form-label">Số Điện Thoại</label>
                                        <input type="tel" class="form-control" name="sdtbay[]" >
                                    </div>';
                                    echo '<div class="col-md-3 mb-2">
                                        <label for="quoctich" class="form-label">Quốc Tịch</label>
                                        <input type="text" class="form-control" name="quoctich[]" >
                                    </div>';
                                    echo '</div>
                                     <div id="errorMessage" style="color: red; font-weight: bold; margin-top: 10px;"></div>
                                     </div>';
                                }
                            } else {
                                $col = 12;
                            }
                            ?>
                            </form>
                            <?php
                            echo '<div class="col-md-' . $col . '">
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
                                </div>
                            </div>
                            ';

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
            document.getElementById("bookingForm").addEventListener("submit", function(event) {
                const isNgayNhanPhongValid = validateDate('ngaynhanphong');
                const isNgayTraPhongValid = validateDate('ngaytraphong');

                if (!isNgayNhanPhongValid || !isNgayTraPhongValid) {
                    event.preventDefault();
                    errorSpan.textContent = "Vui lòng chọn ngày.";
                }
            });

            function validateDate(dateFieldId) {
                const {
                    DateTime
                } = luxon;
                const inputDate = document.getElementById(dateFieldId).value;
                const errorSpan = document.getElementById(dateFieldId + "-error");

                if (!inputDate) {
                    errorSpan.textContent = "Vui lòng chọn ngày.";
                    return false;
                }

                const selectedDate = DateTime.fromISO(inputDate, {
                    zone: 'Asia/Ho_Chi_Minh'
                }).startOf('day');
                const currentDate = DateTime.now().setZone('Asia/Ho_Chi_Minh').startOf('day');

                if (dateFieldId === 'ngaynhanphong' && selectedDate <= currentDate) {
                    errorSpan.textContent = "Ngày nhận phòng phải sau ngày hiện tại.";
                    return false;
                }

                if (dateFieldId === 'ngaytraphong') {
                    const ngayNhanPhong = DateTime.fromISO(
                        document.getElementById('ngaynhanphong').value, {
                            zone: 'Asia/Ho_Chi_Minh'
                        }
                    ).startOf('day');

                    if (selectedDate <= ngayNhanPhong) {
                        errorSpan.textContent = "Ngày trả phòng phải sau ngày nhận phòng.";
                        return false;
                    }
                }
                errorSpan.textContent = "";
                return true;
            }
        </script>
        <script>
            document.getElementById("bookingForm").addEventListener("submit", function(event) {
                let isValid = true;
                let errorMessage = "";
                const errorDiv = document.getElementById("errorMessage");
                errorDiv.innerHTML = "";
                // Validate date fields
                const today = new Date();
                const todayDateString = today.toISOString().split("T")[0];
                document.querySelectorAll('input[name="inputHoDem[]"]').forEach((input) => {
                    if (input.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Họ đệm không được để trống.";
                    }
                });

                document.querySelectorAll('input[name="inputTen[]"]').forEach((input) => {
                    if (input.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Tên không được để trống.";
                    }
                });

                document.querySelectorAll('input[name="ngaysinh[]"]').forEach((input) => {
                    if (input.value === "") {
                        isValid = false;
                        errorMessage = "Ngày sinh không được để trống.";
                    } else if (input.value >= todayDateString) {
                        isValid = false;
                        errorMessage = "Ngày sinh phải nhỏ hơn ngày hiện tại.";
                    }
                });

                document.querySelectorAll('select[name="gioitinh[]"]').forEach((select) => {
                    if (select.value === "") {
                        isValid = false;
                        errorMessage = "Giới tính chưa được chọn.";
                    }
                });

                document.querySelectorAll('input[name="sdtbay[]"]').forEach((input) => {
                    if (input.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Số điện thoại không được để trống.";
                    } else if (!/^\d{10,11}$/.test(input.value.trim())) {
                        isValid = false;
                        errorMessage = "Số điện thoại không hợp lệ. Vui lòng nhập từ 10-11 chữ số.";
                    }
                });
                document.querySelectorAll('input[name="cccd[]"]').forEach((input) => {
                    input.addEventListener('input', () => {
                        if (input.value.trim() === "") {
                            isValid = false;
                            errorMessage = "CCCD không được để trống.";
                        } else if (!/^\d{12}$/.test(input.value.trim())) {
                            isValid = false;
                            errorMessage = "CCCD không hợp lệ. Vui lòng nhập đúng 12 chữ số.";
                        }
                    });
                });
                document.querySelectorAll('input[name="quoctich[]"]').forEach((input) => {
                    if (input.value.trim() === "") {
                        isValid = false;
                        errorMessage = "Quốc tịch không được để trống.";
                    }
                });
                if (!isValid) {
                    errorDiv.innerHTML = errorMessage;
                    event.preventDefault();
                }
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/3.0.1/luxon.min.js"></script>
</body>

</html>