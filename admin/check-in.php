<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT 
        dvdat.id AS id_dich_vu_dat,
        dv.ten_dich_vu as ten_dich_vu,
        dv.gioi_han as gioi_han,
        kh.sdt AS so_dien_thoai_khach_hang,
        kh.ten as ten_khach_hang,
        dvdat.ngay_dat as ngay_dat,
        dv.don_gia as don_gia,
        dvdat.trang_thai as trang_thai,
        dvdat.so_lan_su_dung AS so_lan_su_dung
    FROM 
        dichvudat dvdat
    JOIN 
        dichvu dv ON dvdat.id_dich_vu = dv.id
    JOIN 
        phongdat pd ON dvdat.id_phong_dat = pd.id
    JOIN 
        khachhang kh ON pd.id_khach_hang = kh.id
    Group by
    dvdat.id ,
        dv.ten_dich_vu ,
        kh.sdt ,
        dvdat.ngay_dat ,
        dv.don_gia ,
        dvdat.trang_thai ,
        dvdat.so_lan_su_dung
    order by  dvdat.id ");
    $dv = show("SELECT * FROM dichvu");
    $ks = show("SELECT * FROM khachsan");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        
    </style>
     <link rel="stylesheet" href="./css/common.css">
     <link rel="stylesheet" href="./css/search.css">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
     <!-- <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <?php require('inc/links.php')?>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php require('inc/admin-navbarDV.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"></h1>
                
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar"></span>
                        This week
                    </button>
                </div>
            </div>
            
            <!-- quản lý -->
            <div class="row">
                <!-- add -->
                <div class="col-md-12">
               
            <h3>
                QRCODE
            </h3>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 mx-auto">
                                <label for="dichvu" class="form-label">Dịch Vụ Cần Tạo QR</label>
                                <?php
                                include_once('../config/connect.php');
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
                            <div id="qrcode" class="d-flex justify-content-center mb-2">
                                <!-- in qr ở đây -->
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12 mt-3"> 
                    <h3>
                        Tìm kiếm dịch vụ check-in
                    </h3>
                    <!-- Form and input fields action="inc/add-goidichvu.php" -->
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mb-3 mt-3">
                            <div class="col-md-6">
                                <label for="soDT" class="form-label">Số điện thoại khách hàng</label>
                                <input type="text" class="form-control" id="soDT" name="soDT" placeholder="Nhập SĐT">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email khách hàng</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="maPhong" class="form-label">Chọn phòng để check-in</label>
                                <select class="form-control" id="maPhong" name="maPhong">
                                    <option value="">Chưa có phòng nào</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="timKiemDVD" class="btn btn-primary">Tìm kiếm</button>
                    </form>
<!--  -->

                    <?php
                    if (isset($_SESSION['response'])) {
                        $response_message = $_SESSION['response']['message'];
                        $is_success = $_SESSION['response']['success'];
                        ?>

                        <div class="responseMessage">
                            <p class="responseMessage <?= $is_success ? 'responseMessage__success' : 'responseMessage__error' ?>">
                            <p class="responseMessageText">
                                <?php echo $response_message ?>
                            </p>
                            </p>
                        </div>
                        <?php unset($_SESSION['response']); } ?>
                </div>
            </div>
            <!-- xem -->
           <!-- Hiển thị bảng dịch vụ -->
           <div class="col-md-12 pt-5">
            <h3>Danh sách các đơn đặt dịch vụ</h3>
                <div class="table-responsive" id="collapse2">
                  
                    <table class="table table-striped table-sm table-bordered pt-6" id="dichVuTable">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">ID</th>
                                <th scope="col" width="15%">Tên dịch vụ</th>
                                <th scope="col" width="5%">Giá</th>
                                <th scope="col" width="8%">Ngày đặt</th>
                                <th scope="col" width="8%">Giới hạn SD</th>
                                <th scope="col" width="8%">Đã sử dụng</th>
                                <th scope="col" width="10%">Trạng thái TT</th>
                                <th scope="col" width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Kết quả sẽ được load bằng Ajax -->
                        </tbody>
                </table>
            </div>
           
        </div>
        </main>
    </div>
</div>
<?php include_once('inc/scripts.php')?>
<!-- <script src="./admin/js/add-room.php"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
<!-- Bootstrap Bundle with Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

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
    // SỬA gói dịch vụ
    document.addEventListener('DOMContentLoaded', function () {

    
    $(document).ready(function () {
            $('#soDT').on('blur', function () {
                var soDT = $(this).val(); // Lấy giá trị số điện thoại từ input

                // Gọi Ajax để kiểm tra số điện thoại
                $.ajax({
                    url: 'inc/check_sdt_email-dvdat.php', // Đường dẫn tới file PHP xử lý
                    method: 'POST',
                    data: { soDT: soDT }, // Gửi số điện thoại tới server
                    success: function (response) {
                        try {
                            var result = JSON.parse(response);

                            if (result.status === 'success') {
                                // Hiển thị email khách hàng
                                $('#email').val(result.email);

                                // Làm sạch danh sách select trước khi thêm dữ liệu mới
                                $('#maPhong').empty();

                                // Thêm tùy chọn phòng vào select
                                if (result.phong && result.phong.length > 0) {
                                    result.phong.forEach(function (phong) {
                                        $('#maPhong').append('<option value="' + phong.id_phongdat + '">' + phong.so_phong + '</option>');
                                        console.log(phong.id_phongdat);
                                        console.log(phong.so_phong);
                                    });
                                } else {
                                    $('#maPhong').append('<option value="">Không có phòng nào</option>');
                                }
                            } else {
                                // Hiển thị lỗi nếu không tìm thấy
                                alert(result.message);
                                $('#soDT').val(''); // Xóa số điện thoại
                                $('#email').val(''); // Xóa email
                                $('#maPhong').empty().append('<option value="">Không có phòng nào</option>');
                            }
                        } catch (e) {
                            console.error('Lỗi khi xử lý phản hồi:', e);
                            alert('Có lỗi xảy ra trong quá trình kiểm tra số điện thoại.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Lỗi:', error);
                        alert('Không thể kết nối đến server.');
                    }
                });
            });
        });


       
    // tìm kiem dich vu dat 
    $(function () {
    $("#meo").click(function () {
        var sdt = $("#soDT").val();
        var gia = $("#gia").val().replace(/\./g, ''); // Loại bỏ dấu chấm khỏi giá
        var email = $("#email").val();
        var trang_thai = $("#trangThai").val();
        var selectedIds = [];

        // Kiểm tra các giá trị trước khi gửi
        console.log("SĐT:", sdt);
        console.log("Email:", email);
        console.log("Trạng thái thanh toán:", trang_thai);
        console.log("Dịch vụ đã chọn:", selectedIds);

        // Lấy các ID dịch vụ từ checkbox đã chọn
        $("#Table1 input[type=checkbox]:checked").each(function () {
            var row = $(this).closest("tr")[0];
            var id = row.cells[1].innerHTML; // Lấy ID dịch vụ
            selectedIds.push(id);
        });

        // Kiểm tra lại mảng selectedIds trước khi gửi
        console.log("Dịch vụ đã chọn sau khi xử lý:", selectedIds);

        $.ajax({
            url: 'inc/add-dichvudat.php',
            type: 'POST',
            data: {
                sdt: sdt,
                gia: gia,
                email: email,
                trang_thai: trang_thai,
                selected_ids: selectedIds
            },
            success: function (response) {
                try {
                    var result = JSON.parse(response);

                    if (result.success) {
                        alert(result.message); // Hiển thị thông báo thành công
                        location.reload(); // Reload trang
                    } else {
                        alert("Lỗi: " + result.message);
                    }
                } catch (e) {
                    console.error("Lỗi khi parse JSON:", e);
                    alert("Có lỗi xảy ra khi xử lý phản hồi từ server.");
                }
            },
            error: function (xhr, status, error) {
                alert("Không thể kết nối đến server. Vui lòng thử lại sau.");
            }
        });

        return false; // Ngăn hành động mặc định
        });
    });


/// Xử lý tìm kiếm
    $(document).ready(function () {
    // Xử lý nút Tìm kiếm
    $('#timKiemDVD').on('click', function () {
        var id_phong_dat = $('#maPhong').val(); // Lấy mã phòng đã chọn
        var soDT = $('#soDT').val();
        if (id_phong_dat === '') {
            alert('Vui lòng chọn phòng!');
            return;
        }

        // Gửi AJAX để lấy danh sách dịch vụ
        $.ajax({
            url: 'inc/get_dichvu_by_phong.php', // File xử lý lấy dịch vụ
            method: 'POST',
            data: { id_phong_dat: id_phong_dat,
             }, // Gửi mã phòng
            success: function (response) {
                try {
                    var result = JSON.parse(response);

                    if (result.status === 'success') {
                        // Làm sạch bảng trước khi thêm dữ liệu mới
                        $('#dichVuTable tbody').empty();

                        // Duyệt danh sách dịch vụ và thêm vào bảng
                        result.dichVu.forEach(function (dichVu) {
                            var trangThaiText = dichVu.trang_thai === 0 ? "Chưa thanh toán" : "Đã thanh toán";
                            $('#dichVuTable tbody').append(`
                                <tr>
                                    <td>${dichVu.id_dich_vu}</td>
                                    <td>${dichVu.ten_dich_vu}</td>
                                    <td>${dichVu.gia}</td>
                                    <td>${dichVu.ngay_dat}</td>
                                    <td>${dichVu.gioi_han}</td>
                                    <td>${dichVu.so_lan_su_dung}</td>
                                    <td>${trangThaiText}</td>
                                    <td>
                                        <button class="btn btn-info btnDelete" data-id="${dichVu.id_dich_vu}">
                                        <i class="bi bi-building-fill-check"> Check-in</i></button>
                                    </td>
                                </tr>
                            `);
                            
                        });
                    } else {
                        // Thông báo lỗi nếu không có dữ liệu
                        alert(result.message);
                        $('#dichVuTable tbody').empty();
                    }
                } catch (e) {
                    console.error('Lỗi khi xử lý phản hồi:', e);
                    alert('Có lỗi xảy ra khi tải danh sách dịch vụ.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Lỗi:', error);
                alert('Không thể kết nối đến server.');
            }
        });
    });

    $(document).on('click', '.btnDelete', function () { // do trong bảng được lấy ra từ js nên phải sử dụng class mới được.
        var id_dich_vu_dat = $(this).data('id'); // Lấy ID dịch vụ từ thuộc tính data-id

        // Gửi yêu cầu AJAX để kiểm tra số lần sử dụng và giới hạn dịch vụ
        $.ajax({
            url: 'inc/checkin-check.php', // Tạo một file mới để kiểm tra và xử lý
            type: 'POST',
            data: {
                id_dich_vu_dat: id_dich_vu_dat // ID của dịch vụ được gửi qua
            },
            success: function (response) {
                var result = JSON.parse(response);

                if (result.success) {
                    if (result.confirm) {
                        // Nếu cần xác nhận, hiển thị hộp thoại xác nhận
                        var userConfirm = confirm(result.message);
                        if (userConfirm) {
                            // Nếu người dùng đồng ý, gửi yêu cầu cập nhật số lần sử dụng
                            updateCheckin(id_dich_vu_dat);
                        }
                    } else {
                        // Nếu không cần xác nhận, thực hiện tự động cập nhật
                        updateCheckin(id_dich_vu_dat);
                    }
                } else {
                    alert(result.message); // Thông báo lỗi nếu không thành công
                }
            },
            error: function (xhr, status, error) {
                alert("Có lỗi xảy ra trong quá trình kiểm tra dịch vụ. Vui lòng thử lại.");
            }
        });
    });

    // Hàm thực hiện update số lần sử dụng
    function updateCheckin(id_dich_vu_dat) {
        $.ajax({
            url: 'inc/update-checkin.php', // Đường dẫn xử lý update số lần sử dụng
            type: 'POST',
            data: {
                id_dich_vu_dat: id_dich_vu_dat
            },
            success: function (updateResult) {
                var updateResponse = JSON.parse(updateResult);
                alert(updateResponse.message); // Thông báo kết quả
                $('#timKiemDVD').trigger('click'); // Reload bảng để hiển thị thay đổi
            },
            error: function (xhr, status, error) {
                alert("Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.");
            }
        });
    }

});




    });
</script>
</body>
</html>
