<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['RowId'];
    $stmt = $conn->prepare("SELECT * FROM female_cows WHERE id = ?");
    $stmt->bind_param("i", $rowId);
    $stmt->execute();
    $stmtRes = $stmt->get_result();
    $stmtRow = $stmtRes->fetch_assoc();

    $name = $stmtRow['tag_name'];
    $breedId = $stmtRow['breed_id'];
    $milkProd = $stmtRow['milkProduction'];
    $healthStatus = $stmtRow['health_status_id'];
    $isPreg = $stmtRow['isPregnant'];
    $lifeStatus = $stmtRow['lifecycle_status_id'];

    $result = [
        "name" => $name,
        "breed" => $breedId,
        "milkProduction" => $milkProd,
        "healthStatus" => $healthStatus,
        "isPreg" => $isPreg,
        "lifeStatus" => $lifeStatus
    ];

    echo json_encode($result);
}
?>