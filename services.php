<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <!-- Links -->
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transform: all 0.3s;
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
        <h2 class="mt-5 pt-4 text-center fw-bold">SERVICE</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3 text-center">
            Nơi bạn được tận hưởng dịch vụ không giới hạn
            cùng với cảnh biển tuyệt đẹp tại Nha Trang, Việt Nam.
        </p>
    </div>
    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/Dinner2.jpg" width="211px" height="160px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Dinner</h5>
                    </div>
                    <br>
                    <p style="text-align: justify;">
                        Ăn tối trên bãi biển khi hoàng hôn,
                        kỳ nghỉ lãng mạn với thực đơn hải sản tươi ngon
                        và không gian tĩnh lặng của biển.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/wifi.svg" width="125px" height="140px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Wifi</h5>
                    </div>
                    <br>
                    <p style="text-align: justify;">
                        Cung cấp mạng Wi-Fi miễn phí trong toàn bộ khu vực.
                        Đảm bảo khách hàng có thể truy cập Internet mọi lúc
                        mọi nơi trong kỳ nghỉ để giữ liên lạc,
                        làm việc hoặc thưởng thức giải trí trực tuyến.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/gym.png" width="211px" height="120px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Gym</h5>
                    </div>
                    <br>
                    <p style="text-align: justify;">
                        Trang thiết bị hiện đại, đa dạng và tiện nghi.
                        Khách hàng dùng không gian này để duy trì
                        lối sống lành mạnh và rèn luyện sức khỏe
                        trong suốt kỳ nghỉ. Có các huấn luyện viên chuyên nghiệp
                        hướng dẫn và tư vấn hiệu quả.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/spa.jpg" width="211px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Spa</h5>
                    </div>
                    <br>
                    <p style="text-align: justify;">
                        Nơi khách hàng thư giãn và làm mới tinh thần
                        cùng các liệu pháp chăm sóc sức khỏe và làm đẹp.
                        Khách hàng tận hưởng không gian yên tĩnh và thoải mái,
                        sau một ngày dài tham gia các hoạt động khác tại resort.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/breakfast.png" width="211px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Ăn Sáng</h5>
                    </div>
                    <br>
                    <br>
                    <p style="text-align: justify;">
                        Bữa sáng tại Azure Resort là một trải nghiệm đa dạng
                        với đồ uống sảng khoái, trái cây tươi
                        và các món ăn địa phương và quốc tế,
                        giúp khách hàng bắt đầu ngày mới đầy năng lượng.
                    </p>
                    <br>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <div class="d-flex align-items-center mb-2">
                        <img src="./images/dich-vu/tivi.jpg" width="220px" alt="Ăn uống">
                        <h5 class="m-0 ms-3">Tivi</h5>
                    </div>
                    <br>
                    <br>
                    <p style="text-align: justify;">
                        Mang lại giải trí và thoải mái trong không gian riêng tư,
                        cho phép khách hàng thưởng thức chương trình yêu thích của mình.
                    </p>
                </div>
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
    <!-- /Chatra {/literal} -->

</body>

</html>