<?php
// session_start();
include("database.php");
$conn = connect();

$images = array();
$id_phong = 0;
$maxImages = 8; // Số lượng ảnh tối đa cho mỗi phòng

// Kiểm tra nếu phương thức là POST để xử lý upload ảnh
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_phong = intval($_POST['id_phong']);
    $images = $_FILES['anh_phong'];

    // Kiểm tra id_phong có tồn tại trong bảng phong hay không
    $sqlCheck = "SELECT COUNT(*) FROM phong WHERE id = :id_phong";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':id_phong', $id_phong, PDO::PARAM_INT);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    if ($count == 0) {
        $_SESSION['response'] = array(
            'message' => 'ID phòng không hợp lệ.',
            'success' => false
        );
        header("Location: ../image_phong.php?id_phong=$id_phong");
        exit;
    }

    // Kiểm tra số lượng hình ảnh được tải lên
    $totalUploadedImages = count($_FILES['anh_phong']['name']);

    // Kiểm tra xem số lượng hình ảnh được tải lên có vượt quá số lượng tối đa không
    if ($totalUploadedImages > $maxImages) {
        $_SESSION['response'] = array(
            'message' => "Chỉ được phép tải lên tối đa $maxImages ảnh cho mỗi phòng.",
            'success' => false
            
        );
        header("Location: ../image_phong.php?id_phong=$id_phong");
        exit;
        // throw new Exception("Chỉ được phép tải lên tối đa $maxImages ảnh cho mỗi phòng.");
    }

    // Thực hiện kiểm tra không có hai hình ảnh cùng id_phong và anh_phong
    $sqlCheckDuplicate = "SELECT COUNT(*) FROM image_phong WHERE id_phong = :id_phong AND anh_phong = :anh_phong";
    $stmtCheckDuplicate = $conn->prepare($sqlCheckDuplicate);

    // Lặp qua từng file và kiểm tra trùng lặp, sau đó tiến hành tải lên
    foreach ($images['name'] as $key => $value) {
        $fileName = basename($images['name'][$key]);

        // Kiểm tra xem có hình ảnh đã tồn tại trong cơ sở dữ liệu chưa
        $stmtCheckDuplicate->bindParam(':id_phong', $id_phong, PDO::PARAM_INT);
        $stmtCheckDuplicate->bindParam(':anh_phong', $fileName, PDO::PARAM_STR);
        $stmtCheckDuplicate->execute();
        $duplicateCount = $stmtCheckDuplicate->fetchColumn();

        // Nếu đã tồn tại hình ảnh có cùng id_phong và anh_phong, thông báo lỗi và dừng thực hiện
        if ($duplicateCount > 0) {
            $_SESSION['response'] = array(
                'message' => "Hình ảnh '$fileName' đã tồn tại cho phòng này.",
                'success' => false
            );
            header("Location: ../image_phong.php?id_phong=$id_phong");
            exit;
        }

        // Di chuyển file đến thư mục đích và thêm vào cơ sở dữ liệu
        $targetFilePath = "../uploads/" . $fileName;
        if (move_uploaded_file($images['tmp_name'][$key], $targetFilePath)) {
            // Chỉ lấy tên ảnh để lưu vào cơ sở dữ liệu
            $sql = "INSERT INTO image_phong (id_phong, anh_phong) VALUES (:id_phong, :anh_phong)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_phong', $id_phong, PDO::PARAM_INT);
            $stmt->bindParam(':anh_phong', $fileName, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $_SESSION['response'] = array(
                'message' => "Có lỗi xảy ra khi tải lên file: " . htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'),
                'success' => false
            );
            header("Location: ../image_phong.php?id_phong=$id_phong");
            exit;
        }
    }

    $_SESSION['response'] = array(
        'message' => 'Tải lên thành công!',
        'success' => true
    );
    header("Location: ../image_phong.php?id_phong=$id_phong");
    exit;
}

// Lấy danh sách ảnh của phòng này từ cơ sở dữ liệu
if (isset($_GET['id_phong'])) {
    $id_phong = intval($_GET['id_phong']);
    $sql = "SELECT * FROM image_phong WHERE id_phong = :id_phong";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_phong', $id_phong, PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $_SESSION['response'] = array(
        'message' => 'ID phòng không hợp lệ.',
        'success' => false
    );
    header("Location: ./image-phong.php"); // Chuyển hướng người dùng về trang chủ hoặc trang khác tùy bạn
    exit;
}
?>

