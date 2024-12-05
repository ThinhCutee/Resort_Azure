<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
    <!-- Links -->
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .info-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .info-group h6 {
            margin-bottom: 0;
        }

        .info-group .badge {
            margin-left: 0.5rem;
        }

        .info-danh-gia {
            display: flex;
            justify-content: space-between;
        }

        h5 {
            font-size: 16px;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .fas.fa-exclamation-circle {
            margin-right: 10px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php
    require('inc/header.php');
    ?>
    <!-- Services giới thiệu-->
    <div class="my-5 px-4">
        <h2 class="mt-5 pt-4 text-center fw-bold">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3 text-center">
            Chúng tôi luôn sẵn lòng hỗ trợ bạn với mọi thắc mắc và yêu cầu. </p>
        <p class="text-center mt-3 text-center">
            Đừng ngần ngại liên hệ với chúng tôi bất cứ lúc nào để có thông tin chi tiết về các dịch vụ của Nha Trang
            Azure hoặc để đặt chỗ.
        </p>
    </div>
    <!-- Container -->
    <div class="container">
        <div class="row">
            <!-- Search rooms -->
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 px-lg-0">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2 mb-3">Tìm Kiếm Phòng</h4>
                        <form action="rooms.php" method="post">
                            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                                <div class="border bg-light p-3 rounded mb-3">
                                    <label class="form-label">Check-in</label>
                                    <input type="date" class="form-control shadow-none mb-3" name="checkinroom" id="checkinroom">
                                    <label class="form-label">Check-out</label>
                                    <input type="date" class="form-control shadow-none mb-3" name="checkoutroom" id="checkoutroom">
                                </div>
                                <div class="border bg-light p-3 rounded mb-3">
                                    <h5 class="mb-3" style="font-size: 18px;">Địa Điểm Và Khách Sạn</h5>
                                    <select name="address" id="address" class="form-select shadow-none form-control" required>
                                        <?php
                                        $sql_dia_chi = "SELECT DISTINCT dia_chi FROM khachsan";
                                        $result_dia_chi = mysqli_query($conn, $sql_dia_chi);
                                        echo '<option value="" selected>Điểm đến, khách sạn</option>';
                                        while ($row_dia_chi = mysqli_fetch_assoc($result_dia_chi)) {
                                            $dia_chi = $row_dia_chi['dia_chi'];
                                            $sql_khach_san = "SELECT id, ten_khach_san FROM khachsan WHERE dia_chi = '$dia_chi'";
                                            $result_khach_san = mysqli_query($conn, $sql_khach_san);
                                            echo '<optgroup label="' . $dia_chi . '">';
                                            while ($row_khach_san = mysqli_fetch_assoc($result_khach_san)) {
                                                $id_khach_san = $row_khach_san['id'];
                                                $ten_khach_san = $row_khach_san['ten_khach_san'];
                                                echo '<option value="' . $id_khach_san . '">' . $ten_khach_san . '</option>';
                                            }
                                            echo '</optgroup>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="border bg-light p-3 rounded mb-3">
                                    <h5 class="mb-3" style="font-size: 18px;">Số Người Và Số Phòng</h5>
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <label class="form-label">Số Người</label>
                                            <input type="number" class="form-control shadow-none" value="1" name="numberpeople" min="1" max="4">
                                        </div>

                                        <div class="me-3">
                                            <label class="form-label">Số Phòng</label>
                                            <input type="number" class="form-control shadow-none" value="1" name="numberroom" min="1" max="4">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-lg-3 d-flex justify-content-between">
                                    <button type="submit" class="btn text-white custom-bg" name="checkin">Tìm Kiếm</button>
                                    <a href="index.php" class="btn btn-sm text-white custom-bg">Quay Lại</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>

            <!-- cards -->
            <div class="col-lg-9 col-md-12 px-4">
                <?php
                function getAllRoom()
                {
                    global $conn;
                    $sql_rooms = "SELECT p.*, ks.ten_khach_san, ks.dia_chi
                                    FROM phong p
                                    LEFT JOIN phongdat pd ON p.id = pd.id_phong
                                    LEFT JOIN khachsan ks ON p.id_khach_san = ks.id
                                    GROUP BY p.id
                                    HAVING COUNT(pd.id_phong) <= 10 AND p.trang_thai = 1";
                    $query_rooms = mysqli_query($conn, $sql_rooms);
                    return $query_rooms;
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
                if (isset($_REQUEST['checkin']) || isset($_SESSION['data_check_in'])) {
                    function getAllRoomCheckIn($dia_diem, $ngay_nhan, $tra_phong, $so_nguoi, $so_luong_phong)
                    {
                        global $conn;
                        $sql_get_room_check_in = '
                                                SELECT p.*, ks.ten_khach_san, ks.dia_chi
                                                FROM phong p
                                                LEFT JOIN phongdat pd 
                                                    ON p.id = pd.id_phong 
                                                    AND (pd.ngay_nhan_phong <= ? AND pd.ngay_tra_phong >= ?)
                                                LEFT JOIN khachsan ks ON p.id_khach_san = ks.id
                                                WHERE ks.id = ?
                                                AND p.trang_thai = 1
                                                AND p.so_nguoi >= ?
                                                GROUP BY p.id
                                                HAVING ( 
                                                    COUNT(pd.id_phong) = 0  
                                                    OR 
                                                    (COUNT(pd.id_phong) <= 10 AND COUNT(pd.id_phong) >= ?)
                                                )';
                        $stmt = mysqli_prepare($conn, $sql_get_room_check_in);
                        mysqli_stmt_bind_param($stmt, "sssss", $ngay_nhan, $tra_phong, $dia_diem, $so_nguoi, $so_luong_phong);
                        mysqli_stmt_execute($stmt);
                        $query_get_room_check_in = mysqli_stmt_get_result($stmt);
                        return $query_get_room_check_in;
                    }
                    $dia_diem = !empty($_REQUEST['address']) ? $_REQUEST['address'] : NULL;
                    $ngay_nhan = $_REQUEST['checkinroom'];
                    $tra_phong = $_REQUEST['checkoutroom'];
                    $so_nguoi = $_REQUEST['numberpeople'];
                    $so_phong = $_REQUEST['numberroom'];
                    if (isset($_REQUEST['checkin'])) {
                        $dia_diem = !empty($_REQUEST['address']) ? $_REQUEST['address'] : NULL;
                        $ngay_nhan = $_REQUEST['checkinroom'];
                        $tra_phong = $_REQUEST['checkoutroom'];
                        $so_nguoi = $_REQUEST['numberpeople'];
                        $so_phong = $_REQUEST['numberroom'];
                    } else {
                        $dia_diem = !empty($_SESSION['data_check_in']['dia_diem']) ? $_SESSION['data_check_in']['dia_diem'] : NULL;
                        $ngay_nhan = $_SESSION['data_check_in']['ngay_nhan'];
                        $tra_phong = $_SESSION['data_check_in']['tra_phong'];
                        $so_nguoi = $_SESSION['data_check_in']['so_nguoi'];
                        $so_phong = $_SESSION['data_check_in']['so_phong'];
                    }
                    if (!isset($_SESSION['data_check_in'])) {
                        $_SESSION['data_check_in'] = [
                            'dia_diem' => $dia_diem,
                            'ngay_nhan' => $ngay_nhan,
                            'tra_phong' => $tra_phong,
                            'so_nguoi' => $so_nguoi,
                            'so_phong' => $so_phong
                        ];
                    }
                    $result = getAllRoomCheckIn($dia_diem, $ngay_nhan, $tra_phong, $so_nguoi, $so_phong);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row_rooms = mysqli_fetch_assoc($result)) {
                            $encoded_id = urlencode(base64_encode($row_rooms['id']));
                            $id = $encoded_id;
                            $ten_phong = $row_rooms['ten_phong'];
                            $gia = $row_rooms['gia'];
                            $hang_phong = ($row_rooms['hang_phong'] == 0) ? "Thường" : (($row_rooms['hang_phong'] == 1) ? "VIP" : "Tổng Thống");
                            $loai_phong = ($row_rooms['loai_phong'] == 0) ? "Giường Đơn" : "Giường Đôi";
                            $dien_tich = $row_rooms['dien_tich'];
                            $so_nguoi = $row_rooms['so_nguoi'];
                            $hinh_anh = $row_rooms['hinh_anh'];
                            $get_danh_gia = getDanhGia($row_rooms['id']);
                            $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
                            $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;
                            $hotel_name = $row_rooms['ten_khach_san'];
                            $hotel_address = $row_rooms['dia_chi'];
                            echo '
                            <div class="card mb-4 border-0 shadow">
                                <div class="row g-0 p-4 align-items-center">
                                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                        <img src="./admin/uploads/' . $hinh_anh . '" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                        <h5 class="mb-3">' . $ten_phong . '</h5>
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
                                            <h6 >Số Người:</h6>
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
                                        <div class="info-group">
                                            <h6 >Khách Sạn:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_name . '</span>
                                        </div>
                                        <div class="info-group">
                                            <h6 >Địa Điểm:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_address . '</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                                        <a href="booking.php?id=' . $id . '" class="btn btn-sm w-100 text-white custom-bg mb-2">Đặt Phòng</a>
                                        <a href="chitietphong.php?id=' . $id . '" class="btn btn-sm w-100 btn-outline-dark">Chi Tiết</a>
                                    </div>
                                </div>
                            </div>
                        ';
                        }
                    } else {
                        echo '<div class="card mb-4 border-0 shadow">
                                <div class="card-body text-center">
                                    <h5 class="mb-3 text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Hiện tại, chúng tôi không có phòng theo yêu cầu check-in của bạn, vui lòng thử lại sau!
                                    </h5>
                                    <a href="index.php" class="btn btn-sm text-white custom-bg">Quay Lại Trang Chủ</a>
                                </div>
                            </div>';
                    }
                } elseif (isset($_REQUEST['timphongbay']) || isset($_SESSION['booking_data'])) {
                    function getFindRoom($dia_diem, $ngay_nhan, $tra_phong, $so_nguoi, $so_luong_phong)
                    {
                        global $conn;
                        $sql_get_room_check_in = '
                                                SELECT p.*, ks.ten_khach_san, ks.dia_chi
                                                FROM phong p
                                                LEFT JOIN phongdat pd 
                                                    ON p.id = pd.id_phong 
                                                    AND (pd.ngay_nhan_phong <= ? AND pd.ngay_tra_phong >= ?)
                                                LEFT JOIN khachsan ks ON p.id_khach_san = ks.id
                                                WHERE ks.dia_chi = ?
                                                AND p.trang_thai = 1
                                                AND p.so_nguoi >= ?
                                                GROUP BY p.id
                                                HAVING ( 
                                                    COUNT(pd.id_phong) = 0  
                                                    OR 
                                                    (COUNT(pd.id_phong) <= 10 AND COUNT(pd.id_phong) >= ?)
                                                )';
                        $stmt = mysqli_prepare($conn, $sql_get_room_check_in);
                        mysqli_stmt_bind_param($stmt, "sssss", $ngay_nhan, $tra_phong, $dia_diem, $so_nguoi, $so_luong_phong);
                        mysqli_stmt_execute($stmt);
                        $query_get_room_check_in = mysqli_stmt_get_result($stmt);
                        return $query_get_room_check_in;
                    }
                    if (isset($_REQUEST['timphongbay'])) {
                        $going_flight_decode = base64_decode(urldecode($_REQUEST['going_flight']));
                        $return_flight_decode = base64_decode(urldecode($_REQUEST['return_flight']));
                        $ngay_khoi_hanh_bay = $_REQUEST['ngay_khoi_hanh_bay'];
                        $ngay_den_bay = $_REQUEST['ngay_den_bay'];
                        $so_nguoi_bay = $_REQUEST['so_nguoi_bay'];
                        $so_phong_bay = $_REQUEST['so_phong_bay'];
                        $going_price = $_REQUEST['going_price'];
                        $return_price = $_REQUEST['return_price'];
                        $going_seat_id = $_REQUEST['going_seat_id'];
                        $return_seat_id = $_REQUEST['return_seat_id'];
                    } else {
                        $going_flight_decode = base64_decode(urldecode($_SESSION['booking_data']['going_flight']));
                        $return_flight_decode = base64_decode(urldecode($_SESSION['booking_data']['return_flight']));
                        $ngay_khoi_hanh_bay = $_SESSION['booking_data']['ngay_khoi_hanh_bay'];
                        $ngay_den_bay = $_SESSION['booking_data']['ngay_den_bay'];
                        $so_nguoi_bay = $_SESSION['booking_data']['so_nguoi_bay'];
                        $so_phong_bay = $_SESSION['booking_data']['so_phong_bay'];
                        $going_price = $_SESSION['booking_data']['going_price'];
                        $return_price = $_SESSION['booking_data']['return_price'];
                        $going_seat_id = $_SESSION['booking_data']['going_seat_id'];
                        $return_seat_id = $_SESSION['booking_data']['return_seat_id'];
                    }
                    $dia_diem_check = 'Nha Trang';

                    $ngay_khoi_hanh_bay_decode = base64_decode(urldecode($ngay_khoi_hanh_bay));
                    $ngay_den_bay_decode = base64_decode(urldecode($ngay_den_bay));
                    $so_nguoi_bay_decode = base64_decode(urldecode($so_nguoi_bay));
                    $so_phong_bay_decode = base64_decode(urldecode($so_phong_bay));
                    $going_price_decode = base64_decode(urldecode($going_price));
                    $return_price_decode = base64_decode(urldecode($return_price));
                    $going_seat_id_decode = base64_decode(urldecode($going_seat_id));
                    $return_seat_id_decode = base64_decode(urldecode($return_seat_id));

                    $going_flight_encode = urlencode(base64_encode($going_flight_decode));
                    $return_flight_encode = urlencode(base64_encode($return_flight_decode));
                    $dia_diem_encode = urlencode(base64_encode($dia_diem_check));
                    $ngay_khoi_hanh_bay_encode = urlencode(base64_encode($ngay_khoi_hanh_bay));
                    $ngay_den_bay_encode = urlencode(base64_encode($ngay_den_bay));
                    $so_nguoi_bay_encode = urlencode(base64_encode($so_nguoi_bay));
                    $so_phong_bay_encode = urlencode(base64_encode($so_phong_bay));
                    $going_price_encode = urlencode(base64_encode($going_price));
                    $return_price_encode = urlencode(base64_encode($return_price));
                    $going_seat_id_encode = urlencode(base64_encode($going_seat_id));
                    $return_seat_id_encode = urlencode(base64_encode($return_seat_id));
                    $encoded_id_fly = '';
                    if (!isset($_SESSION['booking_data'])) {
                        $_SESSION['booking_data'] = [
                            'id' => $encoded_id_fly,
                            'going_flight' => $going_flight_encode,
                            'return_flight' => $return_flight_encode,
                            'dia_diem' => $dia_diem_encode,
                            'ngay_khoi_hanh_bay' => $ngay_khoi_hanh_bay_encode,
                            'ngay_den_bay' => $ngay_den_bay_encode,
                            'so_nguoi_bay' => $so_nguoi_bay_encode,
                            'so_phong_bay' => $so_phong_bay_encode,
                            'going_price' => $going_price_encode,
                            'return_price' => $return_price_encode,
                            'going_seat_id' => $going_seat_id_encode,
                            'return_seat_id' => $return_seat_id_encode
                        ];
                    }
                    $result_fly = getFindRoom($dia_diem_check, $ngay_khoi_hanh_bay, $ngay_den_bay, $so_nguoi_bay, $so_phong_bay);
                    if (mysqli_num_rows($result_fly) > 0) {
                        while ($row_rooms = mysqli_fetch_assoc($result_fly)) {
                            $encoded_id_fly = urlencode(base64_encode($row_rooms['id']));
                            $ten_phong = $row_rooms['ten_phong'];
                            $gia = $row_rooms['gia'];
                            $hang_phong = ($row_rooms['hang_phong'] == 0) ? "Thường" : (($row_rooms['hang_phong'] == 1) ? "VIP" : "Tổng Thống");
                            $loai_phong = ($row_rooms['loai_phong'] == 0) ? "Giường Đơn" : "Giường Đôi";
                            $dien_tich = $row_rooms['dien_tich'];
                            $so_nguoi = $row_rooms['so_nguoi'];
                            $hinh_anh = $row_rooms['hinh_anh'];
                            $get_danh_gia = getDanhGia($row_rooms['id']);
                            $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
                            $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;
                            $hotel_name = $row_rooms['ten_khach_san'];
                            $hotel_address = $row_rooms['dia_chi'];
                            echo '
                            <div class="card mb-4 border-0 shadow">
                                <div class="row g-0 p-4 align-items-center">
                                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                        <img src="./admin/uploads/' . $hinh_anh . '" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                        <h5 class="mb-3">' . $ten_phong . '</h5>
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
                                            <h6 >Số Người:</h6>
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
                                        <div class="info-group">
                                            <h6 >Khách Sạn:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_name . '</span>
                                        </div>
                                        <div class="info-group">
                                            <h6 >Địa Điểm:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_address . '</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                                        <a href="booking.php?id=' . $encoded_id_fly . '" class="btn btn-sm w-100 text-white custom-bg mb-2">Đặt Phòng</a>
                                        <a href="chitietphong.php?id=' . $encoded_id_fly . '" class="btn btn-sm w-100 btn-outline-dark">Chi Tiết</a>
                                    </div>
                                </div>
                            </div>
                        ';
                        }
                    } else {
                        echo '<div class="card mb-4 border-0 shadow">
                                <div class="card-body text-center">
                                    <h5 class="mb-3 text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Hiện tại, chúng tôi không có phòng theo yêu cầu check-in của bạn, vui lòng thử lại sau!
                                    </h5>
                                </div>
                            </div>';
                    }
                } else {
                    $query_rooms = getAllRoom();
                    if (mysqli_num_rows($query_rooms) > 0) {
                        while ($row_rooms = mysqli_fetch_assoc($query_rooms)) {
                            $encoded_id = urlencode(base64_encode($row_rooms['id']));
                            $ten_phong = $row_rooms['ten_phong'];
                            $gia = $row_rooms['gia'];
                            $hang_phong = ($row_rooms['hang_phong'] == 0) ? "Thường" : (($row_rooms['hang_phong'] == 1) ? "VIP" : "Tổng Thống");
                            $loai_phong = ($row_rooms['loai_phong'] == 0) ? "Giường Đơn" : "Giường Đôi";
                            $dien_tich = $row_rooms['dien_tich'];
                            $so_nguoi = $row_rooms['so_nguoi'];
                            $hinh_anh = $row_rooms['hinh_anh'];
                            $get_danh_gia = getDanhGia($row_rooms['id']);
                            $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
                            $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;
                            $hotel_name = $row_rooms['ten_khach_san'];
                            $hotel_address = $row_rooms['dia_chi'];
                            echo '
                            <div class="card mb-4 border-0 shadow">
                                <div class="row g-0 p-4 align-items-center">
                                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                        <img src="./admin/uploads/' . $hinh_anh . '" class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                        <h5 class="mb-3">' . $ten_phong . '</h5>
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
                                            <h6 >Số Người:</h6>
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
                                        <div class="info-group">
                                            <h6 >Khách Sạn:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_name . '</span>
                                        </div>
                                        <div class="info-group">
                                            <h6 >Địa Điểm:</h6>
                                            <span class="badge rounded-pill bg-info text-dark text-wrap lh-base">' . $hotel_address . '</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                                        <a href="booking.php?id=' . $encoded_id . '" class="btn btn-sm w-100 text-white custom-bg mb-2">Đặt Phòng</a>
                                        <a href="chitietphong.php?id=' . $encoded_id . '" class="btn btn-sm w-100 btn-outline-dark">Chi Tiết</a>
                                    </div>
                                </div>
                            </div>
                        ';
                        }
                    } else {
                        echo '<div class="card mb-4 border-0 shadow">
                                <div class="card-body text-center">
                                    <h5 class="mb-3 text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Hiện tại, chúng tôi không có phòng, vui lòng thử lại sau!
                                    </h5>
                                </div>
                            </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php
    require('inc/footer.php');
    ?>
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
    <script>
        let today = new Date();
        let formattedCheckinDate = today.toISOString().split('T')[0];
        document.getElementById("checkinroom").value = formattedCheckinDate;
        today.setDate(today.getDate() + 2);
        let formattedCheckoutDate = today.toISOString().split('T')[0];
        document.getElementById("checkoutroom").value = formattedCheckoutDate;
    </script>
    <!-- /Chatra {/literal} -->
</body>

</html>