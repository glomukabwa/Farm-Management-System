<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $oldName = $_POST['oldName'];
    $oldBreed = $_POST['oldBreed'];
    $oldHealth = $_POST['oldHealth'];
    $oldLife = $_POST['oldLife'];

    $rowId = $_POST['rowId'];
    $enteredName = $_POST['tagName'];
    $breedId = empty($_POST['breedId']) ? null : $_POST['breedId'];
                /*$_POST['breedId']) ?? null will only check to see if the breedId
                    has been sent at all, it won't check if what has been sent is 
                    an empty string. Yes, "" is known as an empty string.*/
    $health = $_POST['health'];
    $life = $_POST['life'];

    
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

    $oldNameChecked = '';
    if($oldName == 'Undefined'){/*I need to do this cz $oldName can either store a tagName or
                                  the value 'Undefined' to mean it is null. If I don't write code
                                  here to say 'Undefined' means null, it'll store a tagName called
                                  null in the DB instead of storing the value Null. Like the name
                                  of the animal will literally be Undefined*/
        $oldNameChecked = null;
    }else{
        $oldNameChecked = $oldName;
    }

    $nameChanged = false;

    if($name != $oldNameChecked){
        $nameChanged = true;
    }

    $nameIsDuplicate = false;

    if($nameChanged){

        if($name != null){
        
            $duplicateStmt = $conn->prepare("SELECT id FROM animals WHERE tag_name = ?");
            $duplicateStmt->bind_param("s", $name);
            $duplicateStmt->execute();
            $duplicateStmt->store_result();
            if($duplicateStmt->num_rows > 0){
                $nameIsDuplicate = true;
            }
        }

        if($nameIsDuplicate == false){
            $stmt = $conn->prepare("UPDATE animals
                                    SET tag_name = ?,
                                        breed_id = ?,
                                        health_status_id = ?,
                                        lifecycle_status_id = ?
                                    WHERE id = ?");
            $stmt->bind_param("siiii", $name, $breedId, $health, $life, $rowId);
            $stmt->execute();


        }
    }else{/*I'm only gonna check if name has changed cz it's the only one I need to make sure
            isn't a duplicate. The rest can have similar old values and new values.*/
        $stmt2 = $conn->prepare("UPDATE animals
                                    SET breed_id = ?,
                                        health_status_id = ?,
                                        lifecycle_status_id = ?
                                    WHERE id = ?");
        $stmt2->bind_param("iiii", $breedId, $health, $life, $rowId);
        $stmt2->execute();
    }

    if($nameIsDuplicate){/*You need to put this here cz if you put it inside the name update only
                           section of the if statement, when updating just the other fields (the 
                           else part of the statement), it won't ever output valid even though the
                           update is Valid*/
        echo 'Duplicate';
    }else{
        echo 'Valid';
    }
    
                
}
?>