<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once("inc/upload-chitiet-dichvu.php");
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
    <link rel="stylesheet" href="./css/search.css">
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
                <h1 class="h2">Dịch vụ chi tiết của gói</h1>
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
                <form action="inc/upload-chitiet-dichvu.php" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="id_goi" class="form-label">ID gói dịch vụ</label>
                            <!-- <input type="text" class="form-control" disabled 
                            value="<?php echo isset($_GET['id_goi_dich_vu']) ? intval($_GET['id_goi_dich_vu']) : 0; ?>">
                            <input type="hidden" name="id_goi_dich_vu" 
                            value="<?php echo isset($_GET['id_goi_dich_vu']) ? intval($_GET['id_goi_dich_vu']) : 0; ?>"> -->
                        </div>
                        <div class="col-md-4">
                            <label for="id_phong" class="form-label">Giá gói dịch vụ</label>
                            <input type="text" class="form-control" disabled 
                            value="<?php echo isset($gia['gia']) ? number_format($gia['gia'], 0, '.', '.') : ''; ?>" />
                        </div>
                        <div class="row mb-3" style="height: 300px">
                            <div class="container mt-3 px-2 mb-8">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="search-bar d-flex align-items-center">
                                    <span class="search-icon me-2"><i class="fa fa-search"></i></span>
                                    <input class="form-control" id="search" name="search" type="text" placeholder="Search by order#, name...">
                                    <button id="searchBtn" type="button" class="btn btn-dark flex-shrink-0">Tìm kiếm</button>
                                </div>
                                <div class="px-2">
                                        <span>Filters <i class="fa fa-angle-down"></i></span>
                                        <i class="fa fa-ellipsis-h ms-3"></i>
                                    </div>
                                </div>

                                <!-- Table responsive -->
                                <div class="table-responsive mt-4" id="collapse2" >
                                    <table class="table table-responsive table-borderless" id="Table1">
                                        <thead>
                                            <tr class="bg-light">
                                                <th scope="col" width="5%"><i class="bi bi-check-square-fill"></i></th>
                                                <th scope="col" width="10%">ID</th>
                                                <th scope="col" width="30%">Tên dịch vụ</th>
                                                <th scope="col" width="20%">Giá</th>
                                                <th scope="col" width="20%">Loại dịch vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultTable">
                                            <!-- Data from search will populate here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="addDichVu" class="btn btn-primary mt-5 mb-4">Thêm dịch vụ</button>
                    <button type="button" class="btn btn-dark mt-5 mb-4">
                            <a href="./goidichvu.php" class="text-decoration-none text-white">Quay lại quản lý dịch vụ</a>
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
                <h2>Danh sách các dịch vụ chi tiết của gói</h2>
                <table class="table table-striped table-sm table-bordered pt-6 mt-4">
                    <thead>
                        <tr>
                            <th scope="col" ># ID</th>
                            <th scope="col" >Tên dịch vụ</th>
                            <th scope="col" >Giá</th>
                            <th scope="col" >Loại dịch vụ</th>
                            <th scope="col" >Action</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php if (!empty($goidv)): ?>
                        <?php foreach ($goidv as $goidvs): ?>
                        <tr>
                            <td><?php echo $goidvs['id']; ?></td>
                            <td><?php echo $goidvs['ten_dich_vu']; ?></td>
                            <td><?php echo number_format($goidvs['don_gia'], 0, '.', '.') ?></td>
                            <td><?php if ($goidvs['loai_dich_vu'] == 'HoBoi') {
                                    echo 'Hồ Bơi';
                                } elseif ($goidvs['loai_dich_vu'] == 'NhaHang') {
                                    echo 'Nhà Hàng';
                                } elseif ($goidvs['loai_dich_vu'] == 'Spa') {
                                    echo 'Spa';
                                } elseif ($goidvs['loai_dich_vu'] == 'Gym') {
                                    echo 'Gym';
                                } elseif ($goidvs['loai_dich_vu'] == 'Golf') {
                                    echo 'Golf';
                                } ?></td>
                            <td>
                                <!-- Nút xóa -->
                                <button id="deleteBtn" class="btn btn-danger delete-image" 
                                data-id="<?php echo $goidvs['id']; ?>"
                                data-gdv="<?php echo $goidvs['id_goi_dich_vu']; ?>"
                                >
                                <i class="bi bi-trash-fill"></i> Xóa</i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Không có dịch vụ nào.</td>
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

        
        // //lắng nghe sự kiện cho nút XÓA 
        // $(document).on('click', '#deleteBtn', function(e) {
        //         e.preventDefault();
        //         // Lấy mã kho từ thuộc tính data
        //         var id = $(this).data('id');
        //         var id_goi_dich_vu = $(this).data('data-gdv');
        //         // console.log(maKho)
        //         // Kiểm tra nếu người dùng chắc chắn muốn xóa
        //         var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

        //         if (isConfirmed && id && id_goi_dich_vu) {
        //             // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
        //             $.ajax({
        //                 url: 'inc/delete-chitiet-dichvu.php', // Đường dẫn tới file PHP xử lý xóa trên server
        //                 method: 'POST',
        //                 data: { 
        //                     id: id,
        //                     id_goi_dich_vu: id_goi_dich_vu,
        //                 },
        //                 dataType: 'json',
        //                 success: function(response) {
        //                     // Xử lý phản hồi từ server
        //                     alert(response.message);
        //                     location.reload();
        //                 },
        //                 error: function(xhr, status, error) {
        //                     alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu.');
        //                     console.error('Error:', error);
        //                 }
        //             });
        //         }
        //         else{
        //             alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu !!!' + id_goi_dich_vu);
        //         }
        //     });


        // //lắng nghe sự kiện cho nút XÓA 
        $(document).on('click', '#deleteBtn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var idGoiDichVu = <?php echo isset($_GET['id_goi_dich_vu']) ? $_GET['id_goi_dich_vu'] : 'null'; ?>;
            
            if (!idGoiDichVu) {
                alert('ID gói dịch vụ không hợp lệ.');
                return;
            }

            var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

            if (isConfirmed && id) {
                $.ajax({
                    url: 'inc/delete-chitiet-dichvu.php', 
                    method: 'POST',
                    data: { id: id, id_goi_dich_vu: idGoiDichVu },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Server response:', response);
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error response:', xhr.responseText);
                        alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu.');
                    }
                });
            }
        });

    // search dịch vụ 
    $(document).ready(function () {
            // Xử lý sự kiện click nút tìm kiếm
            $('#searchBtn').click(function() {
                var search = $('#search').val(); // Lấy giá trị tìm kiếm từ input

                $.ajax({
                    url: 'inc/search_dichvu.php', // Đường dẫn tới file PHP xử lý tìm kiếm
                    method: 'GET',
                    data: { search: search }, // Gửi từ khóa tìm kiếm đến PHP
                    dataType: 'html', // Dữ liệu trả về dưới dạng HTML
                    success: function(response) {
                        // Cập nhật kết quả tìm kiếm vào bảng
                        $('#resultTable').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Có lỗi xảy ra trong quá trình xử lý yêu cầu.');
                        console.error('Error:', error);
                    }
                });
            });

        });
    // thêm dịch vụ
    $(function () {
        $("#addDichVu").click(function () {
            var selectedIds = [];
            var idGoiDichVu = <?php echo isset($_GET['id_goi_dich_vu']) ? $_GET['id_goi_dich_vu'] : 'null'; ?>;
            // Lấy các ID dịch vụ từ checkbox đã chọn
            $("#Table1 input[type=checkbox]:checked").each(function () {
                var row = $(this).closest("tr")[0];
                var id = row.cells[1].innerHTML;
                selectedIds.push(id);
            });

            $.ajax({
                url: 'inc/add-dichvu-goi.php',
                type: 'POST',
                data: {
                    id_goi_dich_vu : idGoiDichVu,
                    selected_ids: selectedIds
                },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);

                        if (result.success) {
                            alert(result.message); // Hiển thị thông báo thành công
                            location.reload(); // Reload trang
                        } else {
                            // Hiển thị lỗi chi tiết
                            if (result.code === 400) {
                                alert("Lỗi đầu vào: " + result.message);
                            } else if (result.code === 500) {
                                alert("Lỗi hệ thống: " + result.message);
                            } else {
                                alert("Lỗi không xác định: " + result.message);
                            }
                        }
                    } catch (e) {
                        console.error("Lỗi khi parse JSON:", e);
                        alert("Vui lòng chọn dịch vụ trước khi ấn thêm vào gói.");
                    }
                },
                error: function (xhr, status, error) {
                    alert("Không thể kết nối đến server. Vui lòng thử lại sau.");
                }
            });

            return false; // Ngăn hành động mặc định
        });


    });
    });
</script>


</body>
</html>
