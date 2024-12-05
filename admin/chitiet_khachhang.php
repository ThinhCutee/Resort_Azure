<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once("inc/upload-chitiet-khachhang.php");
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
        #collapseKH {
            overflow-y: scroll;
            height: 300px;
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
                <h1 class="h2">Lịch sử đặt của khách hàng</h1>
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
                            <label for="id_phong" class="form-label">ID Khách hàng</label>
                            <input type="text" class="form-control" disabled 
                            value="<?php echo isset($_GET['id_khach_hang']) ? intval($_GET['id_khach_hang']) : 0; ?>">
                            <input type="hidden" name="id_khach_hang" 
                            value="<?php echo isset($_GET['id_khach_hang']) ? intval($_GET['id_khach_hang']) : 0; ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-dark mt-3 mb-4">
                            <a href="./user-khachhang.php" class="text-decoration-none text-white">Quay lại quản lý khách hàng</a>
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
            <!-- phòng đặt -->
            <div class="col-md-12 pt-5">
                
            <div class="table-responsive" id="collapseKH">
                <h2>Phòng đặt</h2>
                <table class="table table-striped table-sm table-bordered pt-6 mt-4">
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
                        </tr>
                    </thead>
                    <?php if (!empty($phongdat)): ?>
                        <?php foreach ($phongdat as $pd): ?>
                            <td><?php echo $pd['id']; ?></td>
                            <td><?php echo $pd['id_uu_dai']; ?></td>
                            <td><?php
                                // Lấy tên gói dịch vụ từ bảng goidichvu
                                $query = "SELECT so_phong FROM phong WHERE id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->execute(array($pd['id_phong']));
                                $maPhong = $stmt->fetchColumn();
                                echo $maPhong;
                            ?></td>
                            <td>
                            <?php
                                // Lấy tên gói dịch vụ từ bảng goidichvu
                                $query = "SELECT ten_goi_dich_vu FROM goidichvu WHERE id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->execute(array($pd['id_goi_dich_vu']));
                                $tenGoiDichVu = $stmt->fetchColumn();
                                echo $tenGoiDichVu;
                            ?>
                            </td>
                            <td>
                                <?php
                                // Lấy Số ĐT từ bảng khachhang
                                $query = "SELECT sdt FROM khachhang WHERE id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->execute(array($pd['id_khach_hang']));
                                $soDT = $stmt->fetchColumn();
                                echo $soDT;
                                ?>
                            </td>
                            <td><?php echo $pd['so_nguoi']; ?></td>
                            <td><?php echo $pd['ngay_dat_phong']; ?></td>
                            <td><?php echo $pd['ngay_nhan_phong']; ?></td>
                            <td><?php echo $pd['ngay_tra_phong']; ?></td>
                            <td><?php echo number_format($pd['tong_tien'], 0, '.', '.'); ?></td>
                            <td><?php if ($pd['trang_thai']==0)
                                    echo "Chờ xử lý"; 
                                elseif($pd['trang_thai']==1)
                                    echo "Hoàn thành";
                                else echo "Đã hủy";
                                ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có phòng đặt nào.</td>
                        </tr>
                    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

    <!-- Dịch vụ đặt-->
    <!-- xem -->
    <div class="col-md-12 pt-5">
                
                <div class="table-responsive" id="collapseKH">
                    <h2>Dịch vụ đặt thêm</h2>
                    <table class="table table-striped table-sm table-bordered pt-6 mt-4">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">ID</th>
                                <th scope="col" width="5%">Số ĐT</th>
                                <th scope="col" width="15%">Tên gói dịch vụ</th>
                                <th scope="col" width="5%">Giá</th>
                                <th scope="col" width="5%">Ngày đặt</th>
                                <th scope="col" width="8%">Số lần sử dụng</th>
                                <th scope="col" width="10%">Trạng thái TT</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php if (!empty($dvDat)): ?>
                            <?php foreach ($dvDat as $dvd): ?>
                            <tr>
                                <td><?php echo $dvd['id_dich_vu_dat'] ?></td>
                                <td><?php echo $dvd['so_dien_thoai_khach_hang'] ?></td>
                                <td><?php echo $dvd['ten_dich_vu'] ?></td>
                                <td><?php echo number_format($dvd['don_gia'], 0, '.', '.'); ?></td>
                                <td><?php echo $dvd['ngay_dat'] ?></td>
                                <td><?php echo $dvd['so_lan_su_dung'] ?></td>
                                <td><?php if( $dvd['trang_thai']==0)
                                    {
                                        echo "Chưa thanh toán";
                                    } else{
                                        echo "Đã thanh toán";
                                    }
                                ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có dịch vụ đặt nào.</td>
                            </tr>
                        <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    <!-- vé máy bay  -->
     <!-- xem -->
     <div class="col-md-12 pt-5">
                
                <div class="table-responsive" id="collapseKH">
                    <h2>Vé máy bay</h2>
                    <table class="table table-striped table-sm table-bordered pt-6 mt-4">
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
                        <?php if (!empty($ve)): ?>
                            <?php foreach ($ve as $pnk): ?>
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
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có vé máy bay nào.</td>
                            </tr>
                        <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        <!-- hết xem -->
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
    });
</script>


</body>
</html>
