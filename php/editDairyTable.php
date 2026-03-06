<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['rowId'];
    $name = $_POST['tagName'];
    $breedId = empty($_POST['breedId']) ? null : $_POST['breedId'];
                /*$_POST['breedId']) ?? null will only check to see if the breedId
                    has been sent at all, it won't check if what has been sent is 
                    an empty string. Yes, "" is known as an empty string.*/
    $health = $_POST['health'];
    $milk = $_POST['milk'];
    $preg = $_POST['preg'];
    $life = $_POST['life'];

    $getAnimalsId = $conn->prepare("SELECT animal_reference_id FROM female_cows WHERE id = ?");
    $getAnimalsId->bind_param("i", $rowId);
    $getAnimalsId->execute();
    $getAnimalsIdRes = $getAnimalsId->get_result();
    $getAnimalsIdRow = $getAnimalsIdRes->fetch_assoc();
    $AnimalsId = (int)$getAnimalsIdRow['animal_reference_id'];


    $stmt = $conn->prepare("UPDATE animals
                            SET breed_id = ?,
                                tag_name = ?,
                                lifecycle_status_id = ?,
                                health_status_id = ?
                            WHERE id = ?");
    $stmt->bind_param("isiii", $breedId, $name, $life, $health, $AnimalsId);
    $stmt->execute();


    $stmt2 = $conn->prepare("UPDATE female_cows
                            SET tag_name = ?,
                                breed_id = ?,
                                health_status_id = ?,
                                milkProduction = ?,
                                isPregnant = ?,
                                lifecycle_status_id = ?
                            WHERE id = ?");
    $stmt2->bind_param("siidiii", $name, $breedId, $health, $milk, $preg, $life, $rowId);
    $stmt2->execute();
                
}
?>