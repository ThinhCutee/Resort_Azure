<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once("inc/upload-chitiet-checkout.php");
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
        #collapsePD {
            overflow-y: scroll;
            height: 150px;
            width: 100%;
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
                            <label for="id_phong" class="form-label">ID phòng đặt</label>
                            <input type="text" class="form-control" disabled 
                            value="<?php echo isset($_GET['id_phong_dat']) ? intval($_GET['id_phong_dat']) : 0; ?>">
                            <input type="hidden" name="id_phong_dat" 
                            value="<?php echo isset($_GET['id_phong_dat']) ? intval($_GET['id_phong_dat']) : 0; ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn-dark mt-3 mb-4">
                            <a href="./user-khachhang.php" class="text-decoration-none text-white">Quay lại quản lý khách hàng</a>
                    </button>
                </form>
                
                </div>
            </div>
            <!-- phòng đặt -->
            <div class="col-md-12 pt-5">
            <h2>Phòng đặt</h2>
            <div class="table-responsive" id="collapsePD">
                
                <table class="table table-striped table-sm table-bordered pt-6 mt-4">
                    <thead>
                        <tr>
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
                            <th scope="col" width="12%">Check-out</th>
                        </tr>
                    </thead>
                    <?php if (!empty($phongdat)): ?>
                        <?php foreach ($phongdat as $pd): ?>
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
                            <td>
                                <?php
                                    if($pd['is_check_out']==0){
                                        echo 'Chưa checkout';
                                    }else echo 'Đã checkout';
                                ?>
                            </td>
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
     <hr>
    <div class="col-md-12 pt-5">
    <h2>Dịch vụ đặt thêm</h2>
                <div class="table-responsive" id="collapseKH">
                    
                    <table class="table table-striped table-sm table-bordered pt-6 mt-4">
                        <thead>
                            <tr>
                                <th scope="col" width="2%">ID</th>
                                <th scope="col" width="5%">Số ĐT</th>
                                <th scope="col" width="15%">Tên gói dịch vụ</th>
                                <th scope="col" width="8%">Ngày đặt</th>
                                <th scope="col" width="8%">Giới hạn SD</th>
                                <th scope="col" width="8%">Sử dụng</th>
                                <th scope="col" width="5%">Giá tiền</th>
                                <th scope="col" width="10%">Trạng thái TT</th>
                                <th scope="col" width="10%">Action</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php if (!empty($dvDat)): ?>
                            <?php foreach ($dvDat as $dvd): ?>
                            <tr>
                                <td><?php echo $dvd['id_dich_vu_dat'] ?></td>
                                <td><?php echo $dvd['so_dien_thoai_khach_hang'] ?></td>
                                <td><?php echo $dvd['ten_dich_vu'] ?></td>
                                <td><?php echo $dvd['ngay_dat'] ?></td>
                                <td><?php echo $dvd['gioi_han'] ?></td>
                                <td><?php echo $dvd['so_lan_su_dung'] ?></td>
                                <td><?php echo number_format($dvd['don_gia'], 0, '.', '.'); ?></td>
                                <td><?php if( $dvd['trang_thai']==0)
                                    {
                                        echo "Chưa thanh toán";
                                    } else{
                                        echo "Đã thanh toán";
                                    }
                                ?></td>
                                <td><button id="btnCheckinDV" class="btn btn-info"
                            data-id="<?php echo $dvd['id_dich_vu_dat']?>"
                            >    
                            <i class="bi bi-building-fill-check"></i> Check-in</button></td>
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
      <hr>
      <h2 class="mt-5">CHECKOUT</h2>
      <div class="col-md-12">
            <div class="table-responsive" id="collapseKH">
                <table class="table table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>TỔNG TIỀN PHÒNG</strong></td>
                            <td class="text-right"><?php echo number_format($total_room_money, 0, ',', '.') . " VNĐ"; ?></td>
                        </tr>
                        <tr>
                            <td><strong>TỔNG TIỀN DỊCH VỤ</strong></td>
                            <td class="text-right"><?php echo number_format($total_service_money, 0, ',', '.') . " VNĐ"; ?></td>
                        </tr>
                        <tr class="text-danger">
                            <td><strong>TỔNG TIỀN CẦN THANH TOÁN</strong></td>
                            <td class="text-right"><?php echo number_format($total_money, 0, ',', '.') . " VNĐ"; ?></td>
                        </tr>
                        <tr>
                            <td class="text-success"><strong>THANH TOÁN</strong></td>
                            <td><button id='doneCheckout' class='btn btn-success'>
                            <i class='bi bi-sign-turn-slight-right-fill'></i> Check-out</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
        $(document).on('click', '#doneCheckout', function() {
            var id_phong_dat = <?php echo $id_phong_dat; ?>;  // Hoặc lấy từ dữ liệu động

            $.ajax({
                url: 'inc/update-checkout.php',  // Đường dẫn tới file PHP xử lý
                method: 'POST',
                data: {
                    id_phong_dat: id_phong_dat
                },
                success: function(response) {
                    var data = JSON.parse(response);  // Parse JSON response
                    if (data.status === 'success') {
                        alert(data.message);  // Hiển thị thông báo thành công
                        window.location.reload();  // Tải lại trang để cập nhật trạng thái
                    } else {
                        alert(data.message);  // Hiển thị thông báo lỗi
                    }
                },
                error: function() {
                    alert('Không thể kết nối với máy chủ. Vui lòng thử lại!');
                }
            });
        });


        //check in
        $(document).on('click', '#btnCheckinDV', function() {
        var id_dich_vu_dat = $(this).data('id'); // Lấy ID dịch vụ từ thuộc tính data-id

        // Gửi yêu cầu AJAX để kiểm tra số lần sử dụng và giới hạn dịch vụ
        $.ajax({
            url: 'inc/checkin-check.php', // Tạo một file mới để kiểm tra và xử lý
            type: 'POST',
            data: {
                id_dich_vu_dat: id_dich_vu_dat // ID của dịch vụ được gửi qua
            },
            success: function(response) {
                var result = JSON.parse(response);

                if (result.success) {
                    if (result.confirm) {
                        // Hiển thị hộp thoại xác nhận nếu số lần sử dụng vượt quá giới hạn
                        var userConfirm = confirm(result.message); 
                        if (userConfirm) {
                            // Nếu người dùng xác nhận, gửi yêu cầu cập nhật số lần sử dụng
                            $.ajax({
                                url: 'inc/update-checkin.php', // Đường dẫn xử lý update số lần sử dụng
                                type: 'POST',
                                data: {
                                    id_dich_vu_dat: id_dich_vu_dat
                                },
                                success: function(updateResult) {
                                    var updateResponse = JSON.parse(updateResult);
                                    alert(updateResponse.message); // Thông báo kết quả
                                    location.reload(); // Reload trang để hiển thị thay đổi
                                },
                                error: function(xhr, status, error) {
                                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                                }
                            });
                        }
                    } else {
                        // Nếu không cần xác nhận, tự động cập nhật
                        alert(result.message);
                        location.reload();
                    }
                } else {
                    alert(result.message); // Thông báo lỗi nếu không thành công
                }
            },
            error: function(xhr, status, error) {
                alert("Có lỗi xảy ra. Vui lòng thử lại.");
            }
        });
    });





    });
</script>


</body>
</html>
