<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Azure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Links -->
    <?php require('inc/links.php'); ?>
    <style>
    .box {
        border-top-color: var(--teal) !important;
    }
    </style>
</head>

<body>
    <!-- Header -->
    <?php
    require('inc/header.php');
    ?>
    <!-- About-->
    <div class="my-5 px-4">
        <h2 class="mt-5 pt-4 text-center fw-bold">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Không gian sang trọng, dịch vụ cao cấp
            view biển tuyệt đẹp, chúng tôi hứa
            mang đến cho bạn một kỳ nghỉ đáng nhớ.<br>
            Với những món ẩm thực tuyệt vời,
            thư giãn tại spa hoặc tham gia các hoạt động giải trí tại Azure Resort.
        </p>
    </div>
    <!-- ceo FOUNDER -->
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3"> CEO PHÙNG NGUYỄN NHƯ THÙY</h3>
                <p>
                    Đừng bao giờ từ bỏ những điều mà bạn mong muốn. Có thể, có thể không. Dù sao, bạn đã cố gắng.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="./images/about/thuy.png" class="w-100">
            </div>
        </div>
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3"> FOUNDER NGUYỄN ĐỨC THỊNH</h3>
                <p>
                    Thất bại là cơ hội học hỏi. Nếu bạn không từng thất bại, bạn chưa từng thử.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="./images/about/thinh.jpg" class="w-100">
            </div>
        </div>
    </div>
    <!-- about -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">100+ PHÒNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">200+ KHÁCH HÀNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3">100+ ĐÁNH GIÁ</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">200+ NHÂN VIÊN</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- team -->
    <h3 class="my-5 fw-bold text-center">MANAGEMENT TEAM</h3>
    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thuy.png" class="w-50">
                    <h5 class="mt-2">PHÙNG NGUYỄN NHƯ THÙY</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thinh.jpg" class="w-50">
                    <h5 class="mt-2">NGUYỄN ĐỨC THỊNH</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thuy.png" class="w-50">
                    <h5 class="mt-2">PHÙNG NGUYỄN NHƯ THÙY</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thinh.jpg" class="w-50">
                    <h5 class="mt-2">NGUYỄN ĐỨC THỊNH</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thuy.png" class="w-50">
                    <h5 class="mt-2">PHÙNG NGUYỄN NHƯ THÙY</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/thinh.jpg" class="w-50">
                    <h5 class="mt-2">NGUYỄN ĐỨC THỊNH</h5>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <!-- footer -->
    <?php
    require('inc/footer.php');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js">








    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

    <script>
    var swiper = new Swiper(".mySwiper", {

        spaceBetween: 30,



        effect: "fade",
        navigation: {
            nextEl: ".swipper-button-next",
            prevEl: ".swipper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        }
    });
    </script>
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