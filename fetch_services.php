<?php
include('config/connect.php');

if (isset($_POST['goi_dich_vu_id'])) {
    $goiDichVuId = $_POST['goi_dich_vu_id'];

    $query_all_services = "SELECT * FROM dichvu";
    $result_all_services = mysqli_query($conn, $query_all_services);
    $all_services = [];
    while ($row = mysqli_fetch_assoc($result_all_services)) {
        $all_services[] = $row;
    }

    $selected_services = [];
    if ($goiDichVuId !== '') {
        $query_selected = "SELECT * FROM dichvu WHERE id_goi_dich_vu = $goiDichVuId";
        $result_selected = mysqli_query($conn, $query_selected);

        while ($row = mysqli_fetch_assoc($result_selected)) {
            $selected_services[] = $row['id'];
        }
    }

    echo json_encode([
        'all_services' => $all_services,
        'selected_services' => $selected_services
    ]);
}
