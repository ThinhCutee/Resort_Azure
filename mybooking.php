<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking</title>
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
    function getDetailDV($id_dichvu)
    {
        global $conn;
        $query_dich_vu = "
        SELECT DISTINCT
            dv.ten_dich_vu, dv.hinh_anh, dv.mo_ta, dv.id,
            CASE 
                WHEN EXISTS (
                    SELECT 1
                    FROM danhgia dg
                    WHERE dg.id_phong_dat = $id_dichvu AND dg.id_dich_vu = dv.id
                ) THEN NULL
                ELSE dv.don_gia
            END AS gia_dich_vu_hien_thi
        FROM dichvu dv
        LEFT JOIN dichvudat dvd ON dvd.id_dich_vu = dv.id AND dvd.id_phong_dat = $id_dichvu
        WHERE dvd.id_phong_dat = $id_dichvu
        AND NOT EXISTS (
            SELECT 1
            FROM danhgia dg
            WHERE dg.id_phong_dat = $id_dichvu AND dg.id_dich_vu = dv.id)";
        $result_dich_vu = mysqli_query($conn, $query_dich_vu);
        return $result_dich_vu;
    }
    function deletePhong($id_phieu)
    {
        global $conn;

        $sql_get_nguoi_bay = "SELECT id FROM thongtinnguoibay WHERE id IN (SELECT id_nguoi_bay FROM vemaybay WHERE id_phong_dat = '$id_phieu')";
        $result_get_nguoi_bay = mysqli_query($conn, $sql_get_nguoi_bay);

        if ($result_get_nguoi_bay) {
            while ($row = mysqli_fetch_assoc($result_get_nguoi_bay)) {
                $id_nguoi_bay = $row['id'];
                $sql_delete_nguoi_bay = "DELETE FROM thongtinnguoibay WHERE id = $id_nguoi_bay";
                $result_delete_nguoi_bay = mysqli_query($conn, $sql_delete_nguoi_bay);
                if (!$result_delete_nguoi_bay) {
                    return false;
                }
            }
        }

        $sql_huy_chuyen_bay = "DELETE FROM vemaybay WHERE id_phong_dat = '$id_phieu'";
        $result_huy_chuyen_bay = mysqli_query($conn, $sql_huy_chuyen_bay);

        $sql_huy_phong_dat = "DELETE FROM phongdat WHERE id = '$id_phieu'";
        $result_huy_phong_dat = mysqli_query($conn, $sql_huy_phong_dat);

        if ($result_huy_phong_dat && $result_huy_chuyen_bay) {
            return true;
        } else {
            return false;
        }
    }

    function getAllDichVu($id_khach_hang)
    {
        global $conn;
        $sql_mybooking = "SELECT pd.id as id, p.id as id_phong , p.ten_phong as ten_phong, pd.tong_tien as tong_tien, pd.trang_thai as trang_thai, pd.is_check_out as is_check_out
                        FROM phongdat pd JOIN phong p 
                        ON pd.id_phong = p.id
                        WHERE pd.id_khach_hang = '$id_khach_hang'";
        $result = mysqli_query($conn, $sql_mybooking);
        return $result;
    }
    if (isset($_REQUEST['id_phong_dat'])) {
        $id_phong_dat = base64_decode(urldecode($_REQUEST['id_phong_dat']));
        $result_huy_phong_dat = deletePhong($id_phong_dat);
        if ($result_huy_phong_dat) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Hủy Phòng Thành Công!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                }, 2000);
                            }
                        });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hủy Phòng Thất Bại!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                setTimeout(() => {
                                    Swal.close();
                                }, 2000);
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
                <h2 class="fw-bold">My Booking</h2>
                <div style="font-size: 14px;">
                    <a href="mybooking.php" class="text-secondary text-decoration-none;">My Booking</a>
                    <span class="text-secondary"> > </span>
                    <a href="mybooking.php" class="text-secondary text-decoration-none;">List Booking</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <table class="table table-bordered border-primary mb-0">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên Phòng</th>
                                <th class="text-center">Tổng Tiền</th>
                                <th class="text-center">Thanh Toán</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                            <?php
                            $i = 0;
                            $id_user = $user['id'];
                            $result = getAllDichVu($id_user);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $i++;
                                    $id_phong_dat = $row['id'];
                                    $id_phong_dat_encoded = urlencode(base64_encode($id_phong_dat));
                                    $is_check_out = $row['is_check_out'];
                                    $id_phong = $row['id_phong'];
                                    $id_phong_encoded = urlencode(base64_encode($id_phong));
                                    $count_dg = 0;
                                    $count_dv_dg = getDetailDV($row['id']);
                                    if ($count_dv_dg) {
                                        $row_dv_dg = mysqli_num_rows($count_dv_dg);
                                        if ($row_dv_dg == 0) {
                                            $count_dg = 0;
                                        } else {
                                            $count_dg = $row_dv_dg;
                                        }
                                    }
                                    echo '<tr>
                                        <td class="text-center">' . $i . '</td>
                                        <td>' . $row['ten_phong'] . '</td>
                                        <td class="text-center">' . number_format($row['tong_tien'], 0, '.', '.') . ' VNĐ</td>
                                        ';
                                    if ($row['trang_thai'] == 0) {
                                        echo '
                                        <td class="text-center">Chưa Thanh Toán</td>
                                        <td class="text-center">
                                        <a href="mybooking.php?id_phong_dat=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Huỷ Phòng</a>
                                        <a href="detailbooking.php?id_phong_dat=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Xem</a>
                                        <a href="xulythanhtoan.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Thanh Toán</a>
                                        </td>';
                                    } elseif ($count_dg != 0) {
                                        echo '
                                        <td class="text-center">Đã Thanh Toán</td>
                                        <td class="text-center">
                                        <a href="danhgia.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Đánh Giá</a>
                                        <a href="detailbooking.php?id_phong_dat=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Xem</a>
                                        ';
                                        if ($is_check_out == 0) {
                                            echo '<a href="datthemdichvu.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Đặt Thêm Dịch Vụ</a>';
                                            echo '<a href="checkout.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Check-out</a>';
                                        }
                                        echo '</td>';
                                    } else {
                                        echo '
                                        <td class="text-center">Đã Thanh Toán</td>
                                        <td class="text-center">
                                        <a href="detailbooking.php?id_phong_dat=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Xem</a>
                                        ';
                                        if ($is_check_out == 0) {
                                            echo '<a href="datthemdichvu.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Đặt Thêm Dịch Vụ</a>';
                                            echo '<a href="checkout.php?id=' . $id_phong_dat_encoded . '" class="btn btn-sm w-40 btn-outline-dark">Check-out</a>';
                                        }
                                        echo '</td>';
                                    }
                                    echo '</tr>';
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