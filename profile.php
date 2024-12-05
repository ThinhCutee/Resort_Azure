<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azure Resort - Thông Tin Cá Nhân</title>
    <?php require('inc/links.php'); ?>
</head>

<body>
    <?php
    require('inc/header.php');
    ?>
    <?php
    if (isset($user['id_tai_khoan'])) {
        function getAllInfo($id_user)
        {
            global $conn;
            $sql_info = "SELECT * FROM khachhang WHERE id_tai_khoan = '$id_user'";
            $result_info = mysqli_query($conn, $sql_info);
            $fetch_info = mysqli_fetch_assoc($result_info);
            return $fetch_info;
        }
        $fetch_info = getAllInfo($user['id_tai_khoan']);
        function updateUser($id_user, $ho, $ten, $sdt, $email, $dia_chi, $hinh_anh)
        {
            global $conn;
            $sql_change = "UPDATE khachhang SET ho = '$ho', ten = '$ten', sdt = '$sdt', email = '$email', dia_chi = '$dia_chi', hinh_anh = '$hinh_anh' WHERE id_tai_khoan = '$id_user'";
            $result_change_info = mysqli_query($conn, $sql_change);
            return $result_change_info;
        }
        if (isset($_POST['btnChange'])) {
            $hodemnew = $_POST['hodemnew'];
            $tennew = $_POST['tennew'];
            $emailnew = $_POST['emailnew'];
            $sdtnew = $_POST['sdtnew'];
            $diachinew = $_POST['diachinew'];

            if (isset($_FILES["anhnew"]) && $_FILES["anhnew"]["error"] === 0) {
                $anhdaidien_tmp_name = $_FILES["anhnew"]["tmp_name"];
                $anhdaidien_name = basename($_FILES["anhnew"]["name"]);
                $anhdaidien_destination = "./uploads/" . $anhdaidien_name;

                if (!move_uploaded_file($anhdaidien_tmp_name, $anhdaidien_destination)) {
                    exit;
                }
            }

            $update_user = updateUser($user['id_tai_khoan'], $hodemnew, $tennew, $sdtnew, $emailnew, $diachinew, $anhdaidien_name);

            if ($update_user) {
                echo '
                    <div id="update-user-success" class="alert alert-success  text-center" role="alert">
                        Thay đổi thông tin thành công.
                    </div>
                    ';
                echo '<script>setTimeout(function(){ window.location.href = "profile.php"; }, 900);</script>';
            } else {
                echo '
                    <div id="update-user-failure" class="alert alert-danger  text-center" role="alert">
                        Thay đổi thông tin thất bại.
                    </div>
                    ';
                echo '<script>setTimeout(function(){ window.location.href = "profile.php"; }, 900);</script>';
            }
        }
    ?>
        <div class="container">
            <div class="row">
                <div class="col-12 my-5 px-4">
                    <h2 class="fw-bold">Thông Tin Cá Nhân</h2>
                    <div style="font-size: 14px;">
                        <a href="index.php" class="text-secondary text-decoration-none;">Home</a>
                        <span class="text-secondary"> > </span>
                        <a href="#" class="text-secondary text-decoration-none;">Thông Tin Cá Nhân</a>
                    </div>
                </div>
                <div class="col-12 my-5 px-4" style="margin-top: -20px !important;">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                        <a href="index.php" class="btn btn-sm w-20 btn-outline-dark ">Thoát</a>
                        <div class="text-center">
                            <img src="uploads/<?php echo $fetch_info['hinh_anh']; ?>" class="rounded img-thumbnail" alt="avatar" height="200px" width="200px">
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <h5 class="mb-3 fw-bold">Thông Tin Cá Nhân</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Họ Đệm</label>
                                    <input type="text" type="text" class="form-control shadow-none" name="hodemnew" value="<?php echo $fetch_info['ho']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Tên</label>
                                    <input type="text" type="text" class="form-control shadow-none" name="tennew" value="<?php echo $fetch_info['ten']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Email</label>
                                    <input type="email" type="email" class="form-control shadow-none" name="emailnew" value="<?php echo $fetch_info['email']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Số điện thoại</label>
                                    <input type="phone" type="phone" class="form-control shadow-none" name="sdtnew" value="<?php echo $fetch_info['sdt']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Địa Chỉ</label>
                                    <input type="text" type="text" class="form-control shadow-none" name="diachinew" value="<?php echo $fetch_info['dia_chi']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="form-label">Ảnh đại diện</label>
                                    <input type="file" type="file" class="form-control shadow-none" name="anhnew" value="<?php echo $fetch_info['hinh_anh']; ?>" required>
                                </div>
                            </div>
                            <center><button type="submit" class="btn text-white custom-bg shadow-none" name="btnChange">Cập Nhật</button></center>
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
</body>

</html>