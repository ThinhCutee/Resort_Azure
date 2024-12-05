<?php
    // Lấy id_phong từ URL
    $id_phong = isset($_GET['id_phong']) ? intval($_GET['id_phong']) : 0;

    // Kiểm tra id_phong hợp lệ
    if ($id_phong > 0) {
        // Kết nối cơ sở dữ liệu
        include("database.php");
        $conn = connect();

        // Lấy danh sách ảnh của phòng này từ cơ sở dữ liệu
        $sql = "SELECT * FROM image_phong WHERE id_phong = :id_phong";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_phong', $id_phong, PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll();
    } else {
        echo "ID phòng không hợp lệ.";
        exit;
    }
?>