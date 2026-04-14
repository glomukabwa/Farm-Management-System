<?php 
require 'auth.php';
include 'config.php'; 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    ob_start();

    $criteriaValue = $_POST['criteriaOption'] ?? '';
    $searchValue = $_POST['searchValue'] ?? '';
    $limit = $_POST['limit'] ?? 10;
    $page = $_POST['page'] ?? 1;
    $offset = ($page - 1) * $limit;
    $totalRows = 0;

    $searchTerm = "%$searchValue%";
    $result = null;

    if($criteriaValue == 'name'){
        $undefinedName = ['und', 'unde', 'undef', 'def'];
        $isUndefined = false;
        foreach($undefinedName as $word){
            if(stripos($searchValue, $word) !== false){
                $isUndefined = true;
                break;
            }
        }
        if($isUndefined){
            $nullNameStmt = $conn->prepare("SELECT * FROM female_cows WHERE tag_name IS NULL LIMIT ?, ?");
            $nullNameStmt->bind_param("ii", $offset, $limit);
            $nullNameStmt->execute();
            $result = $nullNameStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE tag_name IS NULL");
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }else{
            $nameStmt = $conn->prepare("SELECT * FROM female_cows WHERE tag_name LIKE ? LIMIT ?, ?");
            $nameStmt->bind_param("sii", $searchTerm, $offset, $limit);
            $nameStmt->execute();
            $result = $nameStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE tag_name LIKE ?");
            $countStmt->bind_param("s", $searchTerm);
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }

    }elseif($criteriaValue == 'breed'){
        $unspecifiedBreed = ['no', 'spe', 'not spec'];
        /*Above, you can't write eg Not Specified cz we are going to check if part of the words are found. 
        If you write Not Specified, if someone types Not, it won't be a match cz Not specified is not found
        inside Not but Not is found inside Not Specied so you need to put here shorter versions of the word*/
        $isUnspecified = false;

        foreach($unspecifiedBreed as $word){
            if(stripos($searchValue, $word) !== false){/*stripos is case insensitive, strpos is case sensitive
            Why do we check if it is false instead of if it is true? Bcz stripos returns index positions when 
            true. Below, $needle is the word in the array and $haystack is the search value entered
                Case	                                                Return value
                $needle is found at the very start of $haystack	0       (position index)
                $needle is found later in $haystack	1, 2, 3…            (index of first match)
                $needle is not found                                    false
                
            Sth  u should know is that if it returns 0, in php 0 is false and 1 is true so in the if statement
            if you say if(...) == true, it will say no results found even though stripos is finding results so
            for stripos() one should check if the result is not false not if result is true*/
            
                $isUnspecified = true;
                break; /*This exits the foreach loop as soon as one of the words in the array is found in the
                         search value. This is to prevent the loop from continuing if a match is found */
            }
        }

        if($isUnspecified){
            $breedNullStmt = $conn->prepare("SELECT * FROM female_cows WHERE breed_id IS NULL LIMIT ?, ?");
            $breedNullStmt->bind_param("ii", $offset, $limit);
            $breedNullStmt->execute();
            $result = $breedNullStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE breed_id IS NULL");
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }else{
            $breedNameStmt = $conn->prepare("SELECT id FROM breeds WHERE name LIKE ?");
            $breedNameStmt->bind_param("s", $searchTerm);
            $breedNameStmt->execute();
            $breedNameRes = $breedNameStmt->get_result();
            $breedNameRow = $breedNameRes->fetch_assoc();
            if($breedNameRow == null){
                $result = null;
            }else{
                $breedId = $breedNameRow['id'];
                $breedStmt = $conn->prepare("SELECT * FROM female_cows WHERE breed_id = ? LIMIT ?, ?");
                $breedStmt->bind_param("iii", $breedId, $offset, $limit);
                $breedStmt->execute();
                $result = $breedStmt->get_result();

                $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE breed_id = ?");
                $countStmt->bind_param("i", $breedId);
                $countStmt->execute();
                $countRes = $countStmt->get_result();
                $totalRows = $countRes->fetch_assoc()['total'];
            }
        }
        

    }elseif($criteriaValue == 'healthStatus'){

        $healthNameStmt = $conn->prepare("SELECT id FROM animal_statuses WHERE status_name LIKE ?");
        $healthNameStmt->bind_param("s", $searchTerm);
        $healthNameStmt->execute();
        $healthNameRes = $healthNameStmt->get_result();
        $healthNameRow = $healthNameRes->fetch_assoc();
        if($healthNameRow == null){
            $result = null;
        }else{
            $healthId = $healthNameRow['id'];
            $healthStmt = $conn->prepare("SELECT * FROM female_cows WHERE health_status_id = ? LIMIT ?, ?");
            $healthStmt->bind_param("iii", $healthId, $offset, $limit);
            $healthStmt->execute();
            $result = $healthStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE health_status_id = ?");
            $countStmt->bind_param("i", $healthId);
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }

    }
    elseif($criteriaValue == 'milkProd'){

        $zeroMilk = ['0', '0.0'];
        $isZeroMilk = false;

        foreach($zeroMilk as $word){
            if(stripos($searchValue, $word) !== false){
                $isZeroMilk = true;
            }
        }

        if($isZeroMilk){
            $zeroMilkStmt = $conn->prepare("SELECT * FROM female_cows WHERE milkProduction IS NULL LIMIT ?, ?");
            $zeroMilkStmt->bind_param("ii", $offset, $limit);
            $zeroMilkStmt->execute();
            $result = $zeroMilkStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE milkProduction IS NULL");
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }else{
            $milkStmt = $conn->prepare("SELECT * FROM female_cows WHERE milkProduction = ? LIMIT ?, ?");
            $milkStmt->bind_param("dii", $searchValue, $offset, $limit);
            $milkStmt->execute();
            $result = $milkStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE milkProduction = ?");
            $countStmt->bind_param("d", $searchValue);
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }
    
    }
    elseif($criteriaValue == 'isPreg'){

        $notPreg = ['no', 'no preg', 'not'];
        $isNotPreg = false;

        foreach($notPreg as $word){
            if(stripos($searchValue, $word) !== false){
                $isNotPreg = true;
                break;
            }
        }

        if($isNotPreg){
            $notPregStmt = $conn->prepare("SELECT * FROM female_cows WHERE isPregnant = 0 LIMIT ?, ?");
            $notPregStmt->bind_param("ii", $offset, $limit);
            $notPregStmt->execute();
            $result = $notPregStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE isPregnant = 0");
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }else{
            $pregStmt = $conn->prepare("SELECT * FROM female_cows WHERE isPregnant = 1 LIMIT ?, ?");
            $pregStmt->bind_param("ii", $offset, $limit);
            $pregStmt->execute();
            $result = $pregStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE isPregnant = 1");
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }
        
    }
    elseif($criteriaValue == 'lifeStatus'){
        $lifeNameStmt = $conn->prepare("SELECT id FROM animal_lifecycle_statuses WHERE name LIKE ?");
        $lifeNameStmt->bind_param("s", $searchTerm);
        $lifeNameStmt->execute();
        $lifeNameRes = $lifeNameStmt->get_result();
        $lifeNameRow = $lifeNameRes->fetch_assoc();
        if($lifeNameRow == null){
            $result = null;
        }else{
            $lifeId = $lifeNameRow['id'];
            $lifeStmt = $conn->prepare("SELECT * FROM female_cows WHERE lifecycle_status_id = ? LIMIT ?, ?");
            $lifeStmt->bind_param("iii", $lifeId, $offset, $limit);
            $lifeStmt->execute();
            $result = $lifeStmt->get_result();

            $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM female_cows WHERE lifecycle_status_id = ?");
            $countStmt->bind_param("i", $lifeId);
            $countStmt->execute();
            $countRes = $countStmt->get_result();
            $totalRows = $countRes->fetch_assoc()['total'];
        }

    }

    /*Getting the health status id */
    $healthy = $conn->query("SELECT id FROM animal_statuses WHERE status_name = 'Healthy'");
    $healthyRes = $healthy->fetch_assoc();
    $sick = $conn->query("SELECT id FROM animal_statuses WHERE status_name = 'Sick'");
    $sickRes = $sick->fetch_assoc();
    $quara = $conn->query("SELECT id FROM animal_statuses WHERE status_name = 'Quarantined'");
    $quaraRes = $quara->fetch_assoc();

    $healthStatusName = "undefined";
    $healthStatusColor = "undetermined";

    /*Getting the life status id*/
    $alive = $conn->query("SELECT id FROM animal_lifecycle_statuses WHERE name = 'Alive in the farm'");
    $aliveRes = $alive->fetch_assoc();
    $sold = $conn->query("SELECT id FROM animal_lifecycle_statuses WHERE name = 'Sold'");
    $soldRes = $sold->fetch_assoc();
    $dead = $conn->query("SELECT id FROM animal_lifecycle_statuses WHERE name = 'Dead'");
    $deadRes = $dead->fetch_assoc();

    $lifeStatusName = "undefined";
    $lifeStatusColor = "undetermined";

    if($result && $result->num_rows > 0){/*The first part ensures $result exists before accessing it. I've done this cz I've 
    initialized $result as null so I need to check if it actually contains sth or I'll get an error for checking if sth null has a 
    number of rows*/
        while($row = $result->fetch_assoc()){
            /*Storing the id of the respective field for future use*/
            $rowId = $row['id'];

            $breedName = '';
            $breedId = (int)$row['breed_id'];
            if($row['breed_id'] != null){
                $breedStmt = $conn->prepare("SELECT name FROM breeds WHERE id = ?");
                $breedStmt->bind_param("i", $breedId);
                $breedStmt->execute();
                $breedRes = $breedStmt->get_result();
                $breedRow = $breedRes->fetch_assoc();
                $breedName = $breedRow['name'];
            }else{
                $breedName = 'Not specified';
            }

            /*Determining color depending of health status id */
            if((int)$row['health_status_id'] == (int)$healthyRes['id']){
                $healthStatusName = "Healthy";
                $healthStatusColor = "green";
            }elseif((int)$row['health_status_id'] == (int)$sickRes['id']){
                $healthStatusName = "Sick";
                $healthStatusColor = "red";
            }elseif((int)$row['health_status_id'] == (int)$quaraRes['id']){
                $healthStatusName = "Quarantined";
                $healthStatusColor = "yellow";
            }

            /*Determining color depending of lifecycle status id */
            if((int)$row['lifecycle_status_id'] == (int)$aliveRes['id']){
                $lifeStatusName = "Alive in the farm";
                $lifeStatusColor = "green";
            }elseif((int)$row['lifecycle_status_id'] == (int)$soldRes['id']){
                $lifeStatusName = "Sold";
                $lifeStatusColor = "yellow";
            }elseif((int)$row['lifecycle_status_id'] == (int)$deadRes['id']){
                $lifeStatusName = "Dead";
                $lifeStatusColor = "red";
            }
            ?>
            <tr>
                <td><?= htmlspecialchars($row['tag_name'] ?? 'Undefined') ?></td>
                <td><?= htmlspecialchars($breedName) ?></td>
                <td><?= htmlspecialchars($healthStatusName) ?></td>
                <td><?= htmlspecialchars(number_format($row['milkProduction'] ?? 0, 2)) ?></td>
                <td><?= htmlspecialchars($row['isPregnant'] == 1 ? 'Pregnant' : 'Not Pregnant') ?></td>
                <td><?= htmlspecialchars($lifeStatusName) ?></td>
                <td><button type="button" class="triggerEdit" value="<?= $rowId ?>">Edit</button></td>
                <td><button type="button" class="triggerDelete" value="<?= $rowId ?>">Delete</button></td>
            </tr>
            <?php
        }
    }else{
        ?>
        <tr>
            <td colspan="8">No records found</td>
        </tr>
        <?php
    }

    $rowsHtml = ob_get_clean();

    $totalPages = max(1, ceil($totalRows / $limit));/*I've set the default totalRows as zero so if there are
    no records, the ceil will return 0. This is bad for the UI cz it'll say zero pages. For safety, I'm forcing
    a minimum of 1 (yes, max sets the minimum) so that if nothing is found then it still displays Page 1 of 1*/

    echo json_encode([
        "rows" => $rowsHtml,
        "totalPages" => $totalPages
    ]);
  
}
?>