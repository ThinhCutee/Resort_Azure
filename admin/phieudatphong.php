<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $conn = connect();
    $pnks = show("SELECT * FROM phongdat order by ngay_dat_phong asc");
    $ks = show("SELECT * FROM khachsan");
    $p = show("SELECT * FROM phong");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        #collapse1 {
            overflow-y: scroll;
            height: 500px;
        }
    </style>
     <link rel="stylesheet" href="./css/common.css">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <?php require('inc/links.php')?>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php require('inc/admin-navbar1.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Đặt phòng</h1>
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
                    <form action="inc/add-phieudatphong.php" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ngayNhanPhong" class="form-label">Ngày nhận phòng</label>
                                <input type="date" class="form-control" id="ngayNhanPhong" name="ngayNhanPhong" placeholder="Nhập ngày nhận phòng">
                            </div>
                            <div class="col-md-6">
                                <label for="ngayTraPhong" class="form-label">Ngày trả phòng</label>
                                <input type="date" class="form-control" id="ngayTraPhong" name="ngayTraPhong" placeholder="Nhập ngày trả phòng">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="khachSan" class="form-label">Khách sạn</label>
                                <select class="form-select" id="khachSan" name="khachSan" disabled>
                                    <option value="">Chọn khách sạn</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="soPhong" class="form-label">Số Phòng</label>
                                <select class="form-select" id="soPhong" name="soPhong" disabled>
                                    <option value="">Chọn số phòng</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tenPhong" class="form-label">Tên phòng</label>
                                <input type="text" class="form-control" id="tenPhong" name="tenPhong" placeholder="Tên phòng" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tenGoiDichVu" class="form-label">Tên gói dịch vụ</label>
                                <select class="form-select" id="tenGoiDichVu" name="tenGoiDichVu">
                                    <option value="">Chọn gói dịch vụ</opt>
                                    <?php
                                        option_goidichvu();
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="SoNguoi" class="form-label">Số người</label>
                                <input type="number" class="form-control" id="soNguoi" name="soNguoi" placeholder="Nhập số người" require>
                                <input type="hidden" class="form-control" id="soNguoiToiDa" name="soNguoiToiDa" placeholder="Nhập số người" require>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                    <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
                                    <select class="form-select" id="tenUuDai" name="tenUuDai">
                                    <option value="">Chọn ưu đãi</opt>
                                    <?php
                                        option_uudai();
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="maUuDai" class="form-label">Mã ưu đãi</label>
                                <input type="text" class="form-control" id="maUuDai" name="maUuDai" placeholder="Nhập mã ưu đãi" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="soDT" class="form-label">Số điện thoại khách hàng</label>
                                <input type="text" class="form-control" id="soDT" name="soDT" placeholder="Nhập SĐT">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email khách hàng</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email">
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary">Đặt phòng</button>
                        <button id="btnCheckout" type="submit" class="btn btn-warning">Check-out</button>
                    </form>
                    <?php
                    if (isset($_SESSION['response'])) {
                        $response_message = $_SESSION['response']['message'];
                        $is_success = $_SESSION['response']['success'];
                        ?>

                        <div class="responseMessage text-center text-danger ">
                            <p class="responseMessage <?= $is_success ? 'responseMessage__success ' : 'responseMessage__error' ?>">
                            <p class="responseMessageText">
                                <?php echo $response_message ?>
                            </p>
                            </p>
                        </div>
                        <?php unset($_SESSION['response']); } ?>
                </div>
            </div>
            <!-- xem -->
            <div class="col-md-12 pt-5">
                <!-- Bảng danh sách phòng -->
                <!-- Bảng danh sách phòng -->
                <?php
// Include file chứa các hàm và kết nối CSDL

// Lấy danh sách các phiếu đặt phòng theo từng khách sạn

$pnks = show("SELECT *
 FROM phongdat where trang_thai not like '3' ");

?>


    <div class="table-responsive" id="collapse1">
        
        <table class="table table-striped table-sm table-bordered pt-6">
            <thead>
            <tr>
                <th>Mã phiếu</th>
                <th>Mã ưu đãi</th>
                <th>Mã phòng</th>
                <th>Tên gói dịch vụ</th>
                <th>Số ĐT</th>
                <th>Số người</th>
                <th>Ngày đặt phòng</th>
                <th>Ngày nhận phòng</th>
                <th>Ngày trả phòng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pnks as $pnk) { ?>
                    <tr>
                        
                        <td><?php echo $pnk['id']; ?></td>
                        <td><?php echo $pnk['id_uu_dai']; ?></td>
                        <td><?php
                            // Lấy tên gói dịch vụ từ bảng goidichvu
                            $query = "SELECT so_phong FROM phong WHERE id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->execute(array($pnk['id_phong']));
                            $maPhong = $stmt->fetchColumn();
                            echo $maPhong;
                        ?></td>
                        <td><?php
                            // Lấy tên gói dịch vụ từ bảng goidichvu
                            $query = "SELECT ten_goi_dich_vu FROM goidichvu WHERE id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->execute(array($pnk['id_goi_dich_vu']));
                            $tenGoiDichVu = $stmt->fetchColumn();
                            echo $tenGoiDichVu;
                        ?></td>
                        <td>
                            <?php
                            // Lấy Số ĐT từ bảng khachhang
                            $query = "SELECT sdt FROM khachhang WHERE id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->execute(array($pnk['id_khach_hang']));
                            $soDT = $stmt->fetchColumn();
                            echo $soDT;
                            ?>
                        </td>

                        <td><?php echo $pnk['so_nguoi']; ?></td>
                        <td><?php echo $pnk['ngay_dat_phong']; ?></td>
                        <td><?php echo $pnk['ngay_nhan_phong']; ?></td>
                        <td><?php echo $pnk['ngay_tra_phong']; ?></td>
                        <td><?php echo number_format($pnk['tong_tien'], 0, '.', '.'); ?></td>
                        <td><?php if ($pnk['trang_thai']==0)
                                    echo "Chờ xử lý"; 
                                elseif($pnk['trang_thai']==1)
                                    echo "Hoàn thành";
                                else echo "Đã hủy";
                                ?></td>
                        <td>
                            
<button id="deletePhong" class="btn btn-danger" data-id="<?php echo $pnk['id']?>"><i class="bi bi-trash-fill"></i>Hủy</i></button>

                                </td>
                            </tr>
                        <?php } ?>
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
<script>
    
    // SỬA PHÒNG
    document.addEventListener('DOMContentLoaded', function () {
        var updateButtons = document.querySelectorAll('.updateBtn');
        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('myModal'), {});
                var hoaDonID = button.getAttribute('data-idHoaDon');
                var phieuDatPhongID = button.getAttribute('data-idPhieu');
                var tongTien = button.getAttribute('data-tongTien');
                var trangThai = button.getAttribute('data-trangThai');

                document.getElementById('updateHoaDonID').value = hoaDonID;
                document.getElementById('updatePhieuDatPhongID').value = phieuDatPhongID;
                document.getElementById('updateTongTien').value = tongTien;
                document.getElementById('updateTrangThai').value = trangThai;


                modal.show();
            });
        });
        // Hàm định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }
        
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        $(document).on('click', '#updateUserBtn', function() {
            // Lấy dữ liệu từ các input trong modal
            var updatePhieuDatPhongID = $('#updatePhieuDatPhongID').val();
            var updateHoaDonID = $('#updateHoaDonID').val();
            var updateSoPhong = $('#updateSoPhong').val();
            var updateTenPhong = $('#updateTenPhong').val();
            var updatetenGoiDichVu = $('#updatetenGoiDichVu').val();
            var updateHangPhong = $('#updateHangPhong').val();
            var updateLoaiPhong = $('#updateLoaiPhong').val();
            var updateDienTich = $('#updateDienTich').val();
            var updateSoNguoi = $('#updateSoNguoi').val();
            var updateTrangThai = $('#updateTrangThai').val();
            // var updateHinhAnhCu = $('#updateHinhAnhCu').val();
            var updateHinhAnh = $('#updateHinhAnh').prop('files')[0]; // Hình ảnh mới

            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updatePhieuDatPhongID);
            formData.append('khachSanID', updateHoaDonID);
            formData.append('soPhong', updateSoPhong);
            formData.append('tenPhong', updateTenPhong);
            formData.append('tenGoiDichVu', updatetenGoiDichVu);
            formData.append('hangPhong', updateHangPhong);
            formData.append('loaiPhong', updateLoaiPhong);
            formData.append('dienTich', updateDienTich);
            formData.append('soNguoi', updateSoNguoi);
            formData.append('trangThai', updateTrangThai);
            // formData.append('hinhAnhCu', updateHinhAnhCu);
            formData.append('hinhAnh', updateHinhAnh); // Đặt hình ảnh mới vào formData

            // Gửi request Ajax
            $.ajax({
                url: 'inc/update-phong.php',
                method: 'POST',
                data: formData,
                processData: false, // Không xử lý dữ liệu (formData) thành chuỗi query
                contentType: false, // Không thiết lập header 'Content-Type'
                success: function(data) {
                    // Xử lý phản hồi từ server
                    alert(data.message);
                    if (data.status === 'success') {
                        // Đóng modal sau khi cập nhật thành công
                        var modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                        modal.hide();
                        // Reload trang để cập nhật dữ liệu mới
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu.');
                }
            });
        });
        
        //lắng nghe sự kiện cho nút XÓA PHÒNG
        $(document).on('click', '#deletePhong', function(e) {
                e.preventDefault();
                // Lấy mã kho từ thuộc tính data
                var id = $(this).data('id');
                // console.log(maKho)
                // Kiểm tra nếu người dùng chắc chắn muốn xóa
                var isConfirmed = confirm('Bạn có chắc chắn muốn hủy?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/update-phieudatphong.php', // Đường dẫn tới file PHP xử lý xóa trên server
                        method: 'POST',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                            // Xử lý phản hồi từ server
                            alert(response.message);
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu.');
                            console.error('Error:', error);
                        }
                    });
                }
            });

        // xử lý tạo phiếu đặt phòng
        document.getElementById('ngayNhanPhong').addEventListener('change', updateKhachSanOptions);
        document.getElementById('ngayTraPhong').addEventListener('change', updateKhachSanOptions);

        function updateKhachSanOptions() {
            var ngayNhanPhong = document.getElementById('ngayNhanPhong').value;
            var ngayTraPhong = document.getElementById('ngayTraPhong').value;
            var today = new Date().toISOString().split('T')[0];

            // Kiểm tra ngày nhận phòng phải sau ngày hiện tại
            if (ngayNhanPhong <= today) {
                alert('Ngày nhận phòng phải sau ngày hiện tại.');
                document.getElementById('ngayNhanPhong').value = '';
                return;
            }

            // Kiểm tra ngày trả phòng phải sau ngày nhận phòng
            if (ngayTraPhong && ngayTraPhong <= ngayNhanPhong) {
                alert('Ngày trả phòng phải sau ngày nhận phòng.');
                document.getElementById('ngayTraPhong').value = '';
                return;
            }

            if (ngayNhanPhong && ngayTraPhong) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'inc/get_khachsan.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        var khachSanSelect = document.getElementById('khachSan');
                        khachSanSelect.innerHTML = '<option value="">Chọn khách sạn</option>';
                        response.khachsan.forEach(function (khachsan) {
                            khachSanSelect.innerHTML += '<option value="' + khachsan.id + '">' + khachsan.ten_khach_san + '</option>';
                        });

                        khachSanSelect.disabled = false;
                        document.getElementById('soPhong').innerHTML = '<option value="">Chọn số phòng</option>';
                        document.getElementById('soPhong').disabled = true;
                        document.getElementById('tenPhong').value = '';
                    }
                };

                xhr.send('ngayNhanPhong=' + ngayNhanPhong + '&ngayTraPhong=' + ngayTraPhong);
            }
        }

        document.getElementById('khachSan').addEventListener('change', function () {
            var khachSanId = this.value;
            var ngayNhanPhong = document.getElementById('ngayNhanPhong').value;
            var ngayTraPhong = document.getElementById('ngayTraPhong').value;

            if (khachSanId && ngayNhanPhong && ngayTraPhong) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'inc/get_phong.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        var soPhongSelect = document.getElementById('soPhong');
                        soPhongSelect.innerHTML = '<option value="">Chọn số phòng</option>';
                        response.phong.forEach(function (phong) {
                            soPhongSelect.innerHTML += '<option value="' + phong.id + '">' + phong.so_phong + '</option>';
                        });

                        soPhongSelect.disabled = false;
                        document.getElementById('tenPhong').value = '';
                    }
                };

                xhr.send('khachSanId=' + khachSanId + '&ngayNhanPhong=' + ngayNhanPhong + '&ngayTraPhong=' + ngayTraPhong);
            }
        });

        document.getElementById('soPhong').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var soPhong = selectedOption.value;

            // Gửi yêu cầu Ajax để lấy tên phòng tương ứng với số phòng đã chọn
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'inc/get_tenphong.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var tenPhong = xhr.responseText;

                    // Cập nhật giá trị của input tenPhong
                    document.getElementById('tenPhong').value = tenPhong;
                }
            };

            // Gửi số phòng đã chọn đến file PHP để xử lý
            xhr.send('soPhong=' + soPhong);
        });

        // ƯU ĐÃI 
        document.getElementById('tenUuDai').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var maUuDai = selectedOption.value;

            // Cập nhật giá trị của input maUuDai
            document.getElementById('maUuDai').value = maUuDai;
        });
    
    //CHECK SỐ NGƯỜI NHẬP VÀO 
        document.getElementById('soPhong').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var soPhong = selectedOption.value;

            // Gửi yêu cầu Ajax để lấy tên phòng tương ứng với số phòng đã chọn
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'inc/get_songuoi.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var soNguoiToiDa = xhr.responseText;

                    // Cập nhật giá trị của input tenPhong
                    document.getElementById('soNguoiToiDa').value = soNguoiToiDa;
                }
            };

            // Gửi số phòng đã chọn đến file PHP để xử lý
            xhr.send('soPhong=' + soPhong);
        });


    // Kiểm tra số người nhập vào
    document.getElementById('soNguoi').addEventListener('change', function () {
        var soNguoi = parseInt(this.value);
        var soNguoiToiDa = parseInt(document.getElementById('soNguoiToiDa').value);

        // Kiểm tra nếu số người là số âm hoặc bằng 0
        if (soNguoi <= 0 || isNaN(soNguoi)) {
            alert('Số người phải lớn hơn 0.');
            // Đặt lại giá trị của trường số người thành 1
            this.value = '';
        } else if (soNguoi > soNguoiToiDa) {
            alert('Số người nhập vào phải nhỏ hơn hoặc bằng ' + soNguoiToiDa);
            // Đặt lại giá trị của trường số người
            this.value = '';
        }
        // Kiểm tra nếu số người lớn hơn số người tối đa trong phòng
        else if (soNguoi > soNguoiToiDa) {
            alert('Số người nhập vào phải nhỏ hơn hoặc bằng số người tối đa trong phòng.');
            // Đặt lại giá trị của trường số người thành số người tối đa trong phòng
            this.value = soNguoiToiDa;
        }
    });
    //CHECK SỐ ĐIỆN THOẠI TỒN TẠI 
    document.getElementById('soDT').addEventListener('blur', function () {
        var soDT = this.value;

        // Thực hiện một truy vấn Ajax để kiểm tra số điện thoại trong cơ sở dữ liệu
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'inc/check_sdt_email.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText;

                // Nếu số điện thoại tồn tại trong cơ sở dữ liệu, cập nhật giá trị của trường nhập email
                if (response !== '') {
                    document.getElementById('email').value = response;
                }
                else 
                {
                    alert('Vui lòng tạo thông tin khách hàng trước !');
                    // set về trống
                    this.value = '';
                }
            }
        };

        // Gửi số điện thoại đã nhập đến file PHP để kiểm tra
        xhr.send('soDT=' + soDT);
    });

    // Xử lý sự kiện click cho nút check-out
    $(document).on('click', '#btnCheckout', function() {
                var id_goi_dich_vu = $(this).data('id');
                window.location.href = 'nhanvien.php';
            });



   

    });
</script>


</body>
</html>
