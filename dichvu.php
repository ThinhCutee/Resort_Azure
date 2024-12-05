<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
} else {
    $user = $_SESSION['user'];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in sử dụng dịch vụ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .selectable-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s ease;
        }

        .selectable-card:hover {
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand me-5 fw-bold fs-3" href="index.php">AZURE RESORT</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active me-2" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="rooms.php">Phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="services.php">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="vemaybay.php">Vé Máy Bay</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="contact.php">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="about.php">Về Azure</a>
                    </li>
                    <?php
                    if (isset($user)) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link me-2" href="logout.php">Đăng Xuất</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    include("./config/connect.php");
    ?>
    <?php
    if (isset($_REQUEST['id_dich_vu'])) {
        $id_dich_vu = base64_decode(urldecode($_REQUEST['id_dich_vu']));
        $_SESSION['id_dich_vu'] = $_REQUEST['id_dich_vu'];
    }
    if (isset($_REQUEST['btnLogin'])) {

        $email_login = $_POST['email'];
        $password_login = $_POST['password'];

        function check_tai_khoan($email)
        {
            global $conn;
            $sql = "SELECT * FROM taikhoan WHERE email = '$email'";
            $query = mysqli_query($conn, $sql);
            return $query;
        }
        $query = check_tai_khoan($email_login);
        $data = mysqli_fetch_assoc($query);

        function check_khach_hang($email)
        {
            global $conn;
            $sql_khach_hang = "SELECT * FROM khachhang WHERE email = '$email'";
            $query_khach_hang = mysqli_query($conn, $sql_khach_hang);
            return $query_khach_hang;
        }
        $query_khach_hang = check_khach_hang($email_login);
        $data_khach_hang = mysqli_fetch_assoc($query_khach_hang);

        if ($data) {
            if ($data['is_veryfied'] == 1) {
                if ($data['email'] == $email_login) {
                    $checkpass = password_verify($password_login, $data['mat_khau']);
                    if ($checkpass) {
                        $_SESSION['user'] = $data_khach_hang;
                        $_SESSION['success_message'] = 'Đăng nhập thành công!';
                    } else {
                        echo '
                        <div id="signup-failure" class="alert alert-danger  text-center" role="alert">
                            Mật khẩu không chính xác.
                        </div>
                        ';
                    }
                } else {
                    echo '
                    <div id="signup-failure" class="alert alert-danger  text-center" role="alert">
                        Email không chính xác.
                    </div>
                    ';
                }
            } else {
                echo '
                <div id="signup-failure" class="alert alert-danger  text-center" role="alert">
                    Email chưa được xác thực.
                </div>
                ';
            }
        } else {
            echo '
                <div id="signup-failure" class="alert alert-danger  text-center" role="alert">
                    Email không tồn tại.
                </div>
                ';
        }
    }
    if (isset($_REQUEST['xacnhan'])) {
        $id_phong_dat = $_REQUEST['chon_phong'];
        $id_phong_dat_decode = base64_decode(urldecode($id_phong_dat));
        $id_dich_vu = $_SESSION['id_dich_vu'];
        $id_user = $_SESSION['user']['id'];
        $sql_update_so_lan_su_dung_dich_vu = "UPDATE dichvudat SET so_lan_su_dung = so_lan_su_dung + 1 
                                              WHERE id_dich_vu = '$id_dich_vu' AND id_phong_dat = '$id_phong_dat_decode'";
        $query_update_so_lan_su_dung_dich_vu = mysqli_query($conn, $sql_update_so_lan_su_dung_dich_vu);
        if ($query_update_so_lan_su_dung_dich_vu) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var toastSuccess = new bootstrap.Toast(document.getElementById('toastSuccess'));
                        toastSuccess.show();
                        document.getElementById('form_dich_vu').style.display = 'none';
                        document.getElementById('thank-you-message').style.display = 'block';
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var toastFailure = new bootstrap.Toast(document.getElementById('toastFailure'));
                        toastFailure.show();
                    });
                  </script>";
        }
    }
    ?>
    <?php
    if (isset($_SESSION['success_message']) && isset($_SESSION['id_dich_vu'])) {
        $successMessage = $_SESSION['success_message'];
        $idDichVu = $_SESSION['id_dich_vu'];

        unset($_SESSION['success_message']);
        echo '
            <div id="signup-success" class="alert alert-success text-center" role="alert">
                ' . htmlspecialchars($successMessage) . '
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "http://localhost/Resort_Azure/dichvu.php?id_dich_vu=' . htmlspecialchars($idDichVu) . '";
                }, 100);
            </script>
        ';
        exit();
    }
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo '
                        <div class="card-header text-center">
                            <h2>Xin chào, ' . $_SESSION['user']['ten'] . '</h2>
                        </div>';
                    ?>
                        <form action="dichvu.php" method="post" id="form_dich_vu">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    if (isset($_SESSION['id_dich_vu'])) {
                                        $id_dich_vu = $_SESSION['id_dich_vu'];
                                        $id_user = $_SESSION['user']['id'];
                                        $sql_get_phong_dat = "SELECT pd.id as id_phong_dat, p.hinh_anh as hinh_anh, p.ten_phong, pd.ngay_dat_phong FROM phongdat pd JOIN dichvudat dvd on pd.id = dvd.id_phong_dat JOIN phong p on pd.id_phong = p.id
                                                                WHERE pd.id_khach_hang = '$id_user' AND is_check_out = 0 AND dvd.id_dich_vu = '$id_dich_vu'";
                                        $query_get_phong_dat = mysqli_query($conn, $sql_get_phong_dat);
                                        if (mysqli_num_rows($query_get_phong_dat) > 0) {
                                            while ($row_phong_dat = mysqli_fetch_assoc($query_get_phong_dat)) {
                                                $id_phong_dat = $row_phong_dat['id_phong_dat'];
                                                $id_phong_dat_encode = urlencode(base64_encode($id_phong_dat));
                                                $ten_phong = $row_phong_dat['ten_phong'];
                                                $ngay_dat = $row_phong_dat['ngay_dat_phong'];
                                                $anh_phong = $row_phong_dat['hinh_anh'];
                                                $timestamp = strtotime($ngay_dat);
                                                if ($timestamp) {
                                                    $formattedDate = date('d-m-Y', $timestamp);
                                                }
                                                echo '
                                                <div class="col-md-4 mb-3">
                                                    <div class="card selectable-card" data-radio="' . $ten_phong . '">
                                                        <img src="./admin/uploads/' . $anh_phong . '" class="card-img-top" alt="Phòng ' . $id_phong_dat_encode . '" style="height: 150px; object-fit: cover">
                                                        <div class="card-body text-center">
                                                            <h6 class="card-title">Phòng ' . $ten_phong . '</h6>
                                                            <p class="card-text">Ngày đặt: ' . $formattedDate . '</p>
                                                            <input type="radio" name="chon_phong" value="' . $id_phong_dat_encode . '" class="form-check-input">
                                                        </div>
                                                    </div>
                                                </div>
                                                ';
                                            }
                                        } else {
                                            echo '
                                        <div class="col-md-12 mb-3">
                                            <div class="card selectable-card" data-radio="101">
                                                <img src="./uploads/error.jpg" class="card-img-top" alt="Phòng Trống" style="height: 150px; object-fit: cover">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Bạn chưa đặt dịch vụ này vui lòng liên hệ nhân viên hoặc đặt thêm dịch vụ.</h6>
                                                </div>
                                            </div>
                                        </div>
                                        ';
                                        }
                                    }
                                    ?>
                                    <button type="submit" id="confirmButton" class="btn btn-primary w-100 mt-3" name="xacnhan">Xác nhận</button>
                                </div>
                                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                                    <div id="toastSelection" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                Bạn đã chọn phòng <span id="selected-room"></span>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </div>

                                    <div id="toastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="d-flex">
                                            <div class="toast-body">
                                                Vui lòng chọn một phòng trước khi tiếp tục.
                                            </div>
                                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-center">Quý khách hàng vui lòng chọn phòng <br>muốn sử dụng dịch vụ</p>
                            </div>
                        </form>
                        <div id="thank-you-message" style="display: none; text-align: center; margin-top: 20px;">
                            <h4>Cảm ơn bạn đã sử dụng dịch vụ!</h4>
                            <p>Chúc bạn một ngày tốt lành.</p>
                        </div>
                        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                            <div id="toastSuccess" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 10px; right: 10px;">
                                <div class="toast-header">
                                    <strong class="me-auto">Thành công</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Bạn đã check-in thành công dịch vụ.
                                </div>
                            </div>

                            <div id="toastFailure" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 10px; right: 10px;">
                                <div class="toast-header">
                                    <strong class="me-auto">Thất bại</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Check-in dịch vụ thất bại. Vui lòng thử lại.
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo '
                        <div class="card-header text-center">
                            <h2>Check-in sử dụng dịch vụ</h2>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn w-100 btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Đăng nhập
                            </button>
                        </div>
                        ';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" method="post" onsubmit="return validateForm()" action="dichvu.php">
                        <div id="loginAlert" class="alert" style="display: none;" role="alert"></div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div id="emailError" class="error-message text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div id="passwordError" class="error-message text-danger"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="btnLogin">Đăng nhập</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    require('inc/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function validateForm() {
            var email = document.getElementById("email").value.trim();
            var password = document.getElementById("password").value.trim();
            var emailError = document.getElementById("emailError");
            var passwordError = document.getElementById("passwordError");
            var loginAlert = document.getElementById("loginAlert");
            var isValid = true;

            emailError.innerHTML = "";
            passwordError.innerHTML = "";
            loginAlert.style.display = "none";

            if (email.length < 8 || !email.includes("@")) {
                emailError.innerHTML = "Vui lòng nhập email hợp lệ.";
                isValid = false;
            }

            if (password.length < 8) {
                passwordError.innerHTML = "Mật khẩu phải có ít nhất 8 ký tự.";
                isValid = false;
            }

            if (!isValid) {
                loginAlert.style.display = "block";
                loginAlert.className = "alert alert-danger";
                loginAlert.innerHTML = "Thông tin đăng nhập không hợp lệ. Vui lòng kiểm tra lại.";
            }
            return isValid;
        }
        var signupSuccess = document.getElementById('signup-success');
        signupSuccess.style.display = 'block';
        setTimeout(function() {
            signupSuccess.style.display = 'none';
        }, 1000);
        var signupFailure = document.getElementById('signup-failure');
        signupFailure.style.display = 'block';
        setTimeout(function() {
            signupFailure.style.display = 'none';
        }, 1000);
    </script>
    <script>
        document.querySelectorAll('.selectable-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.selectable-card input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                const roomNumber = this.getAttribute('data-radio');
                document.getElementById('selected-room').textContent = roomNumber;
                const toastSuccess = new bootstrap.Toast(document.getElementById('toastSelection'));
                toastSuccess.show();
            });
        });

        function validateRadioSelection() {
            const selectedRadio = document.querySelector('.selectable-card input[type="radio"]:checked');

            if (!selectedRadio) {
                const toastError = new bootstrap.Toast(document.getElementById('toastError'));
                toastError.show();
                return false;
            }

            return true;
        }

        document.getElementById('confirmButton').addEventListener('click', function(event) {
            const isValid = validateRadioSelection();

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>