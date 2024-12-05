<?php
session_start();
include('config/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Azure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Links -->
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="./css/css_may_bay.css">

<body>
    <!-- Header -->
    <?php
    require('inc/header.php');
    ?>
    <div class="container">
        <div class="my-5 px-4">
            <h2 class="mt-5 pt-4 text-center fw-bold"><i class="fas fa-plane"></i> OUR FLIGHT INFORMATION <i class="fas fa-plane" style="transform: rotate(180deg);"></i>
            </h2>
            <div class="h-line bg-dark"></div>
            <p class="text-center mt-3 text-center">
                Hành trình của bạn, sứ mệnh của chúng tôi.
            </p>
            <p class="text-center mt-3 text-center">
                Mỗi chuyến bay không chỉ là một hành trình, mà còn là cam kết của chúng tôi mang đến sự thoải mái, an toàn và hạnh phúc cho bạn.
            </p>
        </div>
        <div class="col-md-4 mt-2 mx-auto">
            <span id="error-tu" class="error-message" id="error-tu"></span>
            <span id="error-ngaydi" class="error-message" id="error-ngaydi"></span>
            <span id="error-ngayve" class="error-message" id="error-ngayve"></span>
        </div>
        <div class="col-lg-12 col-md-12 px-4 mt-5">
            <div class="card mb-4 border-0 shadow-sm rounded-3 mt-0">
                <div class="card-body">
                    <form action="vemaybay.php" method="post" id="flightForm">
                        <div class="row">
                            <div class="col-md-2 mt-3">
                                <label for="baytu" class="form-label"><i class="fas fa-plane"></i> Bay Từ</label>
                                <select name="tu" id="tu" class="form-select shadow-none form-control" required>
                                    <?php
                                    $bay_tu = "SELECT * FROM sanbay";
                                    $bay_tu_run = mysqli_query($conn, $bay_tu);
                                    if (mysqli_num_rows($bay_tu_run) > 0) {
                                        while ($bay_tu_row = mysqli_fetch_assoc($bay_tu_run)) {
                                            $id = $bay_tu_row['id'];
                                            $ten = $bay_tu_row['dia_diem'];
                                            echo '<option value="' . $id . '">' . $ten . ' (' . $id . ')</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 mt-3">
                                <label for="bayden" class="form-label"><i class="fas fa-plane"></i> Bay Đến</label>
                                <select name="den" id="den" class="form-select shadow-none form-control" required>
                                    <?php
                                    $bay_den = "SELECT * FROM sanbay GROUP BY id DESC";
                                    $bay_den_run = mysqli_query($conn, $bay_den);
                                    if (mysqli_num_rows($bay_den_run) > 0) {
                                        while ($bay_den_row = mysqli_fetch_assoc($bay_den_run)) {
                                            $id = $bay_den_row['id'];
                                            $ten = $bay_den_row['dia_diem'];
                                            echo '<option value="' . $id . '">' . $ten . ' (' . $id . ')</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 mt-3">
                                <label for="ngaydi" class="form-label"><i class="fas fa-calendar-day"></i> Ngày đi</label>
                                <input type="date" class="form-control" id="ngaydi" name="ngaydi" required>
                            </div>
                            <div class="col-md-2 mt-3">
                                <label for="ngayve" class="form-label"><i class="fas fa-sync-alt"></i> Khứ Hồi</label>
                                <input type="date" class="form-control" id="ngayve" name="ngayve">
                            </div>
                            <div class="col-md-2 mt-3">
                                <label for="hangghe" class="form-label"><i class="fas fa-crown"></i> Hạng Vé</label>
                                <select name="hangghe" id="hangghe" class="form-select shadow-none form-control" required>
                                    <?php
                                    $hang_ghe_qr = "SELECT hang_ghe FROM ghe GROUP BY hang_ghe";
                                    $get_hang_ghe = mysqli_query($conn, $hang_ghe_qr);
                                    if (mysqli_num_rows($get_hang_ghe) > 0) {
                                        while ($row_hang_ghe = mysqli_fetch_assoc($get_hang_ghe)) {
                                            $hang_ghe = $row_hang_ghe['hang_ghe'];
                                            echo '<option value="' . $hang_ghe . '">' . $hang_ghe . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1 mt-3">
                                <label for="songuoi" class="form-label"><i class="fas fa-user"></i> Số người</label>
                                <input type="number" class="form-control" id="songuoi" name="songuoi" min="1" placeholder="Số người 1-4" max="4" value="1">
                            </div>
                            <div class="col-md-1 mt-3">
                                <label for="sophong" class="form-label"><i class="fas fa-bed"></i> Phòng</label>
                                <input type="number" class="form-control" id="sophong" name="sophong" min="1" placeholder="Số phòng 1-4" max="4" value="1">
                            </div>
                        </div>
                        <div class="col-md-1 mx-auto"><button type="submit" class="btn btn-primary mt-3" name="timvebay">Tìm Kiếm</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 px-4 mt-5">
            <div class="card-body">
                <div class="row">
                    <?php
                    function getChuyenBayFind($diem_khoi_hanh, $diem_den, $ngay_di, $ngay_ve, $so_nguoi, $hang_ghe)
                    {
                        global $conn;
                        $query_chuyen_bay = "
                        SELECT 
                            cb.id AS id_chuyenbay,
                            cb.so_hieu_chuyen_bay,
                            cb.ngay_khoi_hanh,
                            cb.ngay_den,
                            cb.gio_khoi_hanh,
                            cb.gio_den,
                            cb.thoi_luong_bay,
                            sbxp.dia_diem AS san_bay_xuat_phat,
                            sbdd.dia_diem AS san_bay_den,
                            sbxp.id AS ma_san_bay_xuat_phat,
                            sbdd.id AS ma_san_bay_den,
                            mb.so_hieu_may_bay,
                            mb.hang_may_bay,
                            g.id AS id_ghe_bay,
                            g.so_ghe,
                            g.hang_ghe,
                            g.gia,
                            g.can_nang_hanh_ly,
                            'Di' AS loai_chuyen_bay
                        FROM 
                            chuyenbay cb
                        INNER JOIN sanbay sbxp ON cb.id_san_bay_xuat_phat = sbxp.id
                        INNER JOIN sanbay sbdd ON cb.id_san_bay_den = sbdd.id
                        INNER JOIN maybay mb ON cb.id_may_bay = mb.id
                        INNER JOIN ghe_chuyenbay gcb ON cb.id = gcb.id_chuyenbay
                        INNER JOIN ghe g ON gcb.id_ghe = g.id
                        WHERE 
                            sbxp.id = '$diem_khoi_hanh'
                            AND sbdd.id = '$diem_den'
                            AND cb.ngay_khoi_hanh <= '$ngay_di'
                            AND cb.trang_thai = 0
                            AND g.hang_ghe LIKE '%$hang_ghe%'
                            AND g.so_ghe >= $so_nguoi
                        
                        UNION ALL
                        
                        SELECT 
                            cb.id AS id_chuyenbay,
                            cb.so_hieu_chuyen_bay,
                            cb.ngay_khoi_hanh,
                            cb.ngay_den,
                            cb.gio_khoi_hanh,
                            cb.gio_den,
                            cb.thoi_luong_bay,
                            sbxp.dia_diem AS san_bay_xuat_phat,
                            sbdd.dia_diem AS san_bay_den,
                            sbxp.id AS ma_san_bay_xuat_phat,
                            sbdd.id AS ma_san_bay_den,
                            mb.so_hieu_may_bay,
                            mb.hang_may_bay,
                            g.id AS id_ghe_bay,
                            g.so_ghe,
                            g.hang_ghe,
                            g.gia,
                            g.can_nang_hanh_ly,
                            'Ve' AS loai_chuyen_bay
                        FROM 
                            chuyenbay cb
                        INNER JOIN sanbay sbxp ON cb.id_san_bay_xuat_phat = sbxp.id
                        INNER JOIN sanbay sbdd ON cb.id_san_bay_den = sbdd.id
                        INNER JOIN maybay mb ON cb.id_may_bay = mb.id
                        INNER JOIN ghe_chuyenbay gcb ON cb.id = gcb.id_chuyenbay
                        INNER JOIN ghe g ON gcb.id_ghe = g.id
                        WHERE 
                            sbxp.id = '$diem_den'
                            AND sbdd.id = '$diem_khoi_hanh'
                            AND cb.ngay_khoi_hanh <= '$ngay_ve'
                            AND cb.trang_thai = 0
                            AND g.hang_ghe LIKE '%$hang_ghe%'
                            AND g.so_ghe >= $so_nguoi
                        ORDER BY ngay_khoi_hanh, gio_khoi_hanh";
                        $result = mysqli_query($conn, $query_chuyen_bay);
                        if (!$result) {
                            die('Error in SQL: ' . mysqli_error($conn));
                        }
                        return $result;
                    }
                    if (isset($_REQUEST['timvebay'])) {
                        $bay_tu = $_REQUEST['tu'];
                        $bay_den = $_REQUEST['den'];
                        $ngay_di = $_REQUEST['ngaydi'];
                        $ngay_ve = $_REQUEST['ngayve'];
                        $hang_ve = $_REQUEST['hangghe'];
                        $so_nguoi = $_REQUEST['songuoi'];
                        $so_phong = $_REQUEST['sophong'];
                        $result = getChuyenBayFind($bay_tu, $bay_den, $ngay_di, $ngay_ve, $so_nguoi, $hang_ve);
                        echo '
                            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 px-lg-0">
                                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                                    <div class="container-fluid flex-lg-column align-items-stretch">
                                        <h5 class="mt-2 mb-3">Lọc kết quả</h5>
                                        <form action="rooms.php" method="post">
                                            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                                                <div class="border bg-light p-3 rounded mb-3">
                                                    <h6>Thời gian khởi hành</h6>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f1">
                                                        <label class="form-check-label" for="f1">
                                                            0.00 - 6.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f2">
                                                        <label class="form-check-label" for="f2">
                                                            6.00 - 12.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f3">
                                                        <label class="form-check-label" for="f3">
                                                            12.00 - 18.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f4">
                                                        <label class="form-check-label" for="f4">
                                                            18.00 - 24.00
                                                        </label><br>
                                                    </div>
                                                    <h6>Thời gian khởi đến</h6>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f5">
                                                        <label class="form-check-label" for="f5">
                                                            0.00 - 6.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f6">
                                                        <label class="form-check-label" for="f6">
                                                            6.00 - 12.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f7">
                                                        <label class="form-check-label" for="f7">
                                                            12.00 - 18.00
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f8">
                                                        <label class="form-check-label" for="f8">
                                                            18.00 - 24.00
                                                        </label><br>
                                                    </div>
                                                    <h6>Hạng vé</h6>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f9">
                                                        <label class="form-check-label" for="f9">
                                                            Phổ Thông
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f10">
                                                        <label class="form-check-label" for="f10">
                                                            Thương Gia
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input class="form-check-input" type="checkbox" value="" id="f11">
                                                        <label class="form-check-label" for="f11">
                                                            Hạng Nhất
                                                        </label><br>
                                                    </div>
                                                    <h6>Hãng hàng không</h6>
                                                    <div class="mb-2">
                                                        <input type="checkbox" id="airline1" class="form-check-input">
                                                        <label for="airline1" class="form-check-label"> Vietnam Airlines <img src="https://booking.vinpearl.com/static/media/vna-inline.e62babab.svg" alt="vietnamairlines">
                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="checkbox" id="airline2" class="form-check-input">
                                                        <label for="airline2" class="form-check-label"> Vietjet Air <img src="https://booking.vinpearl.com/static/media/vjair-inline.3e9b142a.svg" alt="vietjetair">

                                                        </label><br>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="checkbox" id="airline3" class="form-check-input">
                                                        <label for="airline3" class="form-check-label"> Bamboo Airways <img src="https://booking.vinpearl.com/static/media/bamboo-inline.c14088e2.svg" alt="bambo">
                                                        </label><br>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </nav>
                            </div>';

                        $airlineLinks = [
                            'Scoot Air' => "https://cdn.brandfetch.io/id4wL7ZHFf/theme/light/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Vietnam Airlines' => "https://cdn.brandfetch.io/idnVwnjyVU/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Singapore Airlines' => "https://cdn.brandfetch.io/idf4k686Hz/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Vietjet Air' => "https://cdn.brandfetch.io/idCqvZFzLY/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Bamboo Airways' => "https://cdn.brandfetch.io/idBPxcnfwV/w/184/h/45/theme/dark/logo.png?c=1dxbfHSJFAPEGdCLU4o5B",
                            'VASCO' => "https://cdn.brandfetch.io/idWjp78WJX/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Thai Airways' => "https://cdn.brandfetch.io/idCJpVyIkv/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'Pacific Airlines' => "https://cdn.brandfetch.io/idIjA6Z_PY/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'AirAsia' => "https://cdn.brandfetch.io/idtir4lMuo/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                            'default' => "https://cdn.brandfetch.io/idnVwnjyVU/theme/dark/logo.svg?c=1dxbfHSJFAPEGdCLU4o5B",
                        ];

                        echo '<div class="col-lg-9 col-md-12 px-4">';
                        echo '<form id="flightForm1" action="rooms.php" method="post">';
                        if (mysqli_num_rows($result) > 0) {
                            echo '<button type="submit" class="btn btn-primary mb-3 mx-auto" onclick="submitFlights()" name="timphongbay" id="submitBtn" disabled>Tiếp tục</button>';
                            while ($row = mysqli_fetch_assoc($result)) {
                                $loai_chuyen_bay = $row['loai_chuyen_bay'];
                                $class = $loai_chuyen_bay === 'Ve' ? 've' : 'di';
                                $buttn = $loai_chuyen_bay === 'Di' ? 'select-going' : 'select-return';
                                $bd = $loai_chuyen_bay === 'Di' ? 'đi' : 'về';
                                $id_chuyen_bay = $row['id_chuyenbay'];
                                $id_chuyen_bay_encode = urlencode(base64_encode($id_chuyen_bay));
                                $so_hieu_chuyen_bay = $row['so_hieu_chuyen_bay'];
                                $ngay_khoi_hanh = $row['ngay_khoi_hanh'];
                                $date_part = explode('-', $ngay_khoi_hanh);
                                $formatted_date = $date_part[2] . '/' . $date_part[1] . '/' . $date_part[0];
                                $ngay_den = $row['ngay_den'];
                                $gio_khoi_hanh = $row['gio_khoi_hanh'];
                                $gio_den = $row['gio_den'];
                                $san_bay_xuat_phat = $row['san_bay_xuat_phat'];
                                $san_bay_den = $row['san_bay_den'];
                                $so_hieu_may_bay = $row['so_hieu_may_bay'];
                                $hang_may_bay = $row['hang_may_bay'];
                                $hang_may_bay_link = $airlineLinks[$hang_may_bay] ?? $airlineLinks['default'];
                                $id_ghe_bay = $row['id_ghe_bay'];
                                $hang_ghe = $row['hang_ghe'];
                                $gia = $row['gia'];
                                $can_nang_hanh_ly = $row['can_nang_hanh_ly'];
                                $ma_san_bay_xuat_phat = $row['ma_san_bay_xuat_phat'];
                                $ma_san_bay_den = $row['ma_san_bay_den'];
                                $thoi_luong_bay = $row['thoi_luong_bay'];
                                echo '
        <div class="card mb-4 border-0 shadow ' . $class . '">
            <div class="row g-0 p-4 align-items-center">
                <div class="info-group">
                    <img class="img-fluid g-width-80x" src="' . $hang_may_bay_link . '" width="80px" height="auto" alt="' . $hang_may_bay . '">
                    <div class="text-wrap lh-base">
                        <span class="d-block"><b>' . $so_hieu_chuyen_bay . '</b> ' . $hang_ghe . '</span>
                    </div>
                    <h4 class="text-danger">' . number_format($gia, 0, '.', '.') . ' VND/1 người</h4>
                </div>
                <div class="info-group">
                    <div class="d-flex flex-column justify-content-center">
                        <div class="vpt-color-v1 g-line-height-1_5">
                            <div class="g-font-weight-500 vpt-block__title g-font-size-18">' . $gio_khoi_hanh . '</div>
                        </div>
                        <div class="vpt-color-v1 g-line-height-1_5">
                            <div class="g-font-weight-500 vpt-block__title g-font-size-12 vpt-color-v4">' . $san_bay_xuat_phat . ' (' . $ma_san_bay_xuat_phat . ')</div>
                        </div>
                    </div>
                    <div class="duration-custom text-center">
                        <div class="g-my-15 d-flex align-items-center justify-content-center">
                            <div class="g-line-height-1_5 g-ml-5 w-100 g-font-size-12 vpt-color-support-info">
                                <span class="duration-text">Bay thẳng</span>
                                <p class="g-font-size-12 vpt-color-v4">' . $formatted_date . ' ' . $thoi_luong_bay . ' Giờ</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center g-pl-5">
                        <div class="vpt-color-v1 g-line-height-1_5">
                            <div class="g-font-weight-500 vpt-block__title g-font-size-18">' . $gio_den . '</div>
                        </div>
                        <div class="vpt-color-v1 g-line-height-1_5">
                            <div class="g-font-weight-500 vpt-block__title g-font-size-12 vpt-color-v4">' . $san_bay_den . ' (' . $ma_san_bay_den . ')</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" style="padding:0;">
                        <button type="button" class="btn btn-sm w-100 text-white custom-bg ' . $buttn . '" onclick="selectFlight(\'' . $id_chuyen_bay_encode . '\', \'' . $loai_chuyen_bay . '\', ' . $gia . ',' . $id_ghe_bay . ')">Chọn chuyến bay ' . $bd . '</button>
                    </div>
                    <div class="col-md-8 d-flex justify-content-end align-items-center">
                        <div class="info-container">
                            <i class="fas fa-exclamation-circle dk" style="width: 107px;"> Điều Khoản</i>
                            <div class="info-group bg-light p-3 rounded ex">
                                <ul class="list-unstyled info-list">
                                    <li class="mb-2"><strong>Thay đổi ngày/giờ bay:</strong> Được phép, mất phí 360.000VNĐ + chênh lệch nếu có</li>
                                    <li class="mb-2"><strong>Đổi hành trình:</strong> Được phép, mất phí 360.000VNĐ + chênh lệch nếu có</li>
                                    <li class="mb-2"><strong>Đổi tên:</strong> Không được phép</li>
                                    <li class="mb-2"><strong>Hoàn/hủy vé:</strong> Được phép, mất phí 500.000VNĐ</li>
                                    <li><strong>Hành lý:</strong> 10kg xách tay + 23kg (01 kiện)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
                            }
                        } else {
                            echo '<div class="card mb-4 border-0 shadow">
                                <div class="card-body text-center">
                                    <h5 class="mb-3">
                                        <i class="fas fa-exclamation-circle"></i> Xin lỗi chúng tôi không tìm được chuyến bay phù hợp
                                    </h5>
                                </div>
                            </div>';
                        }
                        echo '<input type="hidden" name="so_nguoi_bay" value="' . $so_nguoi . '">';
                        echo '<input type="hidden" name="so_phong_bay" value="' . $so_phong . '">';
                        if (isset($ngay_khoi_hanh) && isset($ngay_den)) {
                            echo '<input type="hidden" name="ngay_khoi_hanh_bay" value="' . $ngay_khoi_hanh . '">';
                            echo '<input type="hidden" name="ngay_den_bay" value="' . $ngay_den . '">';
                        }
                        echo '</form>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    require('inc/footer.php');
    ?>
    <script src="./js/js_may_bay.js"></script>

</body>

</html>