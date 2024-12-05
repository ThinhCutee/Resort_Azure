<?php
session_start();

// Kiểm tra xem request có phải là POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $gioiHan = isset($_POST['gioiHan']) ? intval($_POST['gioiHan']) : null;
    $tenDV = isset($_POST['tenDV']) ? $_POST['tenDV'] : null;
    $loaiDV = isset($_POST['loaiDV']) ? $_POST['loaiDV'] : null;
    $gia = isset($_POST['gia']) ? doubleval($_POST['gia']) : null;
    $moTa = isset($_POST['moTa']) ? $_POST['moTa'] : null;
   
    // Kiểm tra xem người dùng đã chọn hình ảnh mới hay không
    if(isset($_FILES['hinhAnh']['name']) && !empty($_FILES['hinhAnh']['name'])) {
        // Nếu có hình ảnh mới được chọn, thực hiện cập nhật hình ảnh
        $hinhAnh = $_FILES['hinhAnh']['name'];

        // Thư mục lưu trữ hình ảnh
        $target_dir = "../uploads/dichvu/";

        // Tạo thư mục nếu nó không tồn tại
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Đường dẫn đầy đủ của hình ảnh
        $target_file = $target_dir . basename($_FILES["hinhAnh"]["name"]);
        // Di chuyển file hình ảnh vào thư mục bạn muốn
        move_uploaded_file($_FILES["hinhAnh"]["tmp_name"], $target_file);
    } else {
        // Nếu không có hình ảnh mới được chọn, giữ nguyên hình ảnh cũ
        $hinhAnh = null;
    }
    include("database.php");
    
    $response =updateDV($id, $tenDV, $loaiDV, $moTa, $gioiHan, $hinhAnh, $gia);
    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Nếu không phải là request POST, trả về lỗi
    $response = array(
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ'
    );

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

