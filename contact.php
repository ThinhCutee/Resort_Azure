<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
        <h2 class="mt-5 pt-4 text-center fw-bold">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3 text-center">
            Chúng tôi luôn sẵn lòng hỗ trợ bạn với mọi thắc mắc và yêu cầu. </p>
        <p class="text-center mt-3 text-center">
            Đừng ngần ngại liên hệ với chúng tôi bất cứ lúc nào để có thông tin chi tiết về các dịch vụ của Nha Trang Azure hoặc để đặt chỗ.
        </p>
    </div>
    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <iframe class="w-100 rounded mb-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249525.14317069115!2d109.08168324550108!3d12.259756501901176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3170677811cc886f%3A0x5c4bbc0aa81edcb9!2zTmhhIFRyYW5nLCBLaMOhbmggSMOyYSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1711208525443!5m2!1svi!2s" width="600" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <h5><i class="bi bi-geo-alt-fill"></i> Address : </h5>
                    <a href="https://maps.app.goo.gl/du3KvFnLa3dHbfzh9" target="_blank" class="d-inline-block mb-2 text-decoration-none text-dark">

                        113 Trần Phú
                        P.Vĩnh Nguyên,
                        TP.Nha Trang, T.Khánh Hòa, Việt Nam.
                    </a><br>
                    <h5><i class="bi bi-telephone-plus-fill"></i> Call us</h5>
                    <a href="tel: +84 937822102" class="d-inline-block mb-2 text-decoration-none text-dark">
                        +84 937822102
                    </a>
                    <h5><i class="bi bi-envelope-at-fill"></i> Email</h5>
                    <a href="email: azureresort@gmail.com" class="d-inline-block mb-2 text-decoration-none text-dark">
                        azureresort@gmail.com
                    </a>
                    <h5><i class="bi bi-facebook"></i> Facebook</h5>
                    <a href="fb: Azure Resort" class="d-inline-block mb-2 text-decoration-none text-dark">
                        Azure Resort
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white shadow p-4 rounded pop border-top border-5 border-dark">
                    <form action="">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Họ và tên </label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Tiêu đề</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Nội dung</label>
                            <textarea class="form-control shadow-none" rows="1" style="resize: none; height: 223px;"></textarea>
                        </div>
                        <button type="submit" class="btn text-white mt-3 custom-bg">Gửi</button>
                    </form>
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