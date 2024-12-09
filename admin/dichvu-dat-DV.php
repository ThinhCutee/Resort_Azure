<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT 
        dvdat.id AS id_dich_vu_dat,
        dv.ten_dich_vu as ten_dich_vu,
        dv.gioi_han as gioi_han,
        kh.sdt AS so_dien_thoai_khach_hang,
        dvdat.ngay_dat as ngay_dat,
        dv.don_gia as don_gia,
        dvdat.trang_thai as trang_thai,
        dvdat.so_lan_su_dung AS so_lan_su_dung
    FROM 
        dichvudat dvdat
    JOIN 
        dichvu dv ON dvdat.id_dich_vu = dv.id
    JOIN 
        phongdat pd ON dvdat.id_phong_dat = pd.id
    JOIN 
        khachhang kh ON pd.id_khach_hang = kh.id
    Group by
    dvdat.id ,
        dv.ten_dich_vu ,
        kh.sdt ,
        dvdat.ngay_dat ,
        dv.don_gia ,
        dvdat.trang_thai ,
        dvdat.so_lan_su_dung
    order by  dvdat.id ");
    $dv = show("SELECT * FROM dichvu");
    $ks = show("SELECT * FROM khachsan");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Panel</title>
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
        <?php require('inc/admin-navbarDV.php') ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Đặt dịch vụ</h1>
                
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
                    <!-- Form and input fields action="inc/add-goidichvu.php" -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="soDT" class="form-label">Số điện thoại khách hàng</label>
                                    <input type="text" class="form-control" id="soDT" name="soDT" placeholder="Nhập SĐT">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email khách hàng</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="maPhong" class="form-label">Chọn phòng để đặt dịch vụ</label>
                                    <select class="form-control" id="maPhong" name="maPhong">
                                        <option value="">Chưa có phòng nào</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="trangThai" class="form-label">Trạng thái thanh toán</label>
                                    <select class="form-select" id="trangThai" name="trangThai">
                                        <option selected>Chọn trạng thái</option>
                                        <option value="0">Chưa thanh toán</option>
                                        <option value="1">Đã thanh toán</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="gia" class="form-label">Giá</label>
                                    <input type="text" class="form-control" id="gia" name="gia" placeholder="Chọn dịch vụ để tính giá" readonly>
                                </div>
                            </div>
                            <hr>
                            <!-- Search section -->
                            <div class="row mb-3" style="height: 300px">
                                <div class="container mt-3 px-2 mb-8">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <div class="search-bar d-flex align-items-center">
                                        <span class="search-icon me-2"><i class="fa fa-search"></i></span>
                                        <input class="form-control" id="search" name="search" type="text" placeholder="Search theo tên">
                                        <select class="form-select" id="loaiDV" name="loaiDV">
                                            <option value="">Chọn loại dịch vụ</option>
                                            <option value="HoBoi">Hồ bơi</option>
                                            <option value="NhaHang">Nhà hàng</option>
                                            <option value="Gym">Gym</option>
                                            <option value="Spa">Spa</option>
                                            <option value="Golf">Golf</option>
                                        </select>
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
                                                    <th scope="col" width="15%">Giá</th>
                                                    <th scope="col" width="15%">Giới hạn sử dụng</th>
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

                            <!-- Additional fields -->
                            <div class="row mb-3 mt-5">
                            </div>
                            <button type="submit" id="addDichVuDat" class="btn btn-primary mt-5">Đặt dịch vụ</button>
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
                <h2>Danh sách các đơn đặt dịch vụ</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th scope="col" width="2%">ID</th>
                            <th scope="col" width="3%">Số ĐT</th>
                            <th scope="col" width="15%">Tên gói dịch vụ</th>
                            <th scope="col" width="5%">Giá</th>
                            <th scope="col" width="8%">Ngày đặt</th>
                            <th scope="col" width="8%">Giới hạn SD</th>
                            <th scope="col" width="8%">Đã sử dụng</th>
                            <th scope="col" width="10%">Trạng thái TT</th>
                            <th scope="col" width="20%">Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id_dich_vu_dat'] ?></td>
                        <td><?php echo $pnk['so_dien_thoai_khach_hang'] ?></td>
                        <td><?php echo $pnk['ten_dich_vu'] ?></td>
                        <td><?php echo number_format($pnk['don_gia'], 0, '.', '.'); ?></td>
                        <td><?php echo $pnk['ngay_dat'] ?></td>
                        <td><?php echo $pnk['gioi_han'] ?></td>
                        <td><?php echo $pnk['so_lan_su_dung'] ?></td>
                        <td><?php if( $pnk['trang_thai']==0)
                            {
                                echo "Chưa thanh toán";
                            } else{
                                 echo "Đã thanh toán";
                            }
                        ?></td>
                        <td>
                            <!-- hủy -->
                            <button id="deleteBtn" class="btn btn-danger m-2"
                            data-id="<?php echo $pnk['id_dich_vu_dat']?>"><i class="bi bi-trash-fill"></i> Hủy</i></button>
                            <!-- checkin -->
                            <!--  -->
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
        
        $(function(){
            //button select all or cancel
            $("#select-all").click(function () {
                var all = $("input.select-all")[0];
                all.checked = !all.checked
                var checked = all.checked;
                $("input.select-item").each(function (index,item) {
                    item.checked = checked;
                });
            });
            //column checkbox select all or cancel
            $("input.select-all").click(function () {
                var checked = this.checked;
                $("input.select-item").each(function (index,item) {
                    item.checked = checked;
                });
            });
            //check selected items
            $("input.select-item").click(function () {
                var checked = this.checked;
                var all = $("input.select-all")[0];
                var total = $("input.select-item").length;
                var len = $("input.select-item:checked:checked").length;
                all.checked = len===total;
            });
        
        });

        ///
        $(document).ready(function () {
            // Xử lý sự kiện click nút tìm kiếm
            $('#searchBtn').click(function() {
                var search = $('#search').val(); // Lấy giá trị tìm kiếm từ input
                var loai_dich_vu = $("#loaiDV").val() // lấy giá trị select loại dịch vụ
                $.ajax({
                    url: 'inc/search_dichvu_dat.php', // Đường dẫn tới file PHP xử lý tìm kiếm
                    method: 'GET',
                    data: { 
                        search: search,
                        loai_dich_vu : loai_dich_vu
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

        /// TEST DISPLAY GIÁ
        $(document).ready(function () {
            // Hàm tính toán tổng giá
            function calculateTotalPrice() {
                let totalPrice = 0;

                // Duyệt qua các checkbox được chọn
                $("#resultTable input[type=checkbox]:checked").each(function () {
                    let row = $(this).closest("tr")[0]; // Lấy hàng chứa checkbox
                    let priceText = $(row).find('td:nth-child(4)').text(); // Lấy giá từ cột thứ 4
                    let price = parseFloat(priceText.replace(/\./g, '').trim()); // Xóa dấu chấm và đơn vị tiền tệ

                    if (!isNaN(price)) {
                        totalPrice += price;
                    } else {
                        console.error("Không thể đọc giá trị ở hàng:", row);
                    }
                });

                // Giảm 10% giá trị tổng
                totalPrice = totalPrice * 1;
                // Kiểm tra xem tổng giá có hợp lệ không
                if (isNaN(totalPrice) || totalPrice <= 0) {
                    console.error("Tổng giá không hợp lệ:", totalPrice);
                    $("#gia").val(""); // Nếu không hợp lệ, để trống ô input
                    return;
                }
                //
                let formattedPrice = totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Thêm dấu chấm phân cách hàng nghìn
                // Gán kết quả vào ô input có ID là 'gia', định dạng giá trị thành tiền Việt Nam
                // $("#gia").val(totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                 $("#gia").val(totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            }

            // Gán sự kiện 'change' vào tất cả checkbox trong bảng kết quả
            $(document).on('change', "#resultTable input[type=checkbox]", calculateTotalPrice);

            // Gọi hàm một lần khi trang được tải để đảm bảo giá trị đúng nếu có checkbox đã được chọn
            calculateTotalPrice();
        });

        

$(document).ready(function () {
            $('#soDT').on('blur', function () {
                var soDT = $(this).val(); // Lấy giá trị số điện thoại từ input

                // Gọi Ajax để kiểm tra số điện thoại
                $.ajax({
                    url: 'inc/check_sdt_email-dvdat.php', // Đường dẫn tới file PHP xử lý
                    method: 'POST',
                    data: { soDT: soDT }, // Gửi số điện thoại tới server
                    success: function (response) {
                        try {
                            var result = JSON.parse(response);

                            if (result.status === 'success') {
                                // Hiển thị email khách hàng
                                $('#email').val(result.email);

                                // Làm sạch danh sách select trước khi thêm dữ liệu mới
                                $('#maPhong').empty();

                                // Thêm tùy chọn phòng vào select
                                if (result.phong && result.phong.length > 0) {
                                    result.phong.forEach(function (phong) {
                                        $('#maPhong').append('<option value="' + phong.id_phongdat + '">' + phong.so_phong + '</option>');
                                        
                                    });
                                } else {
                                    $('#maPhong').append('<option value="">Không có phòng nào</option>');
                                }
                            } else {
                                // Hiển thị lỗi nếu không tìm thấy
                                alert(result.message);
                                $('#soDT').val(''); // Xóa số điện thoại
                                $('#email').val(''); // Xóa email
                                $('#maPhong').empty().append('<option value="">Không có phòng nào</option>');
                            }
                        } catch (e) {
                            console.error('Lỗi khi xử lý phản hồi:', e);
                            alert('Có lỗi xảy ra trong quá trình kiểm tra số điện thoại.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Lỗi:', error);
                        alert('Không thể kết nối đến server.');
                    }
                });
            });
        });

// đặt dịch vụ
        $(function () {
            $("#addDichVuDat").click(function () {
                var sdt = $("#soDT").val();
                var gia = $("#gia").val().replace(/\./g, ''); // Loại bỏ dấu chấm khỏi giá
                var email = $("#email").val();
                var id_phong_dat = $('#maPhong').val();
                var trang_thai = $("#trangThai").val();
                var selectedIds = [];

                // Kiểm tra các giá trị trước khi gửi
                console.log("SĐT:", sdt);
                console.log("Email:", email);
                console.log("Mã phòng:", id_phong_dat);
                console.log("Trạng thái thanh toán:", trang_thai);
                console.log("Dịch vụ đã chọn:", selectedIds);

                // Lấy các ID dịch vụ từ checkbox đã chọn
                $("#Table1 input[type=checkbox]:checked").each(function () {
                    var row = $(this).closest("tr")[0];
                    var id = row.cells[1].innerHTML; // Lấy ID dịch vụ
                    selectedIds.push(id);
                });

                // Kiểm tra lại mảng selectedIds trước khi gửi
                console.log("Dịch vụ đã chọn sau khi xử lý:", selectedIds);

                $.ajax({
                    url: 'inc/add-dichvudat.php',
                    type: 'POST',
                    data: {
                        sdt: sdt,
                        gia: gia,
                        email: email,
                        id_phong_dat:id_phong_dat,
                        trang_thai: trang_thai,
                        selected_ids: selectedIds
                    },
                    success: function (response) {
                        try {
                            var result = JSON.parse(response);

                            if (result.success) {
                                alert(result.message); // Hiển thị thông báo thành công
                                location.reload(); // Reload trang
                            } else {
                                alert("Lỗi: " + result.message);
                            }
                        } catch (e) {
                            console.error("Lỗi khi parse JSON:", e);
                            alert("Có lỗi xảy ra khi xử lý phản hồi từ server.");
                        }
                    },
                    error: function (xhr, status, error) {
                        alert("Không thể kết nối đến server. Vui lòng thử lại sau.");
                    }
                });

                return false; // Ngăn hành động mặc định
            });
        });

        //lắng nghe sự kiện cho nút XÓA gói dịch vụ
        $(document).on('click', '#deleteBtn', function(e) {
                e.preventDefault();
                // Lấy mã kho từ thuộc tính data
                var id = $(this).data('id');
                // console.log(maKho)
                // Kiểm tra nếu người dùng chắc chắn muốn xóa
                var isConfirmed = confirm('Bạn có chắc chắn muốn hủy đặt?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/delete-dichvu-dat.php', // Đường dẫn tới file PHP xử lý xóa trên server
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
