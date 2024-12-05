<?php
session_start();
if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    echo "<script>window.location.href='index.php';</script>";
    exit(); // Thêm exit để dừng thực thi mã PHP sau khi chuyển hướng
}

include_once('inc/database.php');

try {
    // Truy vấn lấy phòng không bị đặt trong khoảng thời gian 28/05/2024 đến 31/05/2024
    $pnks = showTK("SELECT *
    FROM phong
    WHERE soNguoi >=4
    AND id NOT
    IN (
    
    SELECT phongID
    FROM phieudatphong
    WHERE (
    ngayNhanPhong <= '2024-05-31'
    AND ngayTraPhong >= '2024-05-28'
    )
    )
    ");

    // Truy vấn lấy danh sách khách sạn
    $ks = show("SELECT * FROM khachsan");

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
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
                <h1 class="h2">Phòng</h1>
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
                    <form action="inc/add-phong.php" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="khachSan" class="form-label">Khách sạn</label>
                                <select class="form-select" id="khachSan" name="khachSan">
                                    <?php
                                    option_khachsan();
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="soPhong" class="form-label">Số Phòng</label>
                                <input type="text" class="form-control" id="soPhong" name="soPhong" placeholder="Nhập số phòng">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tenPhong" class="form-label">Tên phòng</label>
                                <input type="text" class="form-control" id="tenPhong" name="tenPhong" placeholder="Nhập tên phòng">
                            </div>
                            <div class="col-md-6">
                                <label for="gia" class="form-label">Giá</label>
                                <input type="number" class="form-control" id="gia" name="gia" placeholder="Nhập giá">
                            </div>
                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hangPhong" class="form-label">Hạng phòng</label>
                                <select class="form-select" id="hangPhong" name="hangPhong">
                                    <option selected>Chọn hạng phòng</option>
                                    <option value="1">VIP</option>
                                    <option value="0">Thường</option>
                                    <option value="2">Tổng thống</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="loaiPhong" class="form-label">Loại phòng</label>
                                <select class="form-select" id="loaiPhong" name="loaiPhong">
                                    <option selected>Chọn loại phòng</option>
                                    <option value="0">Đơn</option>
                                    <option value="1">Đôi</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="trangThai" class="form-label">Trạng Thái</label>
                                <select class="form-select" id="trangThai" name="trangThai">
                                    <option selected>Chọn tình trạng</option>
                                    <option value="1">Trống</option>
                                    <option value="0">Hết phòng</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="soNguoi" class="form-label">Số người (max)</label>
                                <input type="number" class="form-control" id="soNguoi" name="soNguoi" placeholder="Nhấp số người">
                            </div>
                            <div class="col-md-4">
                                <label for="dienTich" class="form-label">Diện tích</label>
                                <input type="number" class="form-control" id="dienTich" name="dienTich" placeholder="Nhập diện tích">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hinhAnh" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="hinhAnh" name="hinhAnh" placeholder="Chọn ảnh">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm phòng</button>
                    </form>
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
            <div class="col-md-12 pt-5">
                <!-- Bảng danh sách phòng -->
                <!-- Bảng danh sách phòng -->
    <?php
    $khachSans = array();
    foreach ($pnks as $index => $pnk) {
        $khachSanID = $pnk['khachSanID'];
        if (!array_key_exists($khachSanID, $khachSans)) {
            $khachSans[$khachSanID] = array();
        }
        $khachSans[$khachSanID][] = $pnk;
    }
    ?>

    <?php foreach ($khachSans as $khachSanID => $phongs) { ?>
        <div class="table-responsive" id="collapse1">
            <h2>Khách Sạn: <?php echo ten_khachsan($khachSanID); ?></h2>
            <table class="table table-striped table-sm table-bordered pt-6">
                <thead>
                <tr>
                     
                    <th>Mã phòng</th>
                    <th>Tên phòng</th>
                    <th>Giá</th>
                    <th>Hạng phòng</th>
                    <th>Loại phòng</th>
                    <th>Diện tích</th>
                    <th>Số người</th>
                    <th>Trạng thái</th>
                    <th>Hình ảnh</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($phongs as $pnk) { ?>
                    <tr>
                        
                        <td><?php echo $pnk['soPhong'] ?></td>
                        <td><?php echo $pnk['tenPhong'] ?></td>
                        <td><?php echo $pnk['gia'] ?></td>
                        <td><?php
                            if ($pnk['hangPhong'] == 0)
                                echo "Thường";
                            else if ($pnk['hangPhong'] == 1)
                                echo "VIP";
                            else
                                echo "Tổng thống";
                            ?></td>
                        <td><?php echo $pnk['loaiPhong'] == 1 ? "Đôi" : "Đơn" ?></td>
                        <td><?php echo $pnk['dienTich'] ?></td>
                        <td><?php echo $pnk['soNguoi'] ?></td>
                        <td><?php echo $pnk['trangThai'] == 1 ? "Trống" : "Hết phòng" ?></td>
                        <td><?php echo "<img width=100 height=100 src='../admin/uploads/" . $pnk['hinhAnh'] . "' />"; ?></td>
                        <td>
                            <button class="btn btn-primary updateBtn"
                                    data-id="<?php echo $pnk['id'] ?>"
                                    data-khachSanID="<?php echo $pnk['khachSanID'] ?>"
                                    data-soPhong="<?php echo $pnk['soPhong'] ?>"
                                    data-tenPhong="<?php echo $pnk['tenPhong'] ?>"
                                    data-gia="<?php echo $pnk['gia'] ?>"
                                    data-hangPhong="<?php echo $pnk['hangPhong'] ?>"
                                    data-loaiPhong="<?php echo $pnk['loaiPhong'] ?>"
                                    data-dienTich="<?php echo $pnk['dienTich'] ?>"
                                    data-soNguoi="<?php echo $pnk['soNguoi'] ?>"
                                    data-trangThai="<?php echo $pnk['trangThai'] ?>"
                                    data-hinhAnh="<?php echo $pnk['hinhAnh'] ?>">
                                    <i class="bi bi-pencil-square">Sửa</i></button>


                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-phong.php" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateKhachSanID" class="form-label">Khách sạn ID</label>
                            <input type="text" class="form-control" id="updateKhachSanID" name="soPhong" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateID" class="form-label">Phòng ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="updateSoPhong" class="form-label">Số phòng</label>
                            <input type="text" class="form-control" id="updateSoPhong" name="soPhong">
                        </div>
                        <div class="col-md-4">
                            <label for="updateTenPhong" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="updateTenPhong" name="tenPhong">
                        </div>
                        <div class="col-md-4">
                            <label for="updateGia" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="updateGia" name="gia">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateHangPhong" class="form-label">Hạng phòng</label>
                            <select class="form-select" id="updateHangPhong" name="hangPhong">
                                <option value="1">VIP</option>
                                <option value="0">Thường</option>
                                <option value="2">Tổng thống</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="updateLoaiPhong" class="form-label">Loại phòng</label>
                            <select class="form-select" id="updateLoaiPhong" name="loaiPhong">
                                <option value="0">Đơn</option>
                                <option value="1">Đôi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="updateDienTich" class="form-label">Diện tích</label>
                            <input type="number" class="form-control" id="updateDienTich" name="dienTich">
                        </div>
                        <div class="col-md-4">
                            <label for="updateTrangThai" class="form-label">Trạng thái</label>
                            <select class="form-select" id="updateTrangThai" name="trangThai">
                                <option value="1">Trống</option>
                                <option value="0">Hết phòng</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="updateSoNguoi" class="form-label">Số người</label>
                            <input type="number" class="form-control" id="updateSoNguoi" name="soNguoi">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateHinhAnh" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="updateHinhAnh" name="hinhAnh">
                            <input type="hidden" id="updateHinhAnhCu" name="hinhAnhCu">
                        </div>
                        <div class="col-md-6">
                            <img id="previewUpdateHinhAnh" src="" alt="Preview Image" 
                            style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateUserBtn">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<button id="deletePhong" class="btn btn-danger" data-id="<?php echo $pnk['id']?>"><i class="bi bi-trash-fill"></i>Xóa</i></button>
<button id="chiTietPhong" class="btn btn-warning" data-id="<?php echo $pnk['id']?>"><i class="bi bi-cloud-plus-fill"></i>Ảnh chi tiết</i></button>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
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
                document.getElementById('updateKhachSanID').value = button.getAttribute('data-khachSanID');
                document.getElementById('updateID').value = button.getAttribute('data-id');
                document.getElementById('updateSoPhong').value = button.getAttribute('data-soPhong');
                document.getElementById('updateTenPhong').value = button.getAttribute('data-tenPhong');
                document.getElementById('updateGia').value = button.getAttribute('data-gia');
                document.getElementById('updateHangPhong').value = button.getAttribute('data-hangPhong');
                document.getElementById('updateLoaiPhong').value = button.getAttribute('data-loaiPhong');
                document.getElementById('updateDienTich').value = button.getAttribute('data-dienTich');
                document.getElementById('updateSoNguoi').value = button.getAttribute('data-soNguoi');
                document.getElementById('updateTrangThai').value = button.getAttribute('data-trangThai');
                // document.getElementById('updateHinhAnh').value =button.getAttribute('data-hinhAnh');
                var hinhAnhCu = button.getAttribute('data-hinhAnh');
                document.getElementById('updateHinhAnhCu').value = hinhAnhCu;

                var previewUpdateHinhAnh = document.getElementById('previewUpdateHinhAnh');
                previewUpdateHinhAnh.src = hinhAnhCu ? hinhAnhCu : 'default_image.jpg';
                $('#updateHinhAnh').change(function(){
                    previewImage(this);
                });
                
                function previewImage(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#previewUpdateHinhAnh').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }
                modal.show();
            });
        });
        
        
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        $(document).on('click', '#updateUserBtn', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateID = $('#updateID').val();
            var updateKhachSanID = $('#updateKhachSanID').val();
            var updateSoPhong = $('#updateSoPhong').val();
            var updateTenPhong = $('#updateTenPhong').val();
            var updateGia = $('#updateGia').val();
            var updateHangPhong = $('#updateHangPhong').val();
            var updateLoaiPhong = $('#updateLoaiPhong').val();
            var updateDienTich = $('#updateDienTich').val();
            var updateSoNguoi = $('#updateSoNguoi').val();
            var updateTrangThai = $('#updateTrangThai').val();
            // var updateHinhAnhCu = $('#updateHinhAnhCu').val();
            var updateHinhAnh = $('#updateHinhAnh').prop('files')[0]; // Hình ảnh mới

            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateID);
            formData.append('khachSanID', updateKhachSanID);
            formData.append('soPhong', updateSoPhong);
            formData.append('tenPhong', updateTenPhong);
            formData.append('gia', updateGia);
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
                var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/delete-phong.php', // Đường dẫn tới file PHP xử lý xóa trên server
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
        
        // Xử lý sự kiện click cho nút Chi tiết ảnh
        $(document).on('click', '#chiTietPhong', function() {
            var idPhong = $(this).data('id');
            window.location.href = 'image_phong.php?id_phong=' + idPhong;
        });
        

   

    });
</script>


</body>
</html>
