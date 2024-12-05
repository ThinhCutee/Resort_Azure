<?php
session_start();

try {
    // Assign values to variables
    $id = 'UDAZ' . substr(abs(crc32(uniqid())), 0, 5);
    $tenUuDai = isset($_POST['tenUuDai']) ? $_POST['tenUuDai'] : '';
    $moTa = isset($_POST['moTa']) ? $_POST['moTa'] : '';
    $giaGiam = isset($_POST['giaGiam']) ? $_POST['giaGiam'] : '';
    $ngayBatDau = isset($_POST['ngayBatDau']) ? $_POST['ngayBatDau'] : '';
    $ngayKetThuc = isset($_POST['ngayKetThuc']) ? $_POST['ngayKetThuc'] : '';

    // Check if any required field is empty
    if (empty($tenUuDai) || empty($moTa) || empty($giaGiam) || empty($ngayBatDau) || empty($ngayKetThuc)) {
        throw new Exception("Không được để trống bất kỳ trường nào.");
    }

    // Validate that start date is before end date
    if (strtotime($ngayBatDau) >= strtotime($ngayKetThuc)) {
        throw new Exception("Ngày bắt đầu phải trước ngày kết thúc.");
    }

    // Include database connection
    include("database.php");
    $conn = connect();

    // SQL statement for prepared statement
    insertUuDai($id, $giaGiam, $tenUuDai, $moTa, $ngayBatDau, $ngayKetThuc);

    $response = array(
        'success' => true,
        'message' => "Dữ liệu đã được thêm thành công!"
    );
} catch (Exception $e) {
    $response = array(
        'success' => false,
        'message' => $e->getMessage()
    );
}

// Save response to session and redirect
$_SESSION['response'] = $response;
header("Location: ../uudai.php");
exit();
?>
