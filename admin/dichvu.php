<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT ten_goi_dich_vu FROM goidichvu");
    $dv = show("SELECT * FROM dichvu");
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
                <h1 class="h2">Quản lý dịch vụ</h1>
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
                    <form action="inc/add-dichvu.php" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tenDV" class="form-label">Tên dịch vụ</label>
                                <input type="text" class="form-control" id="tenDV" name="tenDV" placeholder="Nhập tên gói dịch vụ">
                            </div>
                            <div class="col-md-4">
                                <label for="loaiDV" class="form-label">Loại dịch vụ</label>
                                <select class="form-select" id="loaiDV" name="loaiDV">
                                    <option value="HoBoi">Hồ bơi</option>
                                    <option value="NhaHang">Nhà hàng</option>
                                    <option value="Gym">Gym</option>
                                    <option value="Spa">Spa</option>
                                    <option value="Golf">Golf</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="gia" class="form-label">Đơn Giá</label>
                                <input type="number" class="form-control" id="gia" name="gia" placeholder="Nhập giá">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hinhAnh" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="hinhAnh" name="hinhAnh" placeholder="Chọn ảnh">
                            </div>
                            <div class="col-md-6">
                                <label for="gioiHan" class="form-label">Giới hạn số lần sử dụng</label>
                                <input type="number" class="form-control" id="gioiHan" name="gioiHan" placeholder="Nhập giới hạn sử dụng">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                    <label for="moTa" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="moTa" name="moTa" placeholder="Nhập mô tả"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm dịch vụ</button>
                        <a href="./goidichvu.php" class="btn btn-warning">Quản lý gói dịch vụ</a>
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
                            <th>Mã dịch vụ</th>
                            <th>Loại dịch vụ</th>
                            <th>Tên dịch vụ</th>
                            <th>Giá</th>
                            <th>Giới hạn</th>
                            <th>Mô tả</th>
                            <th>Gói dịch vụ</th>
                            <th>Hình ảnh</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($dv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id'] ?></td>
                        <td><?php echo $pnk['ten_dich_vu'] ?></td>
                        <td><?php 
                            if($pnk['loai_dich_vu'] =='HoBoi'){
                                echo "Hồ Bơi";
                            }else if($pnk['loai_dich_vu'] =='NhaHang')
                            {
                                echo "Nhà Hàng";
                            }
                            else if($pnk['loai_dich_vu'] =='Spa')
                            {
                                echo "Spa";
                            }
                            else if($pnk['loai_dich_vu'] =='Gytm')
                            {
                                echo "Gym";
                            }
                            else if($pnk['loai_dich_vu'] =='Golf')
                            {
                                echo "Golf";
                            }
                        ?></td>
                        <td><?php echo number_format($pnk['don_gia'], 0, '.', '.'); ?></td>
                        <td><?php echo $pnk['gioi_han'] ?></td>
                        <td><?php echo $pnk['mo_ta'] ?></td>
                        <td><?php echo $pnk['id_goi_dich_vu'] ?></td>
                        <td><?php 
                            if($pnk['hinh_anh'] == null || $pnk['hinh_anh'] =='')
                            {
                                echo "";
                            }else{
                                echo "<img width=100 height=100 src='../admin/uploads/dichvu/" . $pnk['hinh_anh'] . "' />"; 
                            }
                        ?></td>
                        <td>
                            <button class="btn btn-primary updateBtn mt-2"
                                    data-id="<?php echo $pnk['id'] ?>"
                                    data-moTa="<?php echo $pnk['mo_ta'] ?>"
                                    data-tenDV="<?php echo $pnk['ten_dich_vu'] ?>"
                                    data-gia="<?php echo $pnk['don_gia'] ?>"
                                    data-loaiDV = "<?php echo $pnk['loai_dich_vu'] ?>"
                                    data-hinhAnh = "<?php echo $pnk['hinh_anh'] ?>"
                                    data-gioiHan = "<?php echo $pnk['gioi_han'] ?>">
                                <i class="fa fa-pencil">Sửa</i></button>


                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-dichvu.php" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateID" class="form-label">Dịch vụ ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateTenGV" class="form-label">Tên dịch vụ</label>
                            <input type="text" class="form-control" id="updateTenGV" name="tenGV">
                        </div>
                    </div>
                    <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="updateLoaiDV" class="form-label">Loại dịch vụ</label>
                                <select class="form-select" id="updateLoaiDV" name="loaiDV">
                                    <option value="HoBoi">Hồ bơi</option>
                                    <option value="NhaHang">Nhà hàng</option>
                                    <option value="Gym">Gym</option>
                                    <option value="Spa">Spa</option>
                                    <option value="Golf">Golf</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="updateGioiHan" class="form-label">Giới hạn</label>
                                <input type="number" class="form-control" id="updateGioiHan" name="gioiHan">
                            </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateGia" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="updateGia" name="gia">
                        </div>
                        <div class="col-md-6">
                                <label for="updateMoTaoTa" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="updateMoTa" name="moTa"></textarea>
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
<button id="deleteDV" class="btn btn-danger mt-2" data-id="<?php echo $pnk['id']?>"><i class="fa fa-trash">Xóa</i></button>
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
                document.getElementById('updateMoTa').value = button.getAttribute('data-moTa');
                document.getElementById('updateTenGV').value = button.getAttribute('data-tenDV');
                document.getElementById('updateGia').value = button.getAttribute('data-gia');
                document.getElementById('updateLoaiDV').value = button.getAttribute('data-loaiDV');
                document.getElementById('updateGioiHan').value = button.getAttribute('data-gioiHan');
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
                var updateID = document.getElementById('updateID').value;
                var updateMoTa = document.getElementById('updateMoTa').value;
                var updateGia = document.getElementById('updateGia').value;
                var updateTenDV = document.getElementById('updateTenGV').value;
                var updateLoaiDV = document.getElementById('updateLoaiDV').value;
                console.log(updateID);
                var formData = new FormData();
                formData.append('id', updateID);
                formData.append('moTa', updateMoTa);
                formData.append('gia', updateGia);
                formData.append('tenDV', updateTenDV);
                formData.append('loaiDV', updateLoaiDV);
                console.log(formData);
            });
        });
        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        document.getElementById('updateUserBtn').addEventListener('click', function() {
            // Lấy dữ liệu từ các input trong modal
            var updateID = document.getElementById('updateID').value;
            var updateMoTa = document.getElementById('updateMoTa').value;
            var updateGia = document.getElementById('updateGia').value;
            var updateTenDV = document.getElementById('updateTenGV').value;
            var updateLoaiDV = document.getElementById('updateLoaiDV').value;
            var updateGioiHan = document.getElementById('updateGioiHan').value;
            var updateHinhAnh = $('#updateHinhAnh').prop('files')[0]; // Hình ảnh mới

            if (!updateTenDV || !updateGia || !updateLoaiDV || !updateGioiHan) {
                alert('Vui lòng điền đầy đủ thông tin.');
                return;
            }
            else{
                 // Tạo formData để gửi dữ liệu và file hình ảnh lên server
                var formData = new FormData();
                formData.append('id', updateID);
                formData.append('moTa', updateMoTa);
                formData.append('gia', updateGia);
                formData.append('tenDV', updateTenDV);
                formData.append('loaiDV', updateLoaiDV);
                formData.append('hinhAnh', updateHinhAnh); // Đặt hình ảnh mới vào formData
                formData.append('gioiHan', updateGioiHan);

                // Gửi request Ajax
                $.ajax({
                    url: 'inc/update-dichvu.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        alert(data.message);
                        if (data.status === 'success') {
                            var modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
                            modal.hide();
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Cập nhật không thành công.');
                        location.reload();
                    }
                });
            }
        });

        //lắng nghe sự kiện cho nút XÓA gói dịch vụ
        $(document).on('click', '#deleteDV', function(e) {
                e.preventDefault();
                // Lấy mã kho từ thuộc tính data
                var id = $(this).data('id');
                // console.log(maKho)
                // Kiểm tra nếu người dùng chắc chắn muốn xóa
                var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/delete-dichvu.php', // Đường dẫn tới file PHP xử lý xóa trên server
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

        

    });
</script>


</body>
</html>
