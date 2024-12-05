<?php
session_start();
include('./config/connect.php');

if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập.");
    window.location.href = "index.php";
    </script>';
    exit;
}
$user = $_SESSION['user'];


$id_phong_dat = $_SESSION['id_phong_dat'];
$id_phong_dat_encode = urlencode(base64_encode($id_phong_dat));
if (isset($_SESSION['tong_tien_check_out'])) {
    $tong_tien_check_out = $_SESSION['tong_tien_check_out'];
}
$id_check_out = $_SESSION['id_phong_dat_check_out'];
if (isset($_GET['resultCode'])) {
    $result_code = $_GET['resultCode'];
    switch ($result_code) {
        case '0':
            $sql_update_thanh_toan = "UPDATE phongdat SET trang_thai = '1' WHERE id = '$id_phong_dat'";
            $sql_update_dich_vu = "UPDATE dichvudat SET trang_thai = '1' WHERE id_phong_dat = '$id_phong_dat'";
            if (isset($_SESSION['tong_tien_check_out']) && isset($_SESSION['id_phong_dat_check_out'])) {
                $sql_update_tong_tien = "UPDATE phongdat SET tong_tien = '$tong_tien_check_out' WHERE id = '$id_phong_dat'";
                $result_update_tong_tien = mysqli_query($conn, $sql_update_tong_tien);
                $sql_check_out = "UPDATE phongdat SET is_check_out = 1 WHERE id = '$id_phong_dat'";
                $query_check_out = mysqli_query($conn, $sql_check_out);
                unset($_SESSION['tong_tien_check_out']);
                unset($_SESSION['id_phong_dat_check_out']);
            }
            $result_update_dich_vu = mysqli_query($conn, $sql_update_dich_vu);
            $result_update_phong = mysqli_query($conn, $sql_update_thanh_toan);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = 'detailbooking.php?id_phong_dat=$id_phong_dat_encode';
                        }
                    });
                });
            </script>";
            break;
        case '1006':
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thanh toán thất bại!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = 'mybooking.php';
                        }
                    });
                });
            </script>";
            break;
        default:
            break;
    }
} elseif (isset($_GET['vnp_ResponseCode'])) {
    $vnp_ResponseCode = $_GET['vnp_ResponseCode'];
    switch ($vnp_ResponseCode) {
        case '00':
            $sql_update_thanh_toan = "UPDATE phongdat SET trang_thai = '1' WHERE id = '$id_phong_dat'";
            $sql_update_dich_vu = "UPDATE dichvudat SET trang_thai = '1' WHERE id_phong_dat = '$id_phong_dat'";
            if (isset($_SESSION['tong_tien_check_out']) && isset($_SESSION['id_phong_dat_check_out'])) {
                $sql_update_tong_tien = "UPDATE phongdat SET tong_tien = '$tong_tien_check_out' WHERE id = '$id_phong_dat'";
                $result_update_tong_tien = mysqli_query($conn, $sql_update_tong_tien);
                $sql_check_out = "UPDATE phongdat SET is_check_out = 1 WHERE id = '$id_phong_dat'";
                $query_check_out = mysqli_query($conn, $sql_check_out);
                unset($_SESSION['tong_tien_check_out']);
                unset($_SESSION['id_phong_dat_check_out']);
            }
            $result_update_dich_vu = mysqli_query($conn, $sql_update_dich_vu);
            $result_update_phong = mysqli_query($conn, $sql_update_thanh_toan);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = 'detailbooking.php?id_phong_dat=$id_phong_dat_encode';
                        }
                    });
                });
            </script>";
            break;
        case '24':
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thanh toán thất bại',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                window.location.href = 'mybooking.php';
                            }
                        });
                    });
                </script>";
            break;
        default:
            break;
    }
} else {
    echo "Error";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>
</body>

</html>