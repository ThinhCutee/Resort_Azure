<?php
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
    }
    include_once('inc/database.php');
    $gdv = show("SELECT * FROM goidichvu");
    $dv = show("SELECT * FROM dichvu");
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
                <h1 class="h2">Quản lý gói dịch vụ</h1>
                
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
                                    <label for="tenGoi" class="form-label">Tên gói dịch vụ</label>
                                    <input type="text" class="form-control" id="tenGoi" name="tenGoi" placeholder="Nhập tên gói dịch vụ">
                                </div>
                                <div class="col-md-6">
                                    <label for="gia" class="form-label">Giá</label>
                                    <input type="text" class="form-control" id="gia" name="gia" placeholder="Chọn dịch vụ để tính giá" readonly>
                                </div>
                            </div>

                            <!-- Search section -->
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

                            <!-- Additional fields -->
                            <div class="row mb-3 mt-5">
                                <div class="col-md-12 mt-5">
                                    <label for="moTa" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="moTa" name="moTa" placeholder="Nhập mô tả"></textarea>
                                </div>
                            </div>
                            <button type="submit" id="addDichVu" class="btn btn-primary mt-3">Thêm gói dịch vụ</button>
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
                <h2>Danh sách các gói dịch vụ</h2>
                <table class="table table-striped table-sm table-bordered pt-6">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">Mã gói</th>
                            <th scope="col" width="20%">Tên gói dịch vụ</th>
                            <th scope="col" width="10%">Giá</th>
                            <th scope="col" width="30%">Mô tả</th>
                            <th scope="col" width="20%">Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php foreach ($gdv as $pnk) { ?>
                    <tr>
                        <td><?php echo $pnk['id'] ?></td>
                        <td><?php echo $pnk['ten_goi_dich_vu'] ?></td>
                        <td><?php echo number_format($pnk['gia'], 0, '.', '.'); ?></td>
                        <td><?php echo $pnk['mo_ta'] ?></td>
                        <td>
                            <button class="btn btn-primary updateBtn"
                                    data-id="<?php echo $pnk['id'] ?>"
                                    data-moTa="<?php echo $pnk['mo_ta'] ?>"
                                    data-tenGoi="<?php echo $pnk['ten_goi_dich_vu'] ?>"
                                    data-gia="<?php echo $pnk['gia'] ?>">
                                <i class="fa fa-pencil">Sửa</i></button>


                                    <!-- Modal Bootstrap -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa thông tin gói dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-form" action="inc/update-phong.php" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                        
                        <div class="col-md-6">
                            <label for="updateID" class="form-label">gói dịch vụ ID</label>
                            <input type="text" class="form-control" id="updateID" name="id" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateTenGoi" class="form-label">Tên gói dịch vụ</label>
                            <input type="text" class="form-control" id="updateTenGoi" name="tenGoi">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updateGia" class="form-label">Giá</label>
                            <input type="number" class="form-control" id="updateGia" name="gia" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="updateMoTaoTa" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="updateMoTa" name="moTa"></textarea>
                        </div>
                    </div>
                <!-- display dich vu -->
                    <div lass="row mb-3">
                        <div class="table-responsive" >
                            <!-- <h5>Danh sách các gói dịch vụ</h5> -->
                            <table class="table table-striped table-sm table-bordered pt-6">
                                <thead>
                                    <tr>
                                        <h5>Danh sách các dịch vụ</h5>
                                        <td scope="col" >#</td>
                                        <td scope="col" >Tên dịch vụ</td>
                                        <td scope="col" >Giá</td>
                                        <td scope="col" >Loại dịch vụ</td>
                                </thead>
                                <tbody id="dichVuList">
                                        <!-- Dữ liệu sẽ được thêm vào đây  bằng js -->
                                </tbody>
                            </table>
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
<button id="deleteGDV" class="btn btn-danger" data-id="<?php echo $pnk['id']?>"><i class="fa fa-trash">Xóa</i></button>
<button id="chiTietDV" class="btn btn-warning" data-id="<?php echo $pnk['id']?>"><i class="bi bi-ticket-detailed-fill"></i> Dịch vụ</i></button>

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
        // Lắng nghe sự kiện click cho các nút "Sửa"
            document.querySelectorAll('.updateBtn').forEach(function (button) {
                button.addEventListener('click', function () {
                    // Lấy dữ liệu từ các thuộc tính `data-*` trên nút
                    var goiDichVuId = button.getAttribute('data-id');
                    var tenGoi = button.getAttribute('data-tenGoi');
                    var gia = button.getAttribute('data-gia');
                    var moTa = button.getAttribute('data-moTa');

                    // Điền dữ liệu vào các input trong modal
                    document.getElementById('updateID').value = goiDichVuId;
                    document.getElementById('updateTenGoi').value = tenGoi;
                    document.getElementById('updateGia').value = gia;
                    document.getElementById('updateMoTa').value = moTa;

                    // Gửi AJAX để lấy danh sách dịch vụ
                    $.ajax({
                        url: 'inc/get_dichvu.php', // PHP file xử lý
                        type: 'POST',
                        data: { id_goi_dich_vu: goiDichVuId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Xóa dữ liệu cũ trong bảng dịch vụ
                                $('#dichVuList').empty();

                                // Thêm danh sách dịch vụ mới vào bảng
                                response.data.forEach(function (dichVu, index) {
                                    $('#dichVuList').append(`
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${dichVu.ten_dich_vu}</td>
                                            <td>${parseInt(dichVu.don_gia).toLocaleString()} ₫</td>
                                            <td>${dichVu.loai_dich_vu}</td>
                                        </tr>
                                    `);
                                });

                                // Hiển thị modal
                                var modal = new bootstrap.Modal(document.getElementById('myModal'));
                                modal.show();
                            } else {
                                alert(response.message || 'Không thể lấy danh sách dịch vụ.');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Đã xảy ra lỗi khi tải danh sách dịch vụ.');
                            console.error(error);
                        }
                    });
                });
            });


        // Lắng nghe sự kiện click cho nút "Cập Nhật"
        document.getElementById('updateUserBtn').addEventListener('click', function() {
            
            // Lấy dữ liệu từ các input trong modal
            var updateID = document.getElementById('updateID').value;
            var updateMoTa = document.getElementById('updateMoTa').value;
            var updateTenGoi = document.getElementById('updateTenGoi').value;
            var updateGia = document.getElementById('updateGia').value;

            
            // Tạo formData để gửi dữ liệu và file hình ảnh lên server
            var formData = new FormData();
            formData.append('id', updateID);
            formData.append('moTa', updateMoTa);
            formData.append('tenGoi', updateTenGoi);
            formData.append('gia', updateGia);

            // Gửi request Ajax
            $.ajax({
                url: 'inc/update-goidichvu.php',
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
                    alert('Update thành công !.');
                    location.reload();
                }
            });
        });


        //lắng nghe sự kiện cho nút XÓA gói dịch vụ
        $(document).on('click', '#deleteGDV', function(e) {
                e.preventDefault();
                // Lấy mã kho từ thuộc tính data
                var id = $(this).data('id');
                // console.log(maKho)
                // Kiểm tra nếu người dùng chắc chắn muốn xóa
                var isConfirmed = confirm('Bạn có chắc chắn muốn xóa?');

                if (isConfirmed && id) {
                    // Thực hiện Ajax request khi người dùng nhấp vào nút xóa
                    $.ajax({
                        url: 'inc/delete-goidichvu.php', // Đường dẫn tới file PHP xử lý xóa trên server
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
    
//         function searchServices() {
//     var search = document.getElementById("search").value; // Lấy giá trị từ ô tìm kiếm
    
//     // Gửi yêu cầu AJAX đến PHP để lấy dữ liệu
//     var xhr = new XMLHttpRequest();
//     xhr.open("GET", "search_dichvu.php?search=" + search, true);
//     xhr.onload = function() {
//         if (xhr.status === 200) {
//             document.getElementById("resultTable").innerHTML = xhr.responseText; // Hiển thị kết quả tìm kiếm
//         }
//     };
//     xhr.send();
// }
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
                totalPrice = totalPrice * 0.9;
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

////
     // Sự kiện checkbox lấy ID
    // $(function () {
    //     // Assign Click event to Button.
    //     $("#addDichVu").click(function () {
    //         var selectedRows = [];

    //         // Loop through all checked CheckBoxes in GridView.
    //         $("#Table1 input[type=checkbox]:checked").each(function () {
    //             var row = $(this).closest("tr")[0];
    //             var rowData = row.cells[1].innerHTML; // Get the data from the second cell (adjust as needed)
    //             selectedRows.push(rowData); // Add data to the array
    //         });

    //         // Display selected Row data in the console as an array.
    //         console.log(selectedRows);
    //         return false;
    //     });
    // });
    $(function () {
        $("#addDichVu").click(function () {
            var ten_goi_dich_vu = $("#tenGoi").val();
            var gia = $("#gia").val().replace(/\./g, '');;
            var mo_ta = $("#moTa").val();
            var selectedIds = [];
                    
            // Lấy các ID dịch vụ từ checkbox đã chọn
            $("#Table1 input[type=checkbox]:checked").each(function () {
                var row = $(this).closest("tr")[0];
                var id = row.cells[1].innerHTML;
                selectedIds.push(id);
            });

            $.ajax({
                url: 'inc/add-goidichvu1.php',
                type: 'POST',
                data: {
                    ten_goi_dich_vu: ten_goi_dich_vu,
                    gia: gia,
                    mo_ta: mo_ta,
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
                        alert("Vui lòng nhập đầy đủ thông tin trước khi thêm.");
                    }
                },
                error: function (xhr, status, error) {
                    alert("Không thể kết nối đến server. Vui lòng thử lại sau.");
                }
            });

            return false; // Ngăn hành động mặc định
        });


    });
    // Xử lý sự kiện click cho nút Chi tiết dịch vụ
    $(document).on('click', '#chiTietDV', function() {
            var id_goi_dich_vu = $(this).data('id');
            window.location.href = 'chitiet-dichvu.php?id_goi_dich_vu=' + id_goi_dich_vu;
        });
        // Xử lý sự kiện click cho nút Chi tiết dịch vụ
    $(document).on('click', '#chiTietDV1', function() {
            var id_goi_dich_vu = $(this).data('id');
            window.location.href = 'chitiet-dichvu.php?id_goi_dich_vu=' + id_goi_dich_vu;
        });
    });
</script>


</body>
</html>
