<?php
include("./config/connect.php");
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>
    alert("Vui lòng đăng nhập.");
    window.location.href = "index.php";
    </script>';
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <?php require('inc/links.php'); ?>
    <title>Đánh Giá</title>
    <link rel="stylesheet" href="css/css_danh_gia.css">
</head>
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
        WHERE dg.id_phong_dat = $id_dichvu AND dg.id_dich_vu = dv.id
    )
";

    $result_dich_vu = mysqli_query($conn, $query_dich_vu);
    return $result_dich_vu;
}
function insertDG($id_phong_dat, $id_dich_vu, $ten_nguoi_danh_gia, $binh_luan, $danh_gia, $trang_thai)
{
    global $conn;
    $sql = "INSERT INTO danhgia (id_phong_dat, id_dich_vu, ten_nguoi_danh_gia, binh_luan, danh_gia, trang_thai) VALUES ('$id_phong_dat', '$id_dich_vu', '$ten_nguoi_danh_gia', '$binh_luan', '$danh_gia','$trang_thai')";
    return mysqli_query($conn, $sql);
}
if (isset($_REQUEST['id'])) {
    $id_decode = base64_decode($_REQUEST['id']);
    $get_du_lieu = getDetailDV($id_decode);
}

?>

<body>
    <?php include("inc/header.php") ?>
    <?php
    if (isset($_REQUEST['danhgia'])) {
        $sao = $_REQUEST['rating'];
        $id_dich_vu = $_REQUEST['id_dich_vu'];
        $binhluan = $_REQUEST['opinion'];
        $ten_nguoi_danh_gia = $user['ho'] . " " . $user['ten'];
        $trang_thai = isset($_REQUEST['trang_thai']) ? 0 : 1;
        $insert_danh_gia = insertDG($id_decode, $id_dich_vu, $ten_nguoi_danh_gia, $binhluan, $sao, $trang_thai);
        if ($insert_danh_gia) {
            echo '<div id="review-success" class="alert alert-success text-center" role="alert">
                      Đánh giá thành công!';
            echo '</div>';
        } else {
            echo '<div id="review-failure" class="alert alert-danger text-center" role="alert">
                      Đánh giá thất bại! Vui lòng thử lại.
                  </div>';
        }
    }
    ?>
    <div class="product-rating-list">
        <?php
        while ($row = mysqli_fetch_assoc($get_du_lieu)) {
            $ten_dich_vu = $row['ten_dich_vu'];
            $hinh_anh = $row['hinh_anh'];
            $mo_ta = $row['mo_ta'];
            $don_gia = $row['gia_dich_vu_hien_thi'];
            $id_dich_vu = $row['id'];
            echo '
        <div class="product-item">
            <div class="product-header">
                <img src="./admin/uploads/dichvu/' . $hinh_anh . '" alt="Tên sản phẩm" class="product-image">
                <div class="product-info">
                    <h5 class="product-name">' . $ten_dich_vu . '</h5>
                    <p class="product-description">' . $mo_ta . '</p>
                </div>
            </div>

            <div class="rating-container">
                <h5>Đánh giá</h5>
                <hr>
                <form action="" method="post">
                    <div class="rating">
                        <input type="number" name="rating" hidden >
                        <i class="bx bx-star star" style="--i: 0;"></i>
                        <i class="bx bx-star star" style="--i: 1;"></i>
                        <i class="bx bx-star star" style="--i: 2;"></i>
                        <i class="bx bx-star star" style="--i: 3;"></i>
                        <i class="bx bx-star star" style="--i: 4;"></i>
                        <input type="hidden" name="id_dich_vu" value = ' . $id_dich_vu . '>
                    </div>
                    <textarea name="opinion" cols="30" rows="5" placeholder="Để lại đánh giá của bạn..." required></textarea>
                    <div class="andanh">
                    <label class="switch">
                    <input type="checkbox" checked value="1" name="trang_thai" id="trang_thai">
                    <span class="slider round"></span>
                    </label>
                    <label for="trang_thai" >Đánh giá ẩn danh</label>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn submit" name="danhgia">Đánh Giá</button>
                        <a href="mybooking.php" class="btn cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
        ';
        }
        ?>
    </div>

    <?php include("inc/footer.php") ?>
    <script>
        const allStar = document.querySelectorAll('.rating .star')
        const ratingValue = document.querySelector('.rating input')

        allStar.forEach((item, idx) => {
            item.addEventListener('click', function() {
                let click = 0
                ratingValue.value = idx + 1

                allStar.forEach(i => {
                    i.classList.replace('bxs-star', 'bx-star')
                    i.classList.remove('active')
                })
                for (let i = 0; i < allStar.length; i++) {
                    if (i <= idx) {
                        allStar[i].classList.replace('bx-star', 'bxs-star')
                        allStar[i].classList.add('active')
                    } else {
                        allStar[i].style.setProperty('--i', click)
                        click++
                    }
                }
            })
        })
        var signupSuccess = document.getElementById('review-success');
        signupSuccess.style.display = 'block';
        setTimeout(function() {
            signupSuccess.style.display = 'none';
            window.location.href = 'mybooking.php';
        }, 1000);
        var signupFailure = document.getElementById('review-failure');
        signupFailure.style.display = 'block';
        setTimeout(function() {
            signupFailure.style.display = 'none';
            window.location.href = 'mybooking.php';
        }, 1000);
    </script>
</body>

</html>