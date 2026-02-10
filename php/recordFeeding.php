<?php
require 'admin_auth.php';
include 'config.php';

$success = false;/*You need to define this flag here(it'll be used to indicate successful insertions) and not inside
                    the if below cz POST will not always run so when the page loads(GET request) and u've used this variable in the
                    html(next to the button), u'll get an error that the variable is undefined cz it doesn't exist yet. You
                    also must always define it first(I know this is sth you don't always see the importance of) cz of the same reasons*/
$inStock = true;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $animalId = (int) $_POST['pickAnimal'];
    $feedId = (int) $_POST['feed'];
    $careTaskId = (int) $_POST['mealCategory'];
    $quantity = !empty($_POST['quantity']) ? (float) $_POST['quantity'] : 0.00;/*Note the float cz quantity if of type decimal*/
    $dateTime = $_POST['date'] ?: date('Y-m-d H:i:s');
    $userId = (int) $_SESSION['user_id'];

    $checkFeedsInventory = $conn->prepare("SELECT * FROM feeds WHERE id = ? AND quantity >= ?");
    $checkFeedsInventory->bind_param("id", $feedId, $quantity);
    $checkFeedsInventory->execute();
    $inventoryResult = $checkFeedsInventory->get_result();
    if($inventoryResult->num_rows === 0){
        $inStock = false;

        $title = "Low stock alert";

        /*Getting the feed name for the description*/
        $getFeedName = $conn->prepare("SELECT name FROM feeds WHERE id = ?");
        $getFeedName->bind_param("i", $feedId);
        $getFeedName->execute();
        $getResult = $getFeedName->get_result();
        $getRow = $getResult->fetch_assoc();
        $feedName = $getRow['name'];
        $description = $feedName . " quantity is running low. Please restock.";
        $getFeedName->close();

        $alertDate = date('Y-m-d');
        $alertStmt = $conn->prepare("INSERT INTO alerts(title, description, alert_date, user_id) VALUES (?, ?, ?, ?)");
        $alertStmt->bind_param("sssi", $title, $description, $alertDate, $userId);
        $alertStmt->execute();
        $alertStmt->close();
    }else{
        $stmt = $conn->prepare("INSERT INTO feeding_records(animal_type_id, feed_id, care_task_id, quantity_used, fed_at, recorded_by)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidsi", $animalId, $feedId, $careTaskId, $quantity, $dateTime, $userId);
        $stmt->execute();

        $careTasksStmt = $conn->prepare("INSERT INTO daily_animal_care(animal_type_id, care_task_id, performed_at, performed_by, status)
                                        VALUES (?, ?, ?, ?, ?)");
        $careStatus = true;/*This will be stored as 1 in sql, so in bind_param() below we'll say int. False is stored as 0. 
                        We can also use 1 here in place of true, it'll work the same */
        $careTasksStmt->bind_param("iisii", $animalId, $careTaskId, $dateTime, $userId, $careStatus);
        $careTasksStmt->execute();

        $editFeedsTable = $conn->prepare("UPDATE feeds 
                                        SET quantity = quantity - ?
                                        WHERE id = ? AND quantity >= ?");
        $editFeedsTable->bind_param("did", $quantity, $feedId, $quantity);
        $editFeedsTable->execute();

        if($stmt->affected_rows > 0 && $careTasksStmt->affected_rows > 0 && $editFeedsTable->affected_rows > 0){
            $success = true;
        }
        $stmt->close();
        $careTasksStmt->close();
        $editFeedsTable->close();
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Feeding</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/enterDataForms.css">
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/enterDataForms.js" defer></script>
</head>
<body>
    <section class="sidebar">
        <div class="logo">
            <p>MF</p>
        </div>

        <div class="links">
            <div class="top-links">
                <a href="http://localhost/Farm%20Website/php/index.php"><img src="../icons/category.png" alt="overview">OVERVIEW</a>
                <a href="http://localhost/Farm%20Website/php/enterRecord.php"><img src="../icons/enter_record.png" alt="records">ENTER RECORD</a>
                <a href="#"><img src="../icons/calendar.png" alt="calendar">CALENDAR</a>

                <a href="#" class="products-menu">
                    <div><img src="../icons/product.png" alt="products">PRODUCTS</div>
                    <span class="arrow"> > </span>
                </a>
                
                <div class="products-submenu">
                    <a href="#"><img src="../icons/milk.png" alt="milk">Dairy</a>
                    <a href="#"><img src="../icons/bull.png" alt="bull">Bulls</a>
                    <a href="#"><img src="../icons/chicken.png" alt="chicken">Broilers</a>
                    <a href="#"><img src="../icons/eggs.png" alt="eggs">Eggs</a>
                    <a href="#"><img src="../icons/pig.png" alt="pig">Pigs</a>
                    <a href="#"><img src="../icons/greens.png" alt="greens">Kales</a>
                    <a href="#"><img src="../icons/maize.png" alt="maize">Maize</a>
                </div><br>

                <a href="http://localhost/Farm%20Website/php/farmRecords.php"><img src="../icons/farm_records.png" alt="records">FARM RECORDS</a>

            </div>

            <div class="bottom-links">
                <a href="#"><img src="../icons/profile.png" alt="profile">PROFILE</a>
                <a href="#"><img src="../icons/settings.png" alt="settings">SETTINGS</a>
                <a href="#"><img src="../icons/logout.png" alt="log out">LOG OUT</a>
            </div>
        </div>
    </section>

    <section class="main-content">
        <form method="POST">
            <h1>Record Feeding</h1>

            <div class="select-wrapper">
                <select name="pickAnimal" id="pickAnimal" required>
                    <option value="">Pick Animal</option>
                    <?php
                    $animalTypes = "SELECT * FROM animal_types";
                    $typeResult = $conn->query($animalTypes);
                    while($typeRow = $typeResult->fetch_assoc()){
                        echo '<option value="'.$typeRow['id'].'">'.$typeRow['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="select-wrapper">
                <select name="mealCategory" id="mealCategory" required>
                    <option value="">Meal Category</option>
                    <?php
                    $mealCategories = "SELECT * FROM care_tasks WHERE category = 'Feeding'";
                    $categoriesResult = $conn->query($mealCategories);
                    while($categoriesRow = $categoriesResult->fetch_assoc()){
                        echo '<option value="'.$categoriesRow['id'].'">'.$categoriesRow['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="select-wrapper">
                <select name="feed" id="feed" required>
                    <option value="">Feed</option>
                    <?php
                    $feeds = "SELECT * FROM feeds";
                    $feedsResult = $conn->query($feeds);
                    while($feedsRow = $feedsResult->fetch_assoc()){
                        echo '<option value="'.$feedsRow['id'].'">'.$feedsRow['name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="quantityAndUnit">
                <div class="oneinput" id="quantity">
                    <input type="number" step="0.01" id="quantity-input" name="quantity" placeholder=" " required>
                    <!--step="0.01" allows the input to have up to 2 decimal places-->
                    <label for="quantity">Quantity</label>
                </div>
                <div class="select-wrapper" id="unit">
                    <select name="unit" id="unit" required>
                        <option value="">Unit</option>
                        <?php
                        $units = "SELECT DISTINCT unit FROM feeds";
                        $unitsResult = $conn->query($units);
                        while($unitsRow = $unitsResult->fetch_assoc()){
                            echo '<option value="' .$unitsRow['unit']. '">' .$unitsRow['unit']. '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="date">
                <div>
                    <input type="datetime-local" id="date" name="date"><!--datetime-local makes it provide the date too-->
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <div class="submission">
                <button type="submit">Enter</button>
                <?php 
                $message = '';
                if($success && $inStock){
                    $message = 'Record added successfully!';
                }elseif(!$inStock){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Low Stock Alert!',
                            text: 'The quantity of the product you have selected is not available.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'messageContainer',
                                title: 'messageTitle',
                                content: 'messageText',
                                confirmButton: 'messageConfirmButton'
                            }
                        })
                    </script>
                    <?php
                }
                ?>
                <p id="successMessage"><?= htmlspecialchars($message) ?></p>
            </div>

        </form>
    </section>
</body>
</html>

<?php

if(isset($conn)){
    $conn->close();
}
?>