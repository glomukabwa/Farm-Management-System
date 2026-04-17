<?php
require 'admin_auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $quantity = $_POST['aniQuantity'];
    $breed = empty($_POST['aniBreed']) ? null : $_POST['aniBreed'];
    $healthStatus = $_POST['aniHealth'];
    $createdAt = $_POST['aniDate'] ?: date('Y-m-d');

    $animTypeStmt = $conn->prepare("SELECT id FROM animal_types WHERE name = 'Cow'");
    $animTypeStmt->execute();
    $animTypeRes = $animTypeStmt->get_result();
    $animTypeRow = $animTypeRes->fetch_assoc();
    $animalType = $animTypeRow['id'];

    $gender = 'female';

    $success = false;

    if($quantity >= 1){
        $animStmt = $conn->prepare("INSERT INTO animals (animal_type_id, breed_id, gender, health_status_id, created_at) 
                        VALUES (?, ?, ?, ?, ?)");
        $femaleStmt = $conn->prepare("INSERT INTO female_cows (animal_reference_id, animal_type_id, breed_id, health_status_id, created_at)
                                        VALUES (?, ?, ?, ?, ?)");
        
        for($count = 0; $count < $quantity; $count++) {
            /*The rule is to prepare once(the prepared stmt above) then execute multiple times so the
            ones below are the ones that are put in the loop.*/
            $animStmt->bind_param("iisis", $animalType, $breed, $gender, $healthStatus, $createdAt);
            $animStmt->execute();

            $animId = $conn->insert_id;

            if($gender == "female"){
                $femaleStmt->bind_param("iiiis", $animId, $animalType, $breed, $healthStatus, $createdAt);
                $femaleStmt->execute();
            }

            if($animStmt->affected_rows > 0){/*If it doesn't insert eg 1 out of 3, this will set it to false
                                                It runs for every animal entered*/
                $success = true;
            }else{
                $success = false;
            }
        }


    }

    echo json_encode($success);

}
?>