<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <?php require('inc/links.php'); ?>
</head>

<body>
    <?php
    require('inc/header.php');
    ?>
    <?php
    if (isset($user['id_tai_khoan'])) {
        function getPassword($id_user, $oldpassword)
        {
            global $conn;
            $sql_get_password = "SELECT * FROM taikhoan WHERE id = '$id_user'";
            $result_get_password = mysqli_query($conn, $sql_get_password);
            $password_old = mysqli_fetch_assoc($result_get_password);
            $decode_password = password_verify($oldpassword, $password_old['mat_khau']);
            return $decode_password;
        }
        function updatePassword($id_user, $newpassword)
        {
            global $conn;
            $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
            $sql_update_password = "UPDATE taikhoan SET mat_khau = '$newpassword' WHERE id = '$id_user'";
            $result_update_password = mysqli_query($conn, $sql_update_password);
            return $result_update_password;
        }
        if (isset($_POST['btnChange'])) {

            $passwordold = $_POST['passwordold'];
            $passwordnew = $_POST['passwordnew'];
            $repasswordnew = $_POST['repasswordnew'];

            $password = getPassword($user['id_tai_khoan'], $passwordold);

            if ($password != $passwordold) {
                echo '
                    <div id="update-user-failure" class="alert alert-danger text-center" role="alert">
                        Mật khẩu cũ không chính xác. Vui lòng nhập lại.
                    </div>
                    ';
                echo '<script>setTimeout(function(){ window.location.href = "changepassword.php"; }, 900);</script>';
            } elseif ($passwordnew != $repasswordnew) {
                echo '
                    <div id="update-user-failure" class="alert alert-danger text-center" role="alert">
                        Mật khẩu mới phải giống nhau.
                    </div>
                    ';
                echo '<script>setTimeout(function(){ window.location.href = "changepassword.php"; }, 900);</script>';
            } else {
                $update_password = updatePassword($user['id_tai_khoan'], $passwordnew);
                if ($update_password) {
                    echo '
                    <div id="update-user-success" class="alert alert-success text-center" role="alert">
                        Đổi mật khẩu thành công.
                    </div>
                    ';
                    echo '<script>setTimeout(function(){ window.location.href = "changepassword.php"; }, 900);</script>';
                }
            }
        }
    ?>
        <div class="container">
            <div class="row">
                <div class="col-12 my-5 px-4">
                    <h2 class="fw-bold">Đổi Mật Khẩu</h2>
                    <div style="font-size: 14px;">
                        <a href="index.php" class="text-secondary text-decoration-none;">Home</a>
                        <span class="text-secondary"> > </span>
                        <a href="#" class="text-secondary text-decoration-none;">Đổi Mật Khẩu</a>
                    </div>
                </div>
                <div class="col-12 my-5 px-4" style="margin-top: -20px !important;">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                        <form method="post" onsubmit="return validateForm()">
                            <h5 class="mb-3 fw-bold">Đổi Mật Khẩu</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Mật khẩu cũ</label>
                                    <input type="password" class="form-control shadow-none" name="passwordold" id="passwordold">
                                    <div id="old_password_error" class="error-message">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control shadow-none" name="passwordnew" id="passwordnew">
                                    <div id="new_password_error" class="error-message">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control shadow-none" name="repasswordnew" id="repasswordnew">
                                    <div id="re_new_password_error" class="error-message">
                                    </div>
                                </div>
                            </div>
                            <center><button type="submit" class="btn text-white custom-bg shadow-none" name="btnChange">Đổi Mật Khẩu</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo "<script>window.location.href='index.php';</script>";
    }
    ?>
    <?php
    require('inc/footer.php');
    ?>
    <script>
        var signupSuccess = document.getElementById('update-user-success');
        signupSuccess.style.display = 'block';
        setTimeout(function() {
            signupSuccess.style.display = 'none';
        }, 1000);
        var signupFailure = document.getElementById('update-user-failure');
        signupFailure.style.display = 'block';
        setTimeout(function() {
            signupFailure.style.display = 'none';
        }, 1000);
    </script>
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
    <script>
        function validateForm() {
            var oldPassword = document.getElementById("passwordold").value;
            var newPassword = document.getElementById("passwordnew").value;
            var confirmPassword = document.getElementById("repasswordnew").value;
            var oldPasswordError = document.getElementById("old_password_error");
            var newPasswordError = document.getElementById("new_password_error");
            var confirmPasswordError = document.getElementById("re_new_password_error");
            var isValid = true;

            oldPasswordError.innerHTML = "";
            newPasswordError.innerHTML = "";
            confirmPasswordError.innerHTML = "";

            if (oldPassword == "" && newPassword == "" && confirmPassword == "") {
                oldPasswordError.innerHTML = "Vui lòng nhập mật khẩu cũ";
                newPasswordError.innerHTML = "Vui lòng nhập mật khẩu mới";
                confirmPasswordError.innerHTML = "Vui lòng nhập mật khẩu xác nhận";
                isValid = false;
            }
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