<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['RowId'];
    $stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
    $stmt->bind_param("i", $rowId);
    $stmt->execute();
    $stmtRes = $stmt->get_result();
    $stmtRow = $stmtRes->fetch_assoc();

    $name = $stmtRow['tag_name'];
    $breedId = $stmtRow['breed_id'];
    $healthStatus = $stmtRow['health_status_id'];
    $lifeStatus = $stmtRow['lifecycle_status_id'];

    $result = [
        "name" => $name,
        "breed" => $breedId,
        "healthStatus" => $healthStatus,
        "lifeStatus" => $lifeStatus
    ];

    echo json_encode($result);
}
?>