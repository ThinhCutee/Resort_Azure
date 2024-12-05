<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Assign values to variables from POST request
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $tenUuDai = isset($_POST['tenUuDai']) ? $_POST['tenUuDai'] : '';
        $moTa = isset($_POST['moTa']) ? $_POST['moTa'] : '';
        $giaGiam = isset($_POST['giaGiam']) ? $_POST['giaGiam'] : '';
        $ngayBatDau = isset($_POST['ngayBatDau']) ? $_POST['ngayBatDau'] : '';
        $ngayKetThuc = isset($_POST['ngayKetThuc']) ? $_POST['ngayKetThuc'] : '';

        // Check if any required field is empty
        if (empty($id) || empty($tenUuDai) || empty($moTa) || empty($giaGiam) || empty($ngayBatDau) || empty($ngayKetThuc)) {
            throw new Exception("Không được để trống bất kỳ trường gì.");
        }

        // Validate that start date is before end date
        if (strtotime($ngayBatDau) >= strtotime($ngayKetThuc)) {
            throw new Exception("Ngày bắt đầu phải trước ngày kết thúc.");
        }
        if($giaGiam>100 || $giaGiam<=0)
        {
            throw new Exception("Giá giảm phải <=100 % và >=0.");
        }

        // Include database connection
        include("database.php");
        // function
        $response = updateUuDai($id, $giaGiam, $tenUuDai, $moTa, $ngayBatDau, $ngayKetThuc);

    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Lỗi xảy ra: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
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
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
