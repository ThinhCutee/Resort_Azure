<?php
include("./config/connect.php");
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [];
if (!isset($user)) {
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set up New Password</title>
    <?php require('inc/links.php'); ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cssreset.css">
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
                        <a class="nav-link me-2" href="contact.php">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="about.php">Về Azure</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    if (isset($_POST['btnResetPass']) && isset($_REQUEST['email']) && isset($_REQUEST['token'])) {

        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $email_recovery = base64_decode(urldecode($_REQUEST['email']));
        $token_recovery = urldecode($_REQUEST['token']);

        $t_date = date('Y-m-d');

        $sql_recovery = "SELECT * FROM taikhoan WHERE email = '$email_recovery' AND token = '$token_recovery' AND token_created_at = '$t_date' LIMIT 1";

        $result_recovery = mysqli_query($conn, $sql_recovery);

        $row_recovery = mysqli_num_rows($result_recovery);

        $data = mysqli_fetch_assoc($result_recovery);

        if ($row_recovery == 1) {
            if ($new_password == $confirm_password) {
                $reset_password = updatePass($email_recovery, $new_password, $t_date);
                if ($reset_password) {
                    echo '
                    <div id="reset-success" class="alert alert-success  text-center" role="alert">
                        Đặt lại mật khẩu thành công.
                    </div>
                    ';
                    echo '<script>setTimeout(function(){ window.location.href = "index.php"; }, 900);</script>';
                    echo '<style>.container { animation: fadeOut 1000ms ease; }</style>';
                } else {
                    echo '
                <div id="reset-failure" class="alert alert-success  text-center" role="alert">
                    Đặt lại mật khẩu không thành công.
                </div>
                ';
                }
            }
        } else {
            echo '
                <div id="signup-failure" class="alert alert-danger  text-center" role="alert">
                    Email hoặc token không hợp lệ! Vui lòng thử lại.
                </div>';
        }
    }
    function updatePass($email, $password, $token_created_at)
    {
        global $conn;
        global $token_recovery;
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_pass = "UPDATE taikhoan SET mat_khau = '$new_password_hash', token_created_at = '$token_created_at' WHERE email = '$email' AND token = '$token_recovery'";
        $query_updatepass = mysqli_query($conn, $update_pass);
        return $query_updatepass;
    }
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Đặt lại mật khẩu</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return validateForm()">
                            <div class="form-group">
                                <label for="new_password">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <div id="new_password_error" class="error-message">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Nhập lại mật khẩu mới</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <div id="confirm_password_error" class="error-message"></div>
                            </div>
                            <button type="submit" class="btn btn-custom btn-block" name="btnResetPass">Cập Nhật</button>
                            <a href="index.php" class="btn btn-sm w-100 btn-outline-dark mt-2">Thoát</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            var newPassword = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var newPasswordError = document.getElementById("new_password_error");
            var confirmPasswordError = document.getElementById("confirm_password_error");
            var isValid = true;

            newPasswordError.innerHTML = "";
            confirmPasswordError.innerHTML = "";

            if (newPassword.length < 8) {
                newPasswordError.innerHTML = "Mật khẩu ít nhất 8 kí tự.";
                isValid = false;
            }
            if (newPassword !== confirmPassword) {
                confirmPasswordError.innerHTML = "Mật khẩu nhập vào phải giống nhau!";
                isValid = false;
            }
            return isValid;
        }
        var resetSusscess = document.getElementById('reset-success');
        resetSusscess.style.display = 'block';
        setTimeout(function() {
            resetSusscess.style.display = 'none';
        }, 1000);
        var resetFailure = document.getElementById('reset-failure');
        resetFailure.style.display = 'block';
        setTimeout(function() {
            resetFailure.style.display = 'none';
        }, 1000);
    </script>

</body>

</html>