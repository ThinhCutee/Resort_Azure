<?php
session_start();

// Kiểm tra xem request có phải là POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $id = isset($_POST['id_image_phong']) ? intval($_POST['id_image_phong']) : null;
    $id_phong = isset($_POST['id_phong']) ? intval($_POST['id_phong']) : null;
    // Kiểm tra xem người dùng đã chọn hình ảnh mới hay không
    if(isset($_FILES['anh_phong']['name']) && !empty($_FILES['anh_phong']['name'])) {
        // Nếu có hình ảnh mới được chọn, thực hiện cập nhật hình ảnh
        $anh_phong = $_FILES['anh_phong']['name'];

        // Thư mục lưu trữ hình ảnh
        $target_dir = "../uploads/";

        // Tạo thư mục nếu nó không tồn tại
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Đường dẫn đầy đủ của hình ảnh
        $target_file = $target_dir . basename($_FILES["anh_phong"]["name"]);
        // Di chuyển file hình ảnh vào thư mục bạn muốn
        move_uploaded_file($_FILES["anh_phong"]["tmp_name"], $target_file);
    } else {
        // Nếu không có hình ảnh mới được chọn, giữ nguyên hình ảnh cũ
        $anh_phong = null;
    }

    try {
        include("database.php");
        $conn = connect();

        // Sử dụng prepare statement để tránh SQL injection

        // Chuỗi SQL cơ bản
        $sql = "UPDATE image_phong SET ";

        // Nếu có hình ảnh mới được chọn, thêm phần cập nhật hình ảnh vào câu lệnh SQL
        if($anh_phong != null) {
            $sql .= "anh_phong = :anh_phong ";
        }

        // Thêm điều kiện WHERE
        $sql .= "WHERE id = :id ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Nếu có hình ảnh mới được chọn, gắn giá trị vào prepare statement
        if($anh_phong != null) {
            $stmt->bindParam(':anh_phong', $anh_phong, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Cập nhật thành công'
            );
            header("Location: ../image_phong.php?id_phong=$id_phong");
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            $response = array(
                'status' => 'error',
                'message' => 'Không thể cập nhật dữ liệu.',
                'errorInfo' => $errorInfo
            );
        }

        $stmt->closeCursor();
        $conn = null;
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
        header("Location: ../image_phong.php?id_phong=$id_phong");
        // exit;
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
        header("Location: ../image_phong.php?id_phong=$id_phong");
        // exit;
    }

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
    header("Location: ../image_phong.php?id_phong=$id_phong");
    exit;
}
?>
