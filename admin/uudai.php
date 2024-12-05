<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT * FROM uudai");
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
        .responseMessage {
            margin: 10px ;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        .responseMessage__success {
            background-color: crimson;
            color: #000;
            border: 1px solid #c3e6cb;
        }

        .responseMessage__error {
            background-color: darkred;
            color: #000;
            border: 1px solid #f5c6cb;
        }

        .responseMessageText {
            margin: 0;
            padding: 0;
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
                <h1 class="h2">Ưu đãi</h1>
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
                    <form action="inc/add-uudai.php" method="post" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                    <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
                                    <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" placeholder="Nhập tên ưu đãi">
                            </div>
                            <div class="col-md-6">
                                <label for="giaGiam" class="form-label">Giá giảm (%)</label>
                                <input type="number" class="form-control" id="giaGiam" name="giaGiam" placeholder="Nhập giá giảm %">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ngayBatDau" class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" id="ngayBatDau" name="ngayBatDau" placeholder="Nhập ngày bắt đầu ưu đãi">
                            </div>
                            <div class="col-md-6">
                                <label for="ngayKetThuc" class="form-label">Ngày kết thúc<u></u></label>
                                <input type="date" class="form-control" id="ngayKetThuc" name="ngayKetThuc" placeholder="Nhập ngày kết thúc dịch vụ">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                    <label for="moTa" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="moTa" name="moTa" placeholder="Nhập mô tả"></textarea>
                            </div>
                            
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Thêm ưu đãi</button>
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
                <h2>Danh sách các dịch vụ</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th>Mã ưu đãi</th>
                            <th>Tên ưu đãi</th>
                            <th>Giá giảm (%)</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Mô tả</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id'] ?></td>
                        <td><?php echo $pnk['ten_uu_dai'] ?></td>
                        <td><?php echo $pnk['gia_giam'] ?></td>
                        <td><?php echo $pnk['ngay_bat_dau'] ?></td>
                        <td><?php echo $pnk['ngay_ket_thuc'] ?></td>
                        <td><?php echo $pnk['mo_ta'] ?></td>
                        <td>
                            <button class="btn btn-primary updateBtn"
                                    data-id="<?php echo $pnk['id'] ?>"
                                    data-tenUuDai="<?php echo $pnk['ten_uu_dai'] ?>"
                                    data-moTa="<?php echo $pnk['mo_ta'] ?>"
                                    data-ngayBatDau="<?php echo $pnk['ngay_bat_dau'] ?>"
                                    data-ngayKetThuc="<?php echo $pnk['ngay_ket_thuc'] ?>"
                                    data-giaGiam="<?php echo $pnk['gia_giam'] ?>">
                                <i class="fa fa-pencil">Sửa</i></button>


                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin ưu đãi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-uudai.php" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        
                        <div class="col-md-6">
                            <label for="updateID" class="form-label">ưu đãi ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateTenUuDai" class="form-label">Tên ưu đãi</label>
                            <input type="text" class="form-control" id="updateTenUuDai" name="tenUuDai">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateNgayBatDau" class="form-label">Ngày bắt đầu</label>
                            <input type="date" class="form-control" id="updateNgayBatDau" name="ngayBatDau">
                        </div>
                        <div class="col-md-6">
                            <label for="updateNgayKetThuc" class="form-label">Mô tả</label>
                            <input type="date" class="form-control" id="updateNgayKetThuc" name="ngayKetThuc">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateGiaGiam" class="form-label">Giá giảm %</label>
                            <input type="number" class="form-control" id="updateGiaGiam" name="giaGiam">
                        </div>
                        <div class="col-md-6">
                            <label for="updateMoTaoTa" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="updateMoTa" name="moTa"></textarea>
                            <!-- <input type="text" class="form-control" id="updateMoTa" name="moTa"> -->
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="updateUserBtn">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<button id="deleteBtn" class="btn btn-danger" data-id="<?php echo $pnk['id']?>"><i class="fa fa-trash">Xóa</i></button>
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
                document.getElementById('updateTenUuDai').value = button.getAttribute('data-tenUuDai');
                document.getElementById('updateMoTa').value = button.getAttribute('data-moTa');
                document.getElementById('updateNgayBatDau').value = button.getAttribute('data-ngayBatDau');
                document.getElementById('updateNgayKetThuc').value = button.getAttribute('data-ngayKetThuc');
                document.getElementById('updateGiaGiam').value = button.getAttribute('data-giaGiam');
                
                modal.show();
            });
        });
        
        
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        $(document).on('click', '#updateUserBtn', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateID = $('#updateID').val();
            var updateTenUuDai = $('#updateTenUuDai').val();
            var updateMoTa = $('#updateMoTa').val();
            var updateNgayBatDau = $('#updateNgayBatDau').val();
            var updateNgayKetThuc = $('#updateNgayKetThuc').val();
            var updateGiaGiam = $('#updateGiaGiam').val();
            

            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateID);
            formData.append('tenUuDai', updateTenUuDai);
            formData.append('moTa', updateMoTa);
            formData.append('ngayBatDau', updateNgayBatDau);
            formData.append('ngayKetThuc', updateNgayKetThuc);
            formData.append('giaGiam', updateGiaGiam);

            // Gửi request Ajax
            $.ajax({
                url: 'inc/update-uudai.php',
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
                        url: 'inc/delete-uudai.php', // Đường dẫn tới file PHP xử lý xóa trên server
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

        //check giá giảm <=100%
        // Số người phải lớn hơn 0
        document.getElementById('giaGiam').addEventListener('change', function () {
            var giaGiam = parseInt(this.value);

            // Kiểm tra nếu giá giảm lớn hơn 0 hoặc âm
            if (giaGiam > 100 || giaGiam <=0 || isNaN(giaGiam) ) {
                alert('Giá giảm phải < 100 và phải >=0 .');
                // Đặt lại giá trị của trường số người thành 1
                this.value = '';
            }
        });
        // kiểm tra ngày 
        document.getElementById('ngayBatDau').addEventListener('change', function () {
            var ngayBatDau = document.getElementById('ngayBatDau').value;
            var today = new Date().toISOString().split('T')[0];
            
            // Kiểm tra ngày bắt đầu phải sau ngày hiện tại
            if (ngayBatDau <= today) {
                alert('Ngày bắt đầu phải sau ngày hiện tại.');
                document.getElementById('ngayBatDau').value = '';
                return;
            }
        });

        document.getElementById('ngayKetThuc').addEventListener('change', function () {
            var ngayBatDau = document.getElementById('ngayBatDau').value;
            var ngayKetThuc = document.getElementById('ngayKetThuc').value;

            // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
            if (ngayKetThuc && ngayKetThuc <= ngayBatDau) {
                alert('Ngày kết thúc phải sau ngày bắt đầu.');
                document.getElementById('ngayKetThuc').value = '';
                return;
            }
        });

        //KIỂM TRA MODAL 
        // Lắng nghe sự kiện change cho trường ngày bắt đầu
        document.getElementById('updateNgayBatDau').addEventListener('change', function () {
            var updateNgayBatDau = this.value;
            var updateNgayKetThuc = document.getElementById('updateNgayKetThuc').value;
            var updateGiaGiam = document.getElementById('updateGiaGiam').value;
            var today = new Date().toISOString().split('T')[0];

            // Kiểm tra ngày bắt đầu phải sau ngày hôm nay
            if (updateNgayBatDau <= today) {
                alert('Ngày bắt đầu phải sau ngày hiện tại.');
                this.value = ''; // Xóa giá trị không hợp lệ
                return;
            }

            // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
            if (updateNgayKetThuc <= updateNgayBatDau) {
                alert('Ngày kết thúc phải sau ngày bắt đầu.');
                document.getElementById('updateNgayKetThuc').value = ''; // Xóa giá trị không hợp lệ
                return;
            }
        });

        // Lắng nghe sự kiện change cho trường ngày kết thúc
        document.getElementById('updateNgayKetThuc').addEventListener('change', function () {
            var updateNgayKetThuc = this.value;
            var updateNgayBatDau = document.getElementById('updateNgayBatDau').value;
            var updateGiaGiam = document.getElementById('updateGiaGiam').value;

            // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
            if (updateNgayKetThuc <= updateNgayBatDau) {
                alert('Ngày kết thúc phải sau ngày bắt đầu.');
                this.value = ''; // Xóa giá trị không hợp lệ
                return;
            }
        });

        // Lắng nghe sự kiện change cho trường giá giảm
        document.getElementById('updateGiaGiam').addEventListener('change', function () {
            var updateGiaGiam = this.value;

            // Kiểm tra giá giảm phải lớn hơn 0 và nhỏ hơn hoặc bằng 100
            if (updateGiaGiam <= 0 || updateGiaGiam > 100) {
                alert('Giá giảm phải lớn hơn 0 và nhỏ hơn hoặc bằng 100.');
                this.value = ''; // Xóa giá trị không hợp lệ
                return;
            }
        });

   

    });
</script>


</body>
</html>
