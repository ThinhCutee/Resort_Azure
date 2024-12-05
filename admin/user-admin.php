<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT * FROM admin join nhanvien on admin.id = nhanvien.id_admin");
    $nv = show("SELECT * FROM nhanvien");
    $ks = show("SELECT * FROM khachsan");
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
                <h1 class="h2">Quản lý tài khoản admin</h1>
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
                    <form action="inc/add-user-admin.php" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tenAdmin" class="form-label">Tên tài khoản Admin</label>
                                <input type="text" class="form-control" id="tenAdmin" name="tenAdmin" placeholder="Nhập tên tài khoản admin">
                            </div>
                            <div class="col-md-4">
                                <label for="matKhau" class="form-label">Mật khẩu</label>
                                <input type="text" class="form-control" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu">
                            </div>
                            <div class="col-md-4">
                                <label for="trangThai" class="form-label">Trạng Thái</label>
                                <select class="form-select" id="trangThai" name="trangThai">
                                    <option selected>Chọn tình trạng</option>
                                    <option value="0">Dừng hoạt động</option>
                                    <option value="1">Đang hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <h4>Thông tin nhân viên</h4>
                        <div class="row mb-3 pt-3">
                            <div class="col-md-6">
                                <label for="hoTen" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="hoTen" name="hoTen" placeholder="Nhập họ tên">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="diaChi" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="diaChi" name="diaChi" placeholder="Nhập địa chỉ"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="phongBan" class="form-label">Phòng ban</label>
                                <select class="form-select" id="phongBan" name="phongBan">
                                    <option value="AD">Admin</option>
                                    <option value="LT">Lễ tân</option>
                                    <option value="DV-Spa">Dịch vụ Spa</option>
                                    <option value="DV-NhaHang">Dịch vụ Nhà hàng</option>
                                    <option value="DV-Gym">Dịch vụ Gym</option>
                                    <option value="DV-Golf">Dịch vụ Golf</option>
                                    <option value="DV-HoBoi">Dịch vụ Hồ bơi</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm tài khoản nhân viên</button>
                        <a id="DSNhanVien" class="btn btn-warning">Danh sách nhân viên</a>
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
                
            <div class="table-responsive" id="collapse1">
                <h2>Danh sách tài khoản admin</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th>Mã admin</th>
                            <th>Tên admin</th>
                            <th>Mật khẩu</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id_admin'] ?></td>
                        <td><?php echo $pnk['admin_name'] ?></td>
                        <td><?php echo $pnk['admin_pass'] ?></td>
                        <td><?php
                            if ($pnk['trang_thai'] == 0)
                                echo "Dừng hoạt động";
                            else if ($pnk['trang_thai'] == 1)
                                echo "Đang hoạt động";
                        ?></td>
                        <td class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-primary updateBtn  m-2"
                                    data-id="<?php echo $pnk['id_admin'] ?>"
                                    data-trangThai="<?php echo $pnk['trang_thai'] ?>"
                                    data-tenAdmin="<?php echo $pnk['admin_name'] ?>"
                                    data-matKhau="<?php echo $pnk['admin_pass'] ?>">
                                    <i class="bi bi-pencil-square"> Sửa</i></button>


                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        
                        <div class="col-md-6">
                            <label for="updateID" class="form-label">Admin ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateTenAdmin" class="form-label">Tên admin</label>
                            <input type="text" class="form-control" id="updateTenAdmin" name="tenAdmin" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateMatKhau" class="form-label">Mật khẩu</label>
                            <input type="text" class="form-control" id="updateMatKhau" name="matKhau" required>
                        </div>
                        <div class="col-md-6">
                            <label for="updateTrangThai" class="form-label">Trạng thái</label>
                            <select class="form-select" id="updateTrangThai" name="trangThai">
                                <option value="1">Đang hoạt động</option>
                                <option value="0">Dừng hoạt động</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="updateUserBtn">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<button id="deleteBtn" class="btn btn-danger m-2" data-id="<?php echo $pnk['id_admin']?>"><i class="bi bi-trash-fill"></i> Xóa</i></button>
<button id="chiTietNhanVien" class="btn btn-warning m-2 chiTietNhanVien" 
data-id="<?php echo $pnk['id_admin']?>"
data-idNV="<?php echo $pnk['id']?>"
data-hoTen="<?php echo $pnk['ho_ten']?>"
data-email="<?php echo $pnk['email']?>"
data-diaChi="<?php echo $pnk['dia_chi']?>"
data-phongBan="<?php echo $pnk['phong_ban']?>"
>
    
<i class="bi bi-ticket-detailed-fill"></i> Chi tiết nhân viên</i></button>
 <!-- Modal Bootstrap -->
 <div class="modal fade" id="myModalNV" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        
                        <div class="col-md-6">
                            <label for="updateNV" class="form-label">ID nhân viên</label>
                            <input type="text" class="form-control" id="updateNV" name="updateNV" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateHoTen" class="form-label">Tên nhân viên</label>
                            <input type="text" class="form-control" id="updateHoTen" name="updateHoTen" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="updateEmail" name="updateEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label for="updatePhongBan" class="form-label">Phòng ban</label>
                            <select class="form-select" id="updatePhongBan" name="updatePhongBan">
                                <option value="AD">Admin</option>
                                <option value="LT">Lễ tân</option>
                                <option value="DV-Spa">Dịch vụ Spa</option>
                                <option value="DV-NhaHang">Dịch vụ Nhà hàng</option>
                                <option value="DV-Gym">Dịch vụ Gym</option>
                                <option value="DV-Golf">Dịch vụ Golf</option>
                                <option value="DV-HoBoi">Dịch vụ Hồ bơi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="updateDiaChi" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="updateDiaChi" name="updateDiaChi" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="updateUserBtnNV">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
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
    
    // SỬA gói dịch vụ
    document.addEventListener('DOMContentLoaded', function () {
        var updateButtons = document.querySelectorAll('.updateBtn');
        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('myModal'), {});
                document.getElementById('updateID').value = button.getAttribute('data-id');
                document.getElementById('updateTrangThai').value = button.getAttribute('data-trangThai');
                document.getElementById('updateTenAdmin').value = button.getAttribute('data-tenAdmin');
                document.getElementById('updateMatKhau').value = button.getAttribute('data-matKhau');
                
                modal.show();
            });
        });
        
        
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        $(document).on('click', '#updateUserBtn', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateID = $('#updateID').val();
            var updatetrangThai = $('#updateTrangThai').val();
            var updatetenAdmin = $('#updateTenAdmin').val();
            var updatematKhau = $('#updateMatKhau').val();

            // Kiểm tra các giá trị đầu vào trước khi gửi yêu cầu Ajax
            if (!updateID || !updatetrangThai || !updatetenAdmin || !updatematKhau) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }
            else{
                 // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateID);
            formData.append('trangThai', updatetrangThai);
            formData.append('admin_name', updatetenAdmin);
            formData.append('admin_pass', updatematKhau);

                // Gửi request Ajax
                $.ajax({
                    url: 'inc/update-user-admin.php',
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
            }
        });


        
        //lắng nghe sự kiện cho nút XÓA gói dịch vụ
        $(document).on('click', '#deleteBtn', function(e) {
                e.preventDefault();
                // Lấy mã kho từ thuộc tính data
                var id = $(this).data('id');
                // console.log(maKho)
                // Kiểm tra nếu người dùng chắc chắn muốn xóa
                var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/delete-user-admin.php', // Đường dẫn tới file PHP xử lý xóa trên server
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

        // chi tiết nhân viên update
        var updateButtons = document.querySelectorAll('.chiTietNhanVien');
        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('myModalNV'), {});
                document.getElementById('updateNV').value = button.getAttribute('data-idNV');
                document.getElementById('updateHoTen').value = button.getAttribute('data-hoTen');
                document.getElementById('updateEmail').value = button.getAttribute('data-email');
                document.getElementById('updateDiaChi').value = button.getAttribute('data-diaChi');
                document.getElementById('updatePhongBan').value = button.getAttribute('data-phongBan');
                
                modal.show();
            });
        });
        // Lắng nghe sự kiện click cho nút "Cập Nhật Nhân viên"
        $(document).on('click', '#updateUserBtnNV', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateNV = $('#updateNV').val();
            var updateHoTen = $('#updateHoTen').val();
            var updateDiaChi = $('#updateDiaChi').val();
            var updateEmail = $('#updateEmail').val();
            var updatePhongBan = $('#updatePhongBan').val();

            // Kiểm tra các giá trị đầu vào trước khi gửi yêu cầu Ajax
            if (!updateNV || !updateHoTen || !updateDiaChi || !updatePhongBan || !updateEmail) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }
            else{
                 // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateNV);
            formData.append('ho_ten', updateHoTen);
            formData.append('dia_chi', updateDiaChi);
            formData.append('email', updateEmail);
            formData.append('phong_ban', updatePhongBan);

                // Gửi request Ajax
                $.ajax({
                    url: 'inc/update-nhanvien.php',
                    method: 'POST',
                    data: formData,
                    processData: false, // Không xử lý dữ liệu (formData) thành chuỗi query
                    contentType: false, // Không thiết lập header 'Content-Type'
                    success: function(data) {
                        // Xử lý phản hồi từ server
                        alert(data.message);
                        if (data.status === 'success') {
                            // Đóng modal sau khi cập nhật thành công
                            var modal = bootstrap.Modal.getInstance(document.getElementById('myModalNV'));
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
            }
        });



    // Xử lý sự kiện click cho nút Chi tiết dịch vụ
        $(document).on('click', '#DSNhanVien', function() {
                var id_goi_dich_vu = $(this).data('id');
                window.location.href = 'nhanvien.php';
            });

    });
    
</script>


</body>
</html>
