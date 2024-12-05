<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chuyến bay</title>
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
    function updateAndDeleteTicket($conn, $id_ve_may_bay)
    {
        $stmt1 = $conn->prepare("SELECT * FROM vemaybay WHERE id = ?");
        $stmt1->bind_param("i", $id_ve_may_bay);
        $stmt1->execute();
        $result_id_ghe_chuyenbay = $stmt1->get_result();
        $row_id_ghe_chuyenbay = $result_id_ghe_chuyenbay->fetch_assoc();

        if (!$row_id_ghe_chuyenbay) {
            return false;
        }

        $id_ghe_chuyenbay = $row_id_ghe_chuyenbay['id_ghe_chuyenbay'];
        $id_phong_dat = $row_id_ghe_chuyenbay['id_phong_dat'];
        $id_nguoi_bay = $row_id_ghe_chuyenbay['id_nguoi_bay'];

        $stmt2 = $conn->prepare("SELECT g.gia FROM vemaybay vmb 
                                  JOIN ghe_chuyenbay gcb ON vmb.id_ghe_chuyenbay = gcb.id 
                                  JOIN ghe g ON g.id = gcb.id_ghe 
                                  WHERE vmb.id_ghe_chuyenbay = ?");
        $stmt2->bind_param("i", $id_ghe_chuyenbay);
        $stmt2->execute();
        $result_tt = $stmt2->get_result();
        $row_tt = $result_tt->fetch_assoc();

        if (!$row_tt) {
            return false;
        }

        $gia = $row_tt['gia'];

        $stmt3 = $conn->prepare("SELECT tong_tien, id_uu_dai FROM phongdat WHERE id = ?");
        $stmt3->bind_param("i", $id_phong_dat);
        $stmt3->execute();
        $result_ttt = $stmt3->get_result();
        $row_ttt = $result_ttt->fetch_assoc();

        if (!$row_ttt) {
            return false;
        }

        $tong_tien_hien_tai = $row_ttt['tong_tien'];
        $id_uu_dai = $row_ttt['id_uu_dai'];

        $giam_gia_phan_tram = 0;
        if ($id_uu_dai) {
            $sql_uudai = "SELECT gia_giam FROM uudai WHERE id = '$id_uu_dai'";
            $query_uudai = mysqli_query($conn, $sql_uudai);
            $row_uudai = mysqli_fetch_assoc($query_uudai);
            $giam_gia_phan_tram = $row_uudai['gia_giam'];
        }

        $tong_tien_goc = $tong_tien_hien_tai / (1 - $giam_gia_phan_tram / 100);

        $tong_tien_update = $tong_tien_goc - $gia;

        $tong_tien_update_v1 = $tong_tien_update - ($tong_tien_update * ($giam_gia_phan_tram / 100));
        $tong_tien_update_v1 = round($tong_tien_update_v1, 2);
        if ($tong_tien_update_v1 < 0) {
            $tong_tien_update_v1 = 0;
        }

        $stmt4 = $conn->prepare("UPDATE phongdat SET tong_tien = ? WHERE id = ?");
        $stmt4->bind_param("di", $tong_tien_update_v1, $id_phong_dat);
        $result_update_tong_tien = $stmt4->execute();

        $stmt5 = $conn->prepare("DELETE FROM thongtinnguoibay WHERE id = ?");
        $stmt5->bind_param("i", $id_nguoi_bay);
        $result_delete_nguoi_bay = $stmt5->execute();

        $stmt6 = $conn->prepare("DELETE FROM vemaybay WHERE id = ?");
        $stmt6->bind_param("i", $id_ve_may_bay);
        $result_delete = $stmt6->execute();

        if ($result_update_tong_tien && $result_delete_nguoi_bay && $result_delete) {
            return true;
        } else {
            return false;
        }
    }

    if (isset($_REQUEST['id'])) {
        $id_ve_may_bay = base64_decode(urldecode($_REQUEST['id']));
        $result = updateAndDeleteTicket($conn, $id_ve_may_bay);
        if ($result) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Hủy Chuyến Bay Thành Công!',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                    window.location.href = 'thongtinchuyenbay.php';
                                }, 1500);
                            }
                        });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hủy Chuyến Bay Thất Bại!',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                    window.location.href = 'thongtinchuyenbay.php';
                                }, 1500);
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
                <h2 class="fw-bold">Thông Tin Chuyến Bay</h2>
                <div style="font-size: 14px;">
                    <a href="thongtinchuyenbay.php" class="text-secondary text-decoration-none;">Thông Tin Chuyến Bay</a>
                    <span class="text-secondary"> > </span>
                    <a href="thongtinchuyenbay.php" class="text-secondary text-decoration-none;">Danh Sách Chuyến Bay</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <table class="table table-bordered border-primary mb-0">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Chuyến Bay</th>
                                <th class="text-center">Giá Vé</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                            <?php
                            $i = 0;
                            $id_user = $user['id'];
                            $sql = "SELECT * FROM vemaybay WHERE id_khach_hang = '$id_user'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $i++;
                                    $id_ve_may_bay = $row['id'];
                                    $id_ve_may_bay_encoded = urlencode(base64_encode($id_ve_may_bay));
                                    $id_ghe_chuyenbay = $row['id_ghe_chuyenbay'];
                                    $sql_tt = "SELECT cb.id_san_bay_xuat_phat, cb.id_san_bay_den, sbxp.dia_diem AS ten_san_bay_xuat_phat, sbd.dia_diem AS ten_san_bay_den, g.gia FROM vemaybay vmb JOIN ghe_chuyenbay gcb on vmb.id_ghe_chuyenbay = gcb.id join chuyenbay cb on gcb.id_chuyenbay = cb.id join ghe g on g.id = gcb.id_ghe join sanbay sbxp on sbxp.id = cb.id_san_bay_xuat_phat join sanbay sbd on sbd.id = cb.id_san_bay_den where vmb.id_ghe_chuyenbay = '$id_ghe_chuyenbay'";
                                    $result_tt = mysqli_query($conn, $sql_tt);
                                    $row_tt = mysqli_fetch_assoc($result_tt);
                                    $row_tt_chuyen_di = $row_tt['id_san_bay_xuat_phat'];
                                    if ($row_tt_chuyen_di != "CXR") {
                                        $dia_diem = $row_tt['dia_diem'] . " - " . $row_tt['dia_diem'];
                                        $chuyen_di = "Chuyến bay đi từ " . $row_tt['ten_san_bay_xuat_phat'] . " đến " . $row_tt['ten_san_bay_den'];
                                        $gia_bay_di = $row_tt['gia'];
                                    } else {
                                        $chuyen_di = "Chuyến bay đi từ " . $row_tt['ten_san_bay_xuat_phat'] . " đến " . $row_tt['ten_san_bay_den'];
                                        $gia_bay_di = $row_tt['gia'];
                                    }
                                    echo '<tr>
                                        <td class="text-center">' . $i . '</td>
                                        <td>' . $chuyen_di . '</td>
                                        <td class="text-center">' . number_format($gia_bay_di, 0, '.', '.') . ' VNĐ</td>
                                        <td class="text-center">
                                        <a href="thongtinchuyenbay.php?id=' . $id_ve_may_bay_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Hủy chuyến bay</a>
                                        </td>
                                        </tr>';
                                }
                            }
                            ?>
                        </table>
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
</body>

</html>