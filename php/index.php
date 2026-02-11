<?php
require 'auth.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/main.js" defer></script><!--defer here means:wait until the HTML is parsed before running your JS, so you don’t 
    need DOMContentLoaded(an event that can be added to an action listener) if you use defer.-->
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
        <?php $Fname = $_SESSION['user_name'] ?>
        <h1>Hello <?= htmlspecialchars($Fname) ?></h1>
        
        <div class="content">
            <div class="left">

                <div class="feedings">
                    <h2>Today's Feeding</h2>

                    <table>
                        <tr>
                            <th></th>
                            <th>Morning Meal</th>
                            <th>Evening Meal</th>
                            <th>Water Refill</th>
                        </tr>
                        <?php
                        $getAnimals = $conn->query("SELECT * FROM animal_types");
                        while($getAnimalRow = $getAnimals->fetch_assoc()){
                            ?>
                            <tr>
                                <td id="animal"><?= $getAnimalRow['name'] ?></td>

                                <!--MORNING MEAL-->
                                <?php
                                $morningMeal = false;

                                $morningStmt = $conn->prepare("SELECT status FROM daily_animal_care 
                                                               WHERE care_task_id = 1 
                                                               AND animal_type_id = ? 
                                                               AND performed_at = ?");
                                $todaysDate = date('Y-m-d');
                                $morningStmt->bind_param("is", $getAnimalRow['id'], $todaysDate);
                                $morningStmt->execute();
                                $morningResult = $morningStmt->get_result();
                                if($morningResult->num_rows > 0){
                                    $morningRow = $morningResult->fetch_assoc();
                                    if($morningRow['status'] === 1){
                                        $morningMeal = true;
                                    }
                                }
                                
                                if(!$morningMeal){
                                    ?>
                                    <td>✕</td>
                                    <?php
                                }else{
                                    ?>
                                    <td>✔</td>
                                    <?php
                                }
                                

                                /*EVENING MEAL*/
                                $eveningMeal = false;

                                $eveningStmt = $conn->prepare("SELECT status FROM daily_animal_care 
                                                               WHERE care_task_id = 2
                                                               AND animal_type_id = ? 
                                                               AND performed_at = ?");
                                
                                $eveningStmt->bind_param("is", $getAnimalRow['id'], $todaysDate);
                                $eveningStmt->execute();
                                $eveningResult = $eveningStmt->get_result();
                                if($eveningResult->num_rows > 0){
                                    $eveningRow = $eveningResult->fetch_assoc();
                                    if($eveningRow['status'] === 1){
                                        $eveningMeal = true;
                                    }
                                }

                                if(!$eveningMeal){
                                    ?>
                                    <td>✕</td>
                                    <?php
                                }else{
                                    ?>
                                    <td>✔</td>
                                    <?php
                                }


                                /*WATER REFILL*/
                                $waterRefill = false;

                                $waterStmt = $conn->prepare("SELECT status FROM daily_animal_care 
                                                               WHERE care_task_id = 3
                                                               AND animal_type_id = ? 
                                                               AND performed_at = ?");
                                
                                $waterStmt ->bind_param("is", $getAnimalRow['id'], $todaysDate);
                                $waterStmt ->execute();
                                $waterResult = $waterStmt ->get_result();
                                if($waterResult->num_rows > 0){
                                    $waterRow = $waterResult->fetch_assoc();
                                    if($waterRow['status'] === 1){
                                        $waterRefill = true;
                                    }
                                }

                                if(!$waterRefill){
                                    ?>
                                    <td>✕</td>
                                    <?php
                                }else{
                                    ?>
                                    <td>✔</td>
                                    <?php
                                }
                                ?>
    
                            </tr>
                            <?php
                        }
                        ?>

                    </table>

                    <a href="#">EDIT</a>
                </div>

                <div class="weekly-sales">
                    <h2>Weekly Sales Report</h2>
                </div>
            </div>

            <div class="right">
                <div class="alerts">
                    <h2>Alerts</h2>
                    <p>No alerts found</p>
                    <a href="#">See More</a>
                </div>

                <div class="todays-sales">
                    <h2>Today's Sales</h2>
                    <div>
                        <img src="../icons/sale.png" alt="sale">
                        <p>Ksh 0</p>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>
</html>