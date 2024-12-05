<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    // $gdv = show("SELECT ten_goi_dich_vu FROM goidichvu");
    $nv = show("SELECT * FROM nhanvien");
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
     <link rel="stylesheet" href="./css/search.css">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <?php require('inc/links.php')?>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php require('inc/admin-navbar1.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Nhân viên</h1>
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
                     <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tenNV" class="form-label">Search tên nhân viên</label>
                                    <input class="form-control" id="searchTen" name="searchTen" type="text" placeholder="Tìm nhân viên...">
                                </div>
                                <div class="col-md-6">
                                <label for="phongBan" class="form-label">Phòng ban</label>
                                    <select class="form-select" id="phongBan" name="phongBan">
                                        <option value="0">Chọn phòng ban</option>
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
                            <button id="searchBtn" type="button" class="btn btn-dark flex-shrink-0"><i class="bi bi-search-heart-fill"></i> Tìm kiếm</button>
                    </div>
                <div class="col-md-12">
                    <!-- Table responsive -->
                    <div class="table-responsive mt-4" id="collapse2" >
                        <table class="table table-responsive table-borderless" id="Table1">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col" width="10%">ID</th>
                                    <th scope="col" width="30%">Họ tên nhân viên</th>
                                    <th scope="col" width="20%">Phòng ban</th>
                                    <th scope="col" width="20%">Địa chỉ</th>
                                    <th scope="col" width="20%">ID admin</th>
                                </tr>
                            </thead>
                            <tbody id="resultTable">
                                <!-- Data from search will populate here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-responsive mt-5" id="collapse1">
                <h2>Danh sách nhân viên</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th scope="col" width="10%">ID</th>
                            <th scope="col" width="30%">Họ tên nhân viên</th>
                            <th scope="col" width="20%">Phòng ban</th>
                            <th scope="col" width="20%">Địa chỉ</th>
                            <th scope="col" width="20%">ID admin</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($nv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id'] ?></td>
                        <td><?php echo $pnk['ho_ten'] ?></td>
                        <td><?php 
                            if($pnk['phong_ban'] == 'AD')
                            {
                                echo 'Admin';
                            }else if($pnk['phong_ban'] == 'LT')
                            {
                                echo 'Lễ Tân';
                            }else if($pnk['phong_ban'] == 'DV-Spa')
                            {
                                echo 'Dịch vụ Spa';
                            }else if($pnk['phong_ban'] == 'DV-NhaHang')
                            {
                                echo 'Dịch vụ Nhà hàng';
                            }else if($pnk['phong_ban'] == 'DV-Gym')
                            {
                                echo 'Dịch vụ Gym';
                            }
                            else if($pnk['phong_ban'] == 'DV-Golf')
                            {
                                echo 'Dịch vụ Golf';
                            }else if($pnk['phong_ban'] == 'DV-HoBoi')
                            {
                                echo 'Dịch vụ Hồ bơi';
                            }
                        ?></td>
                        <td><?php echo $pnk['dia_chi'] ?></td>
                        <td><?php echo $pnk['id_admin'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
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
        $(document).ready(function () {
            // Xử lý sự kiện click nút tìm kiếm
            $('#searchBtn').click(function() {
                var search = $('#searchTen').val(); // Lấy giá trị tìm kiếm từ input
                var phongBan = $('#phongBan').val(); // Lấy giá trị tìm kiếm từ input
                $.ajax({
                    url: 'inc/search_nhanvien.php', // Đường dẫn tới file PHP xử lý tìm kiếm
                    method: 'GET',
                    data: { 
                        search: search,
                        phongBan: phongBan
                     }, // Gửi từ khóa tìm kiếm đến PHP
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

    });
    
</script>


</body>
</html>
