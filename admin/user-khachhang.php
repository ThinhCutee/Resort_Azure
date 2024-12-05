<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT * FROM khachhang");
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
                <h1 class="h2">Quản lý khách hàng</h1>
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
                    <form action="inc/add-user-khachhang.php" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ho" class="form-label">Họ: </label>
                                <input type="text" class="form-control" id="ho" name="ho" placeholder="Nhập họ">
                            </div>
                            <div class="col-md-6">
                                <label for="ten" class="form-label">Tên: </label>
                                <input type="text" class="form-control" id="ten" name="ten" placeholder="Nhập tên">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                    <label for="soDT" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="soDT" name="soDT" placeholder="Nhập SĐT">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email">
                                </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                    <label for="diaChi" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" id="diaChi" name="diaChi" placeholder="Nhập Địa chỉ"></textarea>    
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
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
                
            <div class="table-responsive" id="collapse1">
                <h2>Danh sách tài khoản admin</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th scope="col" width="3%">ID</th>
                            <th scope="col" width="10%">Họ</th>
                            <th scope="col" width="3%">Tên</th>
                            <th scope="col" width="5%">Số điện thoại</th>
                            <th scope="col" width="10%">Email</th>
                            <th scope="col" width="10%">Địa chỉ</th>
                            <th scope="col" width="15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id'] ?></td>
                        <td><?php echo $pnk['ho'] ?></td>
                        <td><?php echo $pnk['ten'] ?></td>
                        <td><?php echo $pnk['sdt'] ?></td>
                        <td><?php echo $pnk['email'] ?></td>
                        <td><?php echo $pnk['dia_chi'] ?></td>
                        <td class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-primary updateBtn m-2"
                                    data-id="<?php echo $pnk['id'] ?>"
                                    data-ho="<?php echo $pnk['ho'] ?>"
                                    data-ten="<?php echo $pnk['ten'] ?>"
                                    data-email="<?php echo $pnk['email'] ?>"
                                    data-sdt="<?php echo $pnk['sdt'] ?>"
                                    data-diaChi="<?php echo $pnk['dia_chi'] ?>"
                                    >
                                <i class="fa fa-pencil">Sửa</i></button>


<!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-phong.php" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        
                        <div class="col-md-2">
                            <label for="updateID" class="form-label">ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="updateHo" class="form-label">Họ</label>
                            <input type="text" class="form-control" id="updateHo" name="updateHo">
                        </div>
                        <div class="col-md-5">
                            <label for="updateTen" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="updateTen" name="updateTen">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateSdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="updateSdt" name="updateSdt">
                        </div>
                        <div class="col-md-6">
                            <label for="updateEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="updateEmail" name="updateEmail">
                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-md-12s">
                                <label for="updateDiaChi" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="updateDiaChi" name="updateDiaChi" placeholder="Nhập Địa chỉ"></textarea>    
                        </div>
                    </div> 
                    <button type="submit" class="btn btn-primary" id="updateUserBtn">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<button id="deleteBtn" class="btn btn-danger m-2" data-id="<?php echo $pnk['id']?>"><i class="fa fa-trash">Xóa</i></button>
<button id="chiTietKH" class="btn btn-warning" data-id="<?php echo $pnk['id']?>"><i class="bi bi-ticket-detailed-fill"></i>Chi tiết</i></button>


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
                document.getElementById('updateHo').value = button.getAttribute('data-ho');
                document.getElementById('updateTen').value = button.getAttribute('data-ten');
                document.getElementById('updateSdt').value = button.getAttribute('data-sdt');
                document.getElementById('updateEmail').value = button.getAttribute('data-email');
                document.getElementById('updateDiaChi').value = button.getAttribute('data-diaChi');
                modal.show();
            });
        });
        
        
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        $(document).on('click', '#updateUserBtn', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateID = $('#updateID').val();
            var updateHo = $('#updateHo').val();
            var updateTen = $('#updateTen').val();
            var updateEmail = $('#updateEmail').val();
            var updateSdt = $('#updateSdt').val();
            var updateDiaChi = $('#updateDiaChi').val();

            // // Kiểm tra các giá trị đầu vào trước khi gửi yêu cầu Ajax
            // if (!updateID || !updateQuocTich || !updateDiaChi || !updateHo || !updateTen || !updateSdt || !updateEmail) {
            //     alert('Vui lòng điền đầy đủ thông tin.');
            // }

            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateID);
            formData.append('ho', updateHo);
            formData.append('ten', updateTen);
            formData.append('sdt', updateSdt);
            formData.append('email', updateEmail);
            formData.append('dia_chi', updateDiaChi);

                // Gửi request Ajax
                $.ajax({
                    url: 'inc/update-user-khachhang.php',
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
                        url: 'inc/delete-user-khachhang.php', // Đường dẫn tới file PHP xử lý xóa trên server
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
        $(document).on('click', '#chiTietKH', function() {
            var id_khach_hang = $(this).data('id');
            window.location.href = 'chitiet-khachhang.php?id_khach_hang=' + id_khach_hang;
        });

   

    });
</script>


</body>
</html>
