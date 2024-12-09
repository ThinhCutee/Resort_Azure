<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <?php require('inc/links.php'); ?>
</head>

<body>
    <?php require('inc/header.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <label for="dichvu" class="form-label">Dịch Vụ Cần Tạo QR</label>
                <?php
                include_once('./config/connect.php');
                $sql = "SELECT * FROM dichvu";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    echo '<select name="dichvu" id="dichvu" class="form-select">';
                    while ($rowdv = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $rowdv['id'] . '" class="form-select">' . $rowdv['ten_dich_vu'] . '</option>';
                    }
                    echo '</select>';
                }
                ?>
                <button onclick="generateQRCode()" class="btn btn-primary mt-3 mb-3 w-100">Tạo QR Code</button>
            </div>
            <div id="qrcode" class="d-flex justify-content-center">
                <!-- in qr ở đây -->
            </div>
        </div>
    </div>
    <?php require('inc/footer.php'); ?>
    <script>
        function generateQRCode() {
            var dichvu = document.getElementById("dichvu").value;
            var data = "https://azureresort.id.vn/dichvu.php?id_dich_vu=" + dichvu;
            document.getElementById("qrcode").innerHTML = "";
            new QRCode(document.getElementById("qrcode"), {
                text: data,
                width: 128,
                height: 128
            });
        }
    </script>
</body>

</html>