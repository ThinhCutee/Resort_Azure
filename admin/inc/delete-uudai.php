<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nhận dữ liệu từ yêu cầu Ajax
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    include("database.php");
    $response = deleteUuDai($id);
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    );
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
