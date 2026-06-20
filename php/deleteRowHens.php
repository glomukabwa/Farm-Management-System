<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowIds = json_decode($_POST['rowIds'], true);/*True makes the array associative. Just erase it and the
    comma then put back the comma and Visual Studio will give u a detailed explanation */

    $stmt = $conn->prepare("DELETE FROM animals
                            WHERE id = ?");

    foreach ($rowIds as $rowId){
        $stmt->bind_param("i", $rowId);
        $stmt->execute();
    }

}
?>