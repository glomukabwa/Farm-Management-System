<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['rowId'];

    $stmt = $conn->prepare("DELETE FROM female_cows
                            WHERE id = ?");
    $stmt->bind_param("i", $rowId);
    $stmt->execute();

}
?>