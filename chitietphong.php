<?php
session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết phòng</title>
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
    if (isset($_REQUEST['id'])) {
        $id_phong_details = base64_decode(urldecode($_REQUEST['id']));
        $id = $id_phong_details;
        $encoded_id = urlencode(base64_encode($id));
        $result_phong = getDetailRoom($id_phong_details);
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
    $get_danh_gia = getDanhGia($id);
    $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
    $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;
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

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Chi Tiết Phòng - <?php echo $row_phong['ten_phong'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="rooms.php" class="text-secondary text-decoration-none;">Phòng</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none;">Chi Tiết Phòng</a>
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
                                    ';
                        echo '<a href="booking.php?id=' . $encoded_id . '" class = "btn w-100 text-while btn-outline-dark shadow-none mb-2">Đặt Ngay</a>';
                        echo '<a href="rooms.php" class="btn btn-sm w-100 btn-outline-dark" role="button">Quay Lại</a>';
                        echo '</div>
                            </div>
                            ';
                        ?>
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
                        $danh_gia = get_danh_gia($id);
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
</body>

</html>