<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once("inc/upload-image-phong.php");
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/common.css">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <?php require('inc/links.php')?>
    <script type="text/javascript">
        // Hàm để hiển thị thông báo
        function showAlert(message, success) {
            alert(message);
        }

        // Gọi hàm showAlert với thông điệp từ PHP
        window.onload = function() {
            var message = "<?php echo addslashes($message); ?>";
            var success = <?php echo json_encode($success); ?>;
            showAlert(message, success);
        };
    </script>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php require('inc/admin-navbar1.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ảnh chi tiết của phòng</h1>
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
                <form action="inc/upload-image-phong.php" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="id_phong" class="form-label">ID phòng</label>
                            <input type="text" class="form-control" disabled 
                            value="<?php echo isset($_GET['id_phong']) ? intval($_GET['id_phong']) : 0; ?>">
                            <input type="hidden" name="id_phong" 
                            value="<?php echo isset($_GET['id_phong']) ? intval($_GET['id_phong']) : 0; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="anh_phong" class="form-label">Chọn các ảnh:</label>
                            <input type="file" class="form-control" name="anh_phong[]" 
                            multiple required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 mb-4">Upload Ảnh</button>
                    <button type="button" class="btn btn-dark mt-3 mb-4">
                            <a href="./phong.php" class="text-decoration-none text-white">Quay lại quản lý phòng</a>
                    </button>
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
                <h2>Danh sách các ảnh chi tiết của phòng</h2>
                <table class="table table-striped table-sm table-bordered pt-6 mt-4">
                    <thead>
                        <tr>
                            <th>ID Ảnh</th>
                            <th>ID Phòng</th>
                            <th>Hiển thị hình ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $image): ?>
                        <tr>
                            <td><?php echo $image['id']; ?></td>
                            <td><?php echo $image['id_phong']; ?></td>
                            <td><img src="uploads/<?php echo $image['anh_phong']; ?>" class="img-thumbnail" alt="Ảnh phòng" style="width: 150px; height: auto;"></td>
                            <td>
                                <!-- Nút sửa -->
                                <button id="updateBtn" class="btn btn-primary updateBtn" 
                                data-id="<?php echo $image['id']; ?>"
                                data-idPhong="<?php echo $image['id_phong']; ?>"
                                data-hinhAnh="<?php echo $image['anh_phong'];?>">
                                <i class="bi bi-pencil-square"></i> Đổi ảnh</button>
                                
                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Đổi ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-image-phong.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_image_phong" id="id_image_phong">
                            <input type="hidden" name="id_phong" id="id_phong">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateHinhAnh" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="updateHinhAnh" name="hinhAnh" require>
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
                                <!-- Nút xóa -->
                                <button id="deleteBtn" class="btn btn-danger delete-image" 
                                data-id="<?php echo $image['id']; ?>">
                                <i class="bi bi-trash-fill"></i> Xóa</i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có ảnh nào.</td>
                        </tr>
                    <?php endif; ?>
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
    
    // SỬA ảnh
    document.addEventListener('DOMContentLoaded', function () {
    var updateButtons = document.querySelectorAll('.updateBtn');
    updateButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('id_image_phong').value = button.getAttribute('data-id');
            document.getElementById('id_phong').value = button.getAttribute('data-idPhong');
            var modal = new bootstrap.Modal(document.getElementById('myModal'), {});
            var hinhAnhCu = button.getAttribute('data-hinhAnh');
            document.getElementById('updateHinhAnhCu').value = hinhAnhCu;

            var previewUpdateHinhAnh = document.getElementById('previewUpdateHinhAnh');
            previewUpdateHinhAnh.src = hinhAnhCu ? hinhAnhCu : 'default_image.jpg';

            var updateHinhAnhInput = document.getElementById('updateHinhAnh');
            updateHinhAnhInput.addEventListener('change', function () {
                previewImage(this);
            });

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        previewUpdateHinhAnh.src = e.target.result;
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
            var updateID = $('#id_image_phong').val();
            var updateIDPhong = $('#id_phong').val();
            var updateHinhAnh = $('#updateHinhAnh').prop('files')[0]; // Hình ảnh mới

            // Kiểm tra xem người dùng đã chọn hình ảnh mới hay chưa
            if (!updateHinhAnh) {
                alert('Vui lòng chọn hình ảnh mới.');
                return; // Dừng xử lý nếu không có hình ảnh mới được chọn
            }

            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id_image_phong', updateID);
            formData.append('id_phong', updateIDPhong);
            formData.append('anh_phong', updateHinhAnh); // Đặt hình ảnh mới vào formData

            // Gửi request Ajax
            $.ajax({
                url: 'inc/update-image-phong.php',
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
                        alert(response.message);
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

        
        //lắng nghe sự kiện cho nút XÓA 
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
                        url: 'inc/delete-image-phong.php', // Đường dẫn tới file PHP xử lý xóa trên server
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
