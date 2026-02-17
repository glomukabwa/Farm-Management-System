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
    <script src="../js/Chart.js"></script>
    <script src="../js/index.js" defer></script>
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
        <div class="logoSpace">
            <img src="../images/black image.jpeg" alt="logo">
        </div>
        <div class="content">
            <div class="left">

                <?php $Fname = $_SESSION['user_name'] ?>
                <h1>Hello <?= htmlspecialchars($Fname) ?></h1>

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

                    <a id="editTasks" href="#">EDIT</a>

                    <div class="editTasksOverLay">
                        <div class="editTasksPopup">
                            <span id="closeEditTasks">&times;</span><!--&times; is the X icon-->

                            
                            <div class="content">
                                <a href="http://localhost/Farm%20Website/php/recordFeeding.php">Edit Morning Meal</a>
                                <a href="http://localhost/Farm%20Website/php/recordFeeding.php">Edit Evening Meal</a>

                                <p>Edit Water Refill:</p>

                                <table class="editTasksTable">
                                    <?php
                                    $getAnimalNames = $conn->query("SELECT * FROM animal_types");
                                    while($namesRow = $getAnimalNames->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td id="name"><?= $namesRow['name'] ?></td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" value="check" name="check">
                                                    <button id="tick">&check;</button><!--This is the tick-->
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" value="cross" name="cross">
                                                    <button id="cross">&times;</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="weekly-sales">
                    <h2>Weekly Sales Report</h2>

                    <?php
                    $weeklySales = [
                        'Monday' => 0,
                        'Tuesday' => 0,
                        'Wednesday' => 0,
                        'Thursday' => 0,
                        'Friday' => 0,
                        'Saturday' => 0,
                        'Sunday' => 0
                    ];

                    $productWeeklySales = $conn->query("SELECT
                                                            DAYNAME(sale_date) as day,
                                                            SUM(total_cost) as total
                                                        FROM product_sales
                                                        WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                                        GROUP BY DAYNAME(sale_date)
                                                        ORDER BY WEEKDAY(sale_date)");
                    /*Ok so above, DAYNAME() returns eg Thursday instead of 12th February
                      SUM(total_cost) calculates the total of all the sales selected but GROUP BY DAYNAME(sale_date) makes is calculate 
                      the total of each day instead of the total of everything.
                      WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) This line means the sale_date from Monday to today:
                                DATE_SUB(x, INTERVAL y DAY) is for subtraction on days
                                The x stands for the current date so eg 2026-02-12
                                As for the y:
                                    WEEKDAY(date) returns a number depending on the date:
                                                Monday returns 0
                                                Tuesday returns 1
                                                Wednesday returns 2
                                                Thursday returns 3
                                                Friday returns 4
                                                Saturday returns 5
                                                Sunday returns 6
                                    WEEKDAY(CURDATE()) will return 3. {The day I'm writing this comment is Thursday 2026-02-12}
                                So now we have: DATE_SUB(2026-02-12, INTERVAL 3 DAY) which basically means, subtract today by 3 days
                                You will get Monday and so it'll be WHERE sale_date >= Monday. This means get the sales from Monday to Today and this
                                ensures your graph only shows Monday to Today. There is a different approach where you can take the last 7 days and that
                                is sale_date >= CURDATE() - INTERVAL 7 DAYS. So it won't always start from Monday, it'll start from the last 7th day
                    */
                    while($prodSalesRow = $productWeeklySales->fetch_assoc()){
                        $dayName = $prodSalesRow['day'];
                        $dayTotal = $prodSalesRow['total'];

                        $weeklySales[$dayName] = $dayTotal;
                    }

                    $animalWeeklySales = $conn->query("SELECT
                                                            DAYNAME(sale_date) as day,
                                                            SUM(total_cost) as total
                                                        FROM animal_sales
                                                        WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                                        GROUP BY DAYNAME(sale_date)
                                                        ORDER BY WEEKDAY(sale_date)");
                    while($animalSalesRow = $animalWeeklySales->fetch_assoc()){
                        $dayName = $animalSalesRow['day'];
                        $dayTotal = $animalSalesRow['total'];
                        
                        $weeklySales[$dayName] += $dayTotal;
                    }
                    ?>

                    <div class="salesContent"
                        data-labels = '<?php echo json_encode(array_keys($weeklySales)); ?>'
                        data-values = '<?php echo json_encode(array_values($weeklySales)) ?>'>
                        <!--A data attribute is a special HTML attribute that allows you to store info on an element. It is not shown on the browser but can be accessed by JS-->
                        <!--Data attributtes always start with data- followed by a name of your choosing as long as u specify that name correctly in JS-->
                        <!--Also, notice that the data attributes are part of the div's opening tag-->
                    </div>

                    <div>
                        <canvas id="salesChart"></canvas>
                    </div>
                    
                </div>

            </div>

            <div class="right">

                <div class="todays-sales">
                    <img src="../icons/sale.png" alt="sale">
                    <div>
                        <h2>Today's Sales</h2>
                        <?php
                        $pSalesStmt = $conn->prepare("SELECT total_cost FROM product_sales WHERE sale_date = ?");
                        $pSalesStmt->bind_param("s", $todaysDate);
                        $pSalesStmt->execute();
                        $pSalesResult = $pSalesStmt->get_result();
                        $pSales = 0.00;
                        while($pSalesRow = $pSalesResult->fetch_assoc()){
                            $pSales += (float) $pSalesRow['total_cost'];
                        }

                        $aSalesStmt = $conn->prepare("SELECT total_cost FROM animal_sales WHERE sale_date = ?");
                        $aSalesStmt->bind_param("s", $todaysDate);
                        $aSalesStmt->execute();
                        $aSalesResult = $aSalesStmt->get_result();
                        $aSales = 0.00;
                        while($aSalesRow = $aSalesResult->fetch_assoc()){
                            $aSales += (float) $aSalesRow['total_cost'];
                        }

                        $totalSales = $pSales + $aSales;
                        ?>
                        <p>Ksh <?= number_format($totalSales, 2) ?></p>
                        <!--I've just learnt that php removes trailing zeros so if you don't tell it that you want a decimal number format, it'll
                        remove the trailing zeros so 200.00 will be 200 and 100.50 will be 100.5
                        Also, apparently for htmlspecialchars is mostly for text and userinput, not so much for numbers. That's what Chat said-->
                    </div>
                </div>

                <div class="alerts">
                    <h2>Alerts</h2>

                    <div class="alertList">
                        <?php
                        $alertStmt = $conn->prepare("SELECT * FROM alerts");
                        $alertStmt->execute();
                        $alertRes = $alertStmt->get_result();
                        if($alertRes->num_rows === 0){
                            ?>
                            <p>No alerts found</p>
                            <?php
                        }else{
                            while($alertRows = $alertRes->fetch_assoc()){
                            ?>
                            <div class="singleAlert">
                                <h3><?= htmlspecialchars($alertRows['title']) ?></h3>
                                <div class="contentAndBtn">
                                    <p><?= htmlspecialchars($alertRows['description']) ?></p>
                                    <a href="#">VIEW</a>
                                </div>
                            </div>

                            <?php
                            }
                        }
                        ?>
                    </div>
                    
                    <a class="seeMore" href="#">See More</a>
                </div>

                
            </div>
        </div>

    </section>
</body>
</html>