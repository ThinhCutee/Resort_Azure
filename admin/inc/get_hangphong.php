<?php
include("database.php");

if (isset($_GET['khachSanID'])) {
    $khachSanID = intval($_GET['khachSanID']);
    $conn = connect();

    $sql = "SELECT id, hang_phong FROM phong WHERE id_khach_san = :khachSanID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':khachSanID', $khachSanID, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);

    $stmt->closeCursor();
    $conn = null;
}
?>
