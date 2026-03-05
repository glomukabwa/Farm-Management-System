<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['rowId'];
    $name = $_POST['tagName'];
    $health = $_POST['health'];
    $milk = $_POST['milk'];
    $preg = $_POST['preg'];
    $life = $_POST['life'];

    $stmt = $conn->prepare("UPDATE female_cows
                            SET tag_name = ?,
                                health_status_id = ?,
                                milkProduction = ?,
                                isPregnant = ?,
                                lifecycle_status_id = ?
                            WHERE id = ?");
    $stmt->bind_param("sidiii", $name, $health, $milk, $preg, $life, $rowId);
    $stmt->execute();
                
}
?>