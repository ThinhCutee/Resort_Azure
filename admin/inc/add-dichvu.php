<?php
session_start();

try {
    // Assign values to variables
    $tenDV = isset($_POST['tenDV']) ? $_POST['tenDV'] : '';
    $loaiDV = isset($_POST['loaiDV']) ? $_POST['loaiDV'] : '';
    $gioiHan = isset($_POST['gioiHan']) ? $_POST['gioiHan'] : '';
    $moTa = isset($_POST['moTa']) ? $_POST['moTa'] : '';
    $gia = isset($_POST['gia']) ? $_POST['gia'] : '';
    
    include("database.php"); 
    // Handle image upload
    if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['size'] > 0) {
        $file_name = $_FILES['hinhAnh']['name'];
        $file_tmp = $_FILES['hinhAnh']['tmp_name'];
        $file_size = $_FILES['hinhAnh']['size'];
        $file_type = $_FILES['hinhAnh']['type'];

        // Check file type
        if ($file_type != "image/jpeg" && $file_type != "image/png" && $file_type != "image/gif " && $file_type != "image/jpg") {
            throw new Exception("Chỉ chấp nhận các file JPG, JPEG, PNG và GIF.");
        }

        // Check file size (max 5MB)
        if ($file_size > 5 * 1024 * 1024) {
            throw new Exception("File hình ảnh quá lớn. Vui lòng chọn file dưới 5MB.");
        }

        // Đường dẫn thư mục lưu trữ hình ảnh trên máy chủ
        $upload_folder = "../uploads//dichvu/";

        // Di chuyển file tải lên vào thư mục lưu trữ trên máy chủ
        if (move_uploaded_file($file_tmp, $upload_folder . $file_name)) {
            $hinhAnh = $file_name;
        } else {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
    }
    if (empty($tenDV) || empty($loaiDV) || empty($gioiHan) || empty($moTa) || empty($gia)) {
        // Nếu có trường nào trống, trả về thông báo lỗi
        $response = array(
            'success' => false,
            'message' => "Vui lòng điền đầy đủ thông tin."
        );
    }else{
         // Sử dụng hàm
        insertDV($tenDV, $loaiDV, $moTa, $gioiHan, $hinhAnh, $gia);
        $response = array(
            'success' => true,
            'message' => "Dữ liệu đã được thêm thành công!"
        );
    }
} catch (Exception $e) {
    $response = array(
        'success' => false,
        'message' => $e->getMessage()
    );
}

// Save response to session and redirect
$_SESSION['response'] = $response;
header("refresh:0; url='../dichvu.php'");
?>
