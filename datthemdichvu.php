<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Thêm Dịch Vụ</title>
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.css">
    <script>
        $(document).ready(function() {
            $(".chzn-select").chosen({
                width: "100%",
                no_results_text: "Không tìm thấy dịch vụ"
            });
        });
    </script>
</head>

<body>
    <?php
    require('inc/header.php');
    ?>
    <?php
    include("./config/connect.php");
    if (!isset($_SESSION['user'])) {
        echo '<script>
    alert("Vui lòng đăng nhập.");
    window.location.href = "index.php";
    </script>';
        exit;
    }
    $user = $_SESSION['user'];
    ?>
    <?php
    function insertDichVu($id_phong_dat, $id_dich_vu)
    {
        global $conn;
        $sql = "INSERT INTO dichvudat (id_phong_dat, id_dich_vu, trang_thai, so_lan_su_dung) VALUES ($id_phong_dat, $id_dich_vu,0,0)";
        return mysqli_query($conn, $sql);
    }
    if (isset($_REQUEST['datthem'])) {
        $dichvu = $_REQUEST['dichvu'];
        $id_phong_dat = $_REQUEST['id_phong_dat'];
        if (isset($dichvu) && !empty($dichvu) && is_array($dichvu)) {
            foreach ($dichvu as $key => $value) {
                $insert_dichvu = insertDichVu($id_phong_dat, $value);
            }
        }
        if ($insert_dichvu) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đặt Dịch Vụ Thành Công!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                    window.location.href = 'mybooking.php';
                                }, 100);
                            }
                        });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Đặt Dịch Vụ Phòng Thất Bại!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                    window.location.href = 'mybooking.php';
                                }, 100);
                            }
                        });
                    });
                </script>";
        }
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Đặt Thêm Dịch Vụ</h2>
                <div style="font-size: 14px;">
                    <a href="mybooking.php" class="text-secondary text-decoration-none;">My Booking</a>
                    <span class="text-secondary"> > </span>
                    <a href="datthemdichvu.php" class="text-secondary text-decoration-none;">Đặt Thêm Dịch Vụ</a>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <table class="table table-hover table-bordered mb-0">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên Dịch Vụ</th>
                                <th class="text-center">Số Lượng</th>
                                <th class="text-center">Đơn Giá</th>
                                <th class="text-center">Tổng</th>
                                <th class="text-center">Trạng Thái</th>
                            </tr>
                            <?php
                            $i = 0;
                            $booked_services = [];
                            if (isset($_REQUEST['id'])) {
                                $id_phong_dat = base64_decode(urldecode($_REQUEST['id']));
                                $sql = "SELECT * FROM dichvudat dvd join dichvu dv on dvd.id_dich_vu = dv.id WHERE id_phong_dat = $id_phong_dat";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $i++;
                                        $booked_services[] = $row['id_dich_vu'];
                                        $trang_thai = $row['trang_thai'] == 0 ? 'Chưa Thanh Toán' : 'Đã Thanh Toán';
                                        echo '<tr>
                                        <td class="text-center">' . $i . '</td>
                                        <td>' . $row['ten_dich_vu'] . '</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">' . number_format($row['don_gia'], 0, ',', '.') . ' VNĐ</td>
                                        <td class="text-center">' . number_format($row['don_gia'], 0, ',', '.') . ' VNĐ</td>
                                        <td>' . $trang_thai . '</td>
                                    </tr>';
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <form action="datthemdichvu.php" method="post">
                                <div class="col-md-12">
                                    <label class="form-label">Dịch Vụ</label>
                                    <?php
                                    $query_dichvu = "SELECT * FROM dichvu";
                                    $result_dichvu = mysqli_query($conn, $query_dichvu);
                                    if (mysqli_num_rows($result_dichvu) > 0) {
                                        echo '<select class="chzn-select" multiple="true" name="dichvu[]" id="dichVu">';
                                        while ($rowdv = mysqli_fetch_assoc($result_dichvu)) {
                                            if (!in_array($rowdv['id'], $booked_services)) {
                                                echo '<option value="' . $rowdv['id'] . '">' . $rowdv['ten_dich_vu'] . '</option>';
                                            }
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                    <input type="hidden" name="id_phong_dat" value="<?php echo $id_phong_dat; ?>">
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <button type="submit" class="btn w-100 btn-outline-dark mb-2" name="datthem">Đặt Thêm Dịch Vụ</button>
                                    <a href="mybooking.php" class="btn btn-sm w-100 btn-outline-dark">Quay Lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        require('inc/footer.php');
        ?>
        <!-- Chatra {literal} -->
        <script>
            (function(d, w, c) {
                w.ChatraID = 'CBQ3fukS8PFGnSbiW';
                var s = d.createElement('script');
                w[c] = w[c] || function() {
                    (w[c].q = w[c].q || []).push(arguments);
                };
                s.async = true;
                s.src = 'https://call.chatra.io/chatra.js';
                if (d.head) d.head.appendChild(s);
            })(document, window, 'Chatra');
        </script>
        <!-- /Chatra {/literal} -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/3.0.1/luxon.min.js"></script>

</body>

</html>