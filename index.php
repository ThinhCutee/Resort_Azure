<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azure Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Links -->
    <?php require('inc/links.php'); ?>
    <style>
        .info-danh-gia {
            display: flex;
            justify-content: space-between;
        }

        #chatra {
            position: fixed !important;
            left: 10px !important;
            bottom: 10px !important;
            z-index: 9999;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php
    require('inc/header.php');
    if (isset($_SESSION['success_message'])) {
        echo '
        <div id="signup-success" class="alert alert-success  text-center" role="alert">
            ' . $_SESSION['success_message'] . '
        </div>
        ';
        unset($_SESSION['success_message']);
    }
    ?>

    <!-- The slideshow -->
    <div class="container-fluid px-lg-4 mt-4">
        <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" space-between="30" effect="fade" navigation="true">
            <swiper-slide>
                <img src="./images/banner/1.png" />
            </swiper-slide>
            <swiper-slide>
                <img src="./images/banner/2.png" />
            </swiper-slide>
            <swiper-slide>
                <img src="./images/banner/3.png" />
            </swiper-slide>
            <swiper-slide>
                <img src="./images/banner/4.png" />
            </swiper-slide>
        </swiper-container>
    </div>
    <!--  Kiểm tra phòng trống (check in)-->
    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4">Check-In Booking Availability</h5>
                <form action="rooms.php" method="post">
                    <div class="row align-items-end">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label" style="font-weight: 500;" for="address">Địa Điểm</label>
                            <select name="address" id="address" class="form-select shadow-none form-control" required>
                                <?php
                                $sql_dia_chi = "SELECT DISTINCT dia_chi FROM khachsan";
                                $result_dia_chi = mysqli_query($conn, $sql_dia_chi);
                                echo '<option value="" selected>Chọn điểm đến, khách sạn theo sở thích...</option>';
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
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Ngày Nhận</label>
                            <input type="date" class="form-control shadow-none" name="checkinroom" id="checkinroom">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Trả Phòng </label>
                            <input type="date" class="form-control shadow-none" name="checkoutroom" id="checkoutroom">
                        </div>
                        <div class="col-lg-1 mb-3">
                            <label class="form-label" style="font-weight: 500;">Số Người</label>
                            <input type="number" class="form-control shadow-none" name="numberpeople" value="1" min="1" max="4">
                        </div>
                        <div class="col-lg-1 mb-3">
                            <label class="form-label" style="font-weight: 500;">Số Phòng</label>
                            <input type="number" class="form-control shadow-none" name="numberroom" value="1" min="1" max="4">
                        </div>
                        <div class="col-lg-2 mb-lg-3 ">
                            <button type="submit" class="btn text-white custom-bg" name="checkin">Check-In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Phòng của resort - review qua -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">OUR ROOM</h2>
    <div class="container">
        <div class="row">
            <?php
            function getDanhGia($id)
            {
                global $conn;

                $sql_danh_gia = "
                    SELECT p.id AS id_phong, pd.id AS id_phong_dat, dg.danh_gia
                    FROM danhgia dg
                    JOIN phongdat pd ON dg.id_phong_dat = pd.id
                    JOIN phong p ON p.id = pd.id_phong
                    WHERE p.id = '$id'
                ";

                $query_danh_gia = mysqli_query($conn, $sql_danh_gia);

                if (!$query_danh_gia) {
                    die("Lỗi truy vấn: " . mysqli_error($conn));
                }

                $tong_sao = 0;
                $so_luong_danh_gia = 0;

                while ($row = mysqli_fetch_assoc($query_danh_gia)) {
                    $tong_sao += (float)$row['danh_gia'];
                    $so_luong_danh_gia++;
                }

                $trung_binh_sao = $so_luong_danh_gia > 0 ? $tong_sao / $so_luong_danh_gia : 0;

                return [
                    'so_luong_danh_gia' => $so_luong_danh_gia,
                    'trung_binh_sao' => round($trung_binh_sao, 1)
                ];
            }
            $i = 0;
            $max_rooms = 3; // maximum number of rooms to display
            $sql_phong = "SELECT * FROM phong";
            $query_phong = mysqli_query($conn, $sql_phong);

            if (mysqli_num_rows($query_phong) > 0) {
                while ($row_phong = mysqli_fetch_assoc($query_phong)) {
                    if ($i >= $max_rooms) {
                        break;
                    }

                    $row_phong['hang_phong'] = $row_phong['hang_phong'] == 0 ? "Thường" : ($row_phong['hang_phong'] == 1 ? "VIP" : "Tổng Thống");
                    $encoded_id_row_phong = urlencode(base64_encode($row_phong['id']));
                    $id_phong = $encoded_id_row_phong;

                    $get_danh_gia = getDanhGia($row_phong['id']);

                    $count_danh_gia = $get_danh_gia['so_luong_danh_gia'] ?? 0;
                    $tb_sao = $get_danh_gia['trung_binh_sao'] ?? 0;

                    echo '<div class="col-lg-4 col-md-6 my-3">
                        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                            <img src="./admin/uploads/' . $row_phong['hinh_anh'] . '" class="card-img-top">
            
                            <div class="card-body">
                                <h5 class="card-title mb-4">' . $row_phong['ten_phong'] . '</h5>
                                <h6 class="mb-4">' . number_format($row_phong['gia']) . ' VNĐ per night</h6>
                                <div class="info-danh-gia mb-4">
                                    <h6 class="mb-1">Diện Tích</h6>
                                    <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                        ' . $row_phong['dien_tich'] . ' m<sup>2</sup>
                                    </span>
                                </div>
                                <div class="info-danh-gia mb-4">
                                    <h6 class="mb-1">Hạng Phòng</h6>
                                    <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                        ' . $row_phong['hang_phong'] . '
                                    </span>
                                </div>
                                
                                <div class="info-danh-gia mb-4">
                                    <h6 class="mb-1">Số Người</h6>
                                    <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                        ' . $row_phong['so_nguoi'] . '
                                    </span>
                                </div>
                                <div class="info-danh-gia">
                                        <h6 >Đánh Giá:</h6>
                                        <div class="info-dg">';
                    $so_sao_day = floor($tb_sao);
                    $sao_nua = ($tb_sao - $so_sao_day) >= 0.5 ? 1 : 0;
                    $sao_rong = 5 - ($so_sao_day + $sao_nua);

                    for ($j = 0; $j < $so_sao_day; $j++) {
                        echo '<span><i class="fas fa-star" style="color: gold;"></i></span>';
                    }
                    if ($sao_nua) {
                        echo '<span><i class="fas fa-star-half-alt" style="color: gold;"></i></span>';
                    }
                    for ($j = 0; $j < $sao_rong; $j++) {
                        echo '<span><i class="far fa-star" style="color: gold;"></i></span>';
                    }
                    echo '
                                        ' . $count_danh_gia . ' Đánh Giá
                                        </div>
                                        
                                </div>
                                <div class="d-flex justify-content-evenly mb-2 mt-4">
                                    <a href="booking.php?id=' . $id_phong . '" class="btn btn-sm text-white custom-bg">Book now</a>
                                    <a href="chitietphong.php?id=' . $id_phong . '" class="btn btn-sm btn-outline-dark">More details</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                    $i++;
                }
            } else {
                echo '<div class="col-lg-4 col-md-6 my-3">
                    <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                        <img src="./images/phong/room-1.jpg" class="card-img-top">
            
                        <div class="card-body">
                            <h5 class="card-title">VIP Single room with a sea view</h5>
                            <h6 class="mb-4">2.000.000 VND per night</h6>
                            <div class="features mb-4">
                                <h6 class="mb-1">Features</h6>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    1 Bed
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    1 Bathroom
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    1 Banlcony
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    3 Sofa
                                </span>
                            </div>
                            <div class="service mb-4">
                                <h6 class="mb-1">Service</h6>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    Wifi
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    Buffet breakfast
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    Gym
                                </span>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    Spa
                                </span>
                            </div>
                            <div class="guests mb-4">
                                <h6 class="mb-1">Guests</h6>
                                <span class="mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    2 Adults
                                </span>
                                <span class=" mb-1 badge rounded-pill bg-info text-dark text-wrap lh-base">
                                    0 childrens
                                </span>
                            </div>
                            <div class="d-flex justify-content-evenly mb-2">
                                <a href="rooms.php" class="btn btn-sm text-white custom-bg">Book now</a>
                                <a href="rooms.php" class="btn btn-sm btn-outline-dark">More details</a>
                            </div>
                        </div>
            
                    </div>
                </div>';
            }

            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold">More Rooms >>></a>
            </div>
        </div>
    </div>

    <!-- Dịch vụ của rì sọt -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">OUR SERVICE</h2>

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="./images/dich-vu/dinner.png" class="card-img-top">

                    <div class="card-body">
                        <h5 class="card-title text-center">Dinner on the beach at sunset</h5>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="./images/dich-vu/gym.png" class="card-img-top">

                    <div class="card-body">
                        <h5 class="card-title text-center">VIP Gym and Spa</h5>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="./images/dich-vu/breakfast.png" class="card-img-top">

                    <div class="card-body">
                        <h5 class="card-title text-center">Buffet breakfast</h5>
                    </div>

                </div>
            </div>
            <div class="col-lg-12 text-center mt-5">
                <a href="services.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold">More Service >>></a>
            </div>
        </div>
    </div>
    <!-- Map nè má -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">REACH US</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249525.14317069115!2d109.08168324550108!3d12.259756501901176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3170677811cc886f%3A0x5c4bbc0aa81edcb9!2zTmhhIFRyYW5nLCBLaMOhbmggSMOyYSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1711208525443!5m2!1svi!2s" width="600" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">

                    <h5><i class="bi bi-telephone-plus-fill"></i> Call us</h5>
                    <a href="tel: +84 937822102" class="d-inline-block mb-2 text-decoration-none text-dark">
                        +84 937822102
                    </a>
                </div>
                <div class="bg-white p-4 rounded mb-4">

                    <h5><i class="bi bi-envelope-at-fill"></i> Email</h5>
                    <a href="email: azureresort@gmail.com" class="d-inline-block mb-2 text-decoration-none text-dark">
                        azureresort@gmail.com
                    </a>
                </div>
                <div class="bg-white p-4 rounded mb-4">
                    <h5><i class="bi bi-facebook"></i> Facebook</h5>
                    <a href="fb: Azure Resort" class="d-inline-block mb-2 text-decoration-none text-dark">
                        Azure Resort
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php
    require('inc/footer.php');
    ?>
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
    <!-- Link JS -->
    <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
    <script>
        window.botpressWebChat.init({
            "composerPlaceholder": "Chat với Azure",
            "botConversationDescription": "By Azure Resort ",
            "botId": "326647f2-a16f-41d2-becb-8edf7c6fdbcd",
            "hostUrl": "https://cdn.botpress.cloud/webchat/v1",
            "messagingUrl": "https://messaging.botpress.cloud",
            "clientId": "326647f2-a16f-41d2-becb-8edf7c6fdbcd",
            "webhookId": "1e23bdf7-da4b-4327-af89-7c3804440b25",
            "lazySocket": true,
            "themeName": "prism",
            "botName": "Azure Bot",
            "stylesheet": "https://webchat-styler-css.botpress.app/prod/b93a3edb-4d8f-48a0-a4fa-d1098729ad48/v27397/style.css",
            "frontendVersion": "v1",
            "useSessionStorage": true,
            "enableConversationDeletion": true,
            "showPoweredBy": true,
            "theme": "prism",
            "themeColor": "#2563eb",
            "allowedOrigins": []
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const successAlert = document.getElementById('signup-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.add('animate__fadeOut');
                    setTimeout(() => successAlert.style.display = 'none', 100);
                }, 1000);
            }

            const failureAlert = document.getElementById('signup-failure');
            if (failureAlert) {
                setTimeout(() => {
                    failureAlert.classList.add('animate__fadeOut');
                    setTimeout(() => failureAlert.style.display = 'none', 100);
                }, 1000);
            }
        });
    </script>
    <script>
        let today = new Date();
        let formattedCheckinDate = today.toISOString().split('T')[0];
        document.getElementById("checkinroom").value = formattedCheckinDate;
        today.setDate(today.getDate() + 2);
        let formattedCheckoutDate = today.toISOString().split('T')[0];
        document.getElementById("checkoutroom").value = formattedCheckoutDate;
    </script>
</body>

</html>