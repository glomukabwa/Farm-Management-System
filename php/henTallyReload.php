<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    /*Getting the chicken ID first*/
    $chickenIdRes = $conn->query("SELECT id FROM animal_types WHERE name = 'Chicken'");
    $chickenIdRow = $chickenIdRes->fetch_assoc();
    $chickenId = (int)$chickenIdRow['id'];

    /*Getting the ID for 'Alive in the farm */
    $aliveStatusStmt = $conn->query("SELECT id FROM animal_lifecycle_statuses 
                                    WHERE name = 'Alive in the farm'");
    $aliveStatusRow = $aliveStatusStmt->fetch_assoc();
    $aliveStatusId = (int)$aliveStatusRow['id'];

    $adultHensStmt = $conn->prepare("SELECT COUNT(*) as count FROM animals 
                                    WHERE animal_type_id = ? 
                                        AND gender = 'female'
                                            AND lifecycle_status_id = ?");
    $adultHensStmt->bind_param("ii", $chickenId, $aliveStatusId);
    $adultHensStmt->execute();
    $adultHensRes = $adultHensStmt->get_result();
    $adultHensRow = $adultHensRes->fetch_assoc();
    $adultHens = $adultHensRow['count'] ?? 0;/*Now u see why the null coalesce operator(??) is important*/
    /**/

    echo $adultHens;

}

?>