<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rowId = $_POST['rowId'];

    $enteredName = $_POST['tagName'];
    $undefName = ['Undefined', 'Undefine', 'Undefin'];
    $isUndefinedName = false;
    foreach($undefName as $word){
        if(stripos($enteredName, $word) !== false){
            $isUndefinedName = true;
        }
    }
    $name = empty($_POST['tagName']) || $isUndefinedName == true ? null : $_POST['tagName'];
            /*The tagName in the DB is nullable so I wanna make sure that users can remove names and
              leave them empty if they want to. In the code for displaying the table, I've made sure
              that if a tagName is empty, it will be assigned the value 'Undefined'*/
              
    $breedId = empty($_POST['breedId']) ? null : $_POST['breedId'];
                /*$_POST['breedId']) ?? null will only check to see if the breedId
                    has been sent at all, it won't check if what has been sent is 
                    an empty string. Yes, "" is known as an empty string.*/
    $health = $_POST['health'];
    $life = $_POST['life'];

    $stmt = $conn->prepare("UPDATE animals
                            SET breed_id = ?,
                                tag_name = ?,
                                lifecycle_status_id = ?,
                                health_status_id = ?
                            WHERE id = ?");
    $stmt->bind_param("isiii", $breedId, $name, $life, $health, $rowId);
    $stmt->execute();
                
}
?>