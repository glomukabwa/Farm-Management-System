<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['rowId'];

    $getAnimalsId = $conn->prepare("SELECT animal_reference_id FROM female_cows WHERE id = ?");
    $getAnimalsId->bind_param("i", $rowId);
    $getAnimalsId->execute();
    $getAnimalsIdRes = $getAnimalsId->get_result();
    $getAnimalsIdRow = $getAnimalsIdRes->fetch_assoc();
    $AnimalsId = (int)$getAnimalsIdRow['animal_reference_id'];

    $stmt = $conn->prepare("DELETE FROM animals
                            WHERE id = ?");
    $stmt->bind_param("i", $AnimalsId);
    $stmt->execute();

}
?>