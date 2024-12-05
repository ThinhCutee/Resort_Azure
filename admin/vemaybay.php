<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT 
        v.id as id_ve,
        v.ngay_dat_ve as ngay_dat_ve,
        v.trang_thai as trang_thai,
        k.sdt as so_khachhang,
        g.so_ghe as so_ghe,
        -- khach hang
        k.id as id_kh,
        k.ho as ho,
        k.ten as ten,
        k.sdt as sdt,
        k.email as email,
        k.dia_chi as dia_chi,
        -- nguoi bay
        nb.id as id_nb,
        nb.ho as ho_nb,
        nb.ten as ten_nb,
        nb.ngay_sinh as ngay_sinh,
        nb.gioi_tinh as gioi_tinh,
        nb.cccd as cccd,
        nb.sdt as sdt_nb,
        nb.quoc_tich as quoc_tich
        FROM 
        vemaybay v join khachhang k ON v.id_khach_hang = k.id
        join ghe_chuyenbay gcb ON v.id_ghe_chuyenbay = gcb.id
        join ghe g ON g.id = gcb.id_ghe
        join thongtinnguoibay nb on nb.id = v.id_nguoi_bay

        ");
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
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
     <!-- <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <?php require('inc/links.php')?>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <?php require('inc/admin-navbar1.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Vé máy bay</h1>
                
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
                                <div class="col-md-4">
                                    <label for="searchTen" class="form-label">Search số điện thoại khách hàng</label>
                                    <input class="form-control" id="searchTen" name="searchTen" type="text" placeholder="Tìm theo người đặt...">
                                </div>
                                <!-- chọn ngày -->
                                <div class="col-md-3">
                                    <label for="" class="form-label"> Chọn khoảng thời gian từ :</label>
                                    <input type="date" class="form-control" id="ngayBatDau" name="ngayBatDau" placeholder="ngày bắt đầu">
                                </div>
                                <div class="col-md-3">
                                <label for="" class="form-label"> ~ đến:</label>
                                    <input type="date" class="form-control" id="ngayKetThuc" name="ngayKetThuc" placeholder="Ngày kết thúc">
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
                                    <th >ID</th>
                                    <th >SDT người đặt</th>
                                    <th >Số ghế </th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="resultTable">
                                <!-- Data from search will populate here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- xem -->
            <div class="col-md-12 pt-5">
                
            <div class="table-responsive" id="collapse1">
                <h2>Danh sách các đơn đặt dịch vụ</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th >ID</th>
                            <th >SDT người đặt</th>
                            <th >Số ghế </th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id_ve'] ?></td>
                        <td><?php echo $pnk['so_khachhang'] ?></td>
                        <td><?php echo $pnk['so_ghe'] ?></td>
                        <td><?php echo $pnk['ngay_dat_ve'] ?></td>
                        <td><?php if( $pnk['trang_thai']==0)
                            {
                                echo "Chưa check-in";
                            } else{
                                 echo "Đã check-in";
                            }
                        ?></td>
                        <td>
                            
                            <button id="" class="btn btn-success chiTietKH" 
                            data-id="<?php echo $pnk['id_ve']?>"
                            data-idKH="<?php echo $pnk['id_kh']?>"
                            data-hoKH="<?php echo $pnk['ho']?>"
                            data-tenKH="<?php echo $pnk['ten']?>"
                            data-sdtKH="<?php echo $pnk['sdt']?>"
                            data-email="<?php echo $pnk['email']?>"
                            data-diaChi="<?php echo $pnk['dia_chi']?>"
                            > 
                            <i class="bi bi-person-fill-check"></i>Chi tiết người đặt</i></button>


                                   <!-- Modal Bootstrap -->
<div class="modal fade" id="myModalKH" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thông tin người đặt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="updateID" class="form-label">ID khách hàng</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="updateHoKH" class="form-label">Họ</label>
                            <input type="text" class="form-control" id="updateHoKH" name="hoKH" required>
                        </div>
                        <div class="col-md-5">
                            <label for="updateTenKH" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="updateTenKH" name="tenKH" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateSDT" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="updateSDT" name="sdt" required>
                        </div>
                        <div class="col-md-6">
                            <label for="updateEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="updateEmail" name="email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="updateDiaChi" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="updateDiaChi" name="diaChi" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

                            <button id="" class="btn btn-warning chiTietNB" 
                            data-id="<?php echo $pnk['id_ve']?>"
                            data-idNB="<?php echo $pnk['id_nb']?>"
                            data-hoNB="<?php echo $pnk['ho_nb']?>"
                            data-tenNB="<?php echo $pnk['ten_nb']?>"
                            data-sdtNB="<?php echo $pnk['sdt_nb']?>"
                            data-quocTich="<?php echo $pnk['quoc_tich']?>"
                            data-CCCD="<?php echo $pnk['cccd']?>"
                            data-ngaySinh="<?php echo $pnk['ngay_sinh']?>"
                            data-gioiTinh="<?php
                                if($pnk['gioi_tinh']==1)
                                {
                                    echo "Nam";
                                }else echo "Nữ"?>"
                            ><i class="bi bi-airplane-engines-fill"></i>
                            Thông tin người bay</i></button>
                                     <!-- Modal Bootstrap -->
<div class="modal fade" id="myModalNB" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thông tin người bay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="updateIDNB" class="form-label">ID người bay</label>
                            <input type="text" class="form-control" id="updateIDNB" name="id" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="updateHoNB" class="form-label">Họ</label>
                            <input type="text" class="form-control" id="updateHoNB" name="hoKH" required>
                        </div>
                        <div class="col-md-5">
                            <label for="updateTenNB" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="updateTenNB" name="tenKH" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateSDTNB" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="updateSDTNB" name="sdt" required>
                        </div>
                        <div class="col-md-6">
                            <label for="updateCCCD" class="form-label">CCCD (nếu cung cấp)</label>
                            <input type="email" class="form-control" id="updateCCCD" name="email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="updateNS" class="form-label">Ngày sinh</label>
                            <input type="text" class="form-control" id="updateNS" name="sdt" required>
                        </div>
                        <div class="col-md-4">
                            <label for="updateGT" class="form-label">Giới tính</label>
                            <input type="email" class="form-control" id="updateGT" name="email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="updateQC" class="form-label">Quốc tịch</label>
                            <input type="text" class="form-control" id="updateQC" name="diaChi" required>
                        </div>
                    </div>
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
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
<!-- Bootstrap Bundle with Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    
    // SỬA gói dịch vụ
    document.addEventListener('DOMContentLoaded', function () {
       

        // chi tiết khách hàng đặt vé
        var updateButtons = document.querySelectorAll('.chiTietKH');
        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('myModalKH'), {});
                document.getElementById('updateID').value = button.getAttribute('data-idKH');
                document.getElementById('updateHoKH').value = button.getAttribute('data-hoKH');
                document.getElementById('updateTenKH').value = button.getAttribute('data-tenKH');
                document.getElementById('updateSDT').value = button.getAttribute('data-sdtKH');
                document.getElementById('updateEmail').value = button.getAttribute('data-email');
                document.getElementById('updateDiaChi').value = button.getAttribute('data-diaChi');
               
                modal.show();
            });
        });
         // chi tiết người bay
         var updateButtons = document.querySelectorAll('.chiTietNB');
        updateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('myModalNB'), {});
                document.getElementById('updateIDNB').value = button.getAttribute('data-idNB');
                document.getElementById('updateHoNB').value = button.getAttribute('data-hoNB');
                document.getElementById('updateTenNB').value = button.getAttribute('data-tenNB');
                document.getElementById('updateSDTNB').value = button.getAttribute('data-sdtNB');
                document.getElementById('updateNS').value = button.getAttribute('data-ngaySinh');
                document.getElementById('updateQC').value = button.getAttribute('data-quocTich');
                document.getElementById('updateGT').value = button.getAttribute('data-gioiTinh');
                document.getElementById('updateCCCD').value = button.getAttribute('data-CCCD');

                modal.show();
            });
        });

        $(document).ready(function () {
            // Xử lý sự kiện click nút tìm kiếm
            $('#searchBtn').click(function() {
                var search = $('#searchTen').val(); // Lấy giá trị tìm kiếm từ input
                var ngayKetThuc = $('#ngayKetThuc').val(); // Lấy giá trị tìm kiếm từ input
                var ngayBatDau = $('#ngayBatDau').val(); // Lấy giá trị tìm kiếm từ input
                $.ajax({
                    url: 'inc/search_vemaybay.php', // Đường dẫn tới file PHP xử lý tìm kiếm
                    method: 'GET',
                    data: { 
                        search: search,
                        ngayBatDau: ngayBatDau,
                        ngayKetThuc:ngayKetThuc
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
