<?php
require 'auth.php';
include 'config.php';

/*Default tally*/
$milkInStock = 0.00;
$weeklySales = 0.00;
$adultCows = 0;
$femaleCalves = 0;

/*Getting the milk in stock from the DB */
$milkIdRes = $conn->query("SELECT id FROM products WHERE name = 'Milk'");
$milkIdRow = $milkIdRes->fetch_assoc();
$milkId = (int)$milkIdRow['id'];
$milkStockRes = $conn->query("SELECT quantity_available FROM product_inventory WHERE product_id = $milkId");
$milkStockRow = $milkStockRes->fetch_assoc();
$milkInStock = $milkStockRow['quantity_available'];

/*Getting the total sales(weekly) from the DB */
$weekTotalSalesStmt = $conn->query("SELECT COALESCE(SUM(total_cost), 0.00) as weeklyTotal
                                FROM product_sales
                                WHERE 
                                    product_id = $milkId AND
                                    sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                ");/*DATE_SUB(x, INTERVAL y DAY) */
                                /*Above COALESCE returns the first non-null value so for example:
                                    COALESCE(null, null, 2, 7, null) will return 5 cz it's the first non-null
                                    value in the set of numbers
                                */
$weekTotalSalesRow = $weekTotalSalesStmt->fetch_assoc();
$weekTotalSales = $weekTotalSalesRow['weeklyTotal'];

/*Getting the total sales(weekly) from the DB */
$adultCowsStmt = $conn->query("SELECT COUNT(*) as count FROM female_cows");
$adultCowsRow = $adultCowsStmt->fetch_assoc();
$adultCows = $adultCowsRow['count'] ?? 0;/*Now u see why the null coalesce operator(??) is important*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dairy.css">
    <script src="../js/main.js" defer></script>
    <script src="../js/dairy.js" defer></script>
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
                <a href="http://localhost/Farm%20Website/php/calendar.php"><img src="../icons/calendar.png" alt="calendar">CALENDAR</a>

                <a href="#" class="products-menu">
                    <div><img src="../icons/product.png" alt="products">PRODUCTS</div>
                    <span class="arrow"> > </span>
                </a>
                
                <div class="products-submenu">
                    <a href="http://localhost/Farm%20Website/php/dairy.php"><img src="../icons/milk.png" alt="milk">Dairy</a>
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
                <a href="#" id="logoutNav"><img src="../icons/logout.png" alt="log out">LOG OUT</a>
            </div>
        </div>
    </section>

    <section class="main-content">
        <div class="tally">
            <div>
                <h2>Milk In Stock</h2>
                <p><?= $milkInStock ?></p>
                <p>Litres</p>
            </div>

            <div>
                <h2>Total Sales (Weekly) </h2>
                <p>Ksh</p>
                <p><?= $weekTotalSales ?></p>
            </div>

            <div>
                <h2>Cow Population</h2>
                <p></p>
                <p><?= $adultCows ?></p>
            </div>

            <div>
                <h2>Female Calves</h2>
                <p></p>
                <p><?= $femaleCalves ?></p>
            </div>
        </div>

        <div class="graphs">
            <div class="productionGraph">
                <h2>Milk Production</h2>
                <button>Update Production Records</button>
            </div>

            <div class="salesGraph">
                <h2>Milk Sales</h2>
                <button>Update Sales Records</button>
            </div>
        </div>

        <div class="cowsTable">
            <h2>Cows(Female) Records</h2>

            <div class="topControls">
                <div class="right">
                    <div class="select-wrapper">
                        <select name="searchCriteria" id="searchCriteria">
                            <option value="">-- Search By --</option>
                            <option value="name">Name</option>
                            <option value="healthStatus">Health Status</option>
                            <option value="milkProd">Milk Production</option>
                            <option value="isPreg">Pregnancy Status</option>
                            <option value="lifeStatus">Life Status</option>
                        </select>
                    </div>

                    <div class="oneinput">
                        <input type="text" id="searchValue" name="searchValue" placeholder=" ">
                        <label for="searchValue">Enter Search Value</label>
                    </div>

                    <button>SEARCH</button>
                </div>

                <div class="left">
                    <button>MORE OPTIONS</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Health Status</th>
                        <th>Milk Production(Ltrs)</th>
                        <th>Pregnancy Status</th>
                        <th>Life Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $cowsStmt = $conn->prepare("SELECT * FROM female_cows");
                    $cowsStmt->execute();
                    $cowRes = $cowsStmt->get_result();

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

                    if($cowRes->num_rows > 0){
                        while($cowsRow = $cowRes->fetch_assoc()){
                            /*Storing the id of the respective field for future use*/
                            $rowId = $cowsRow['id'];

                            $breedName = '';
                            $breedId = (int)$cowsRow['breed_id'];
                            if($cowsRow['breed_id'] != null){
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
                            if((int)$cowsRow['health_status_id'] == (int)$healthyRes['id']){
                                $healthStatusName = "Healthy";
                                $healthStatusColor = "green";
                            }elseif((int)$cowsRow['health_status_id'] == (int)$sickRes['id']){
                                $healthStatusName = "Sick";
                                $healthStatusColor = "red";
                            }elseif((int)$cowsRow['health_status_id'] == (int)$quaraRes['id']){
                                $healthStatusName = "Quarantined";
                                $healthStatusColor = "yellow";
                            }

                            /*Determining color depending of lifecycle status id */
                            if((int)$cowsRow['lifecycle_status_id'] == (int)$aliveRes['id']){
                                $lifeStatusName = "Alive in the farm";
                                $lifeStatusColor = "green";
                            }elseif((int)$cowsRow['lifecycle_status_id'] == (int)$soldRes['id']){
                                $lifeStatusName = "Sold";
                                $lifeStatusColor = "yellow";
                            }elseif((int)$cowsRow['lifecycle_status_id'] == (int)$deadRes['id']){
                                $lifeStatusName = "Dead";
                                $lifeStatusColor = "red";
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($cowsRow['tag_name'] ?? 'Undefined') ?></td>
                                <td><?= htmlspecialchars($breedName) ?></td>
                                <td><?= htmlspecialchars($healthStatusName) ?></td>
                                <td><?= htmlspecialchars(number_format($cowsRow['milkProduction'] ?? 0, 2)) ?></td>
                                <td><?= htmlspecialchars($cowsRow['isPregnant'] == 1 ? 'Pregnant' : 'Not Pregnant') ?></td>
                                <td><?= htmlspecialchars($lifeStatusName) ?></td>
                                <td><button type="button" class="triggerEdit" value="<?= $rowId ?>">Edit</button></td>
                                <td><button type="button" class="triggerDelete" value="<?= $rowId ?>">Delete</button></td>
                            </tr>
                            <?php
                        }
                    }else{
                        ?>
                        <tr><td colspan="8">No Records Found</td></tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <div class="editOverlay">
                <form method="POST">
                    <span id="closePopup">&times;</span>
                    <span id="deleteBtn"><img id="dustbin" src="../icons/delete.png" alt="trashcan"></span>

                    <div class="oneinput">
                        <input type="text" id="Name" name="Name" placeholder=" " required>
                        <label for="Name">Name</label>
                    </div>

                    <div class="select-wrapper">
                        <select name="breed" id="breed">
                            <option value="">Breed</option>
                            <?php 
                            $breeds = "SELECT * FROM breeds";
                            $breedResult = $conn->query($breeds);
                            while($breedRow = $breedResult->fetch_assoc()){
                                echo '<option value="'.$breedRow['id'].'">'.$breedRow['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="select-wrapper">
                        <select name="healthStatus" id="healthStatus" required>
                            <option value="">Health Status</option>
                            <?php 
                            $healthStatuses = "SELECT * FROM animal_statuses";
                            $healthResult = $conn->query($healthStatuses);
                            while($healthRow = $healthResult->fetch_assoc()){
                                echo '<option value="'.$healthRow['id'].'">'.$healthRow['status_name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="oneinput">
                        <input type="number" id="milkProd" name="milkProd" placeholder=" " required>
                        <label for="milkProd">Milk Production</label>
                    </div>

                    <div class="select-wrapper">
                        <select name="pregStatus" id="pregStatus" required>
                            <option value="">Pregnancy Status</option>
                            <option value="0">Not Pregnant</option>
                            <option value="1">Pregnant</option>
                        </select>
                    </div>

                    <div class="select-wrapper">
                        <select name="lifeStatus" id="lifeStatus" required>
                            <option value="">Life Status</option>
                            <?php 
                            $lifeStatuses = "SELECT * FROM animal_lifecycle_statuses";
                            $lifeResult = $conn->query($lifeStatuses);
                            while($lifeRow = $lifeResult->fetch_assoc()){
                                echo '<option value="'.$lifeRow['id'].'">'.$lifeRow['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <button class="actualEdit">EDIT</button>
                </form>
            </div>

            <div class="deleteRowOverlay">
                <form action="" method="POST" id="">
                    <img id="deleteLogo" src="../icons/delete.png" alt="trashcan">
                    <p>Are you sure you want to delete this row?</p>
                    <div>
                        <button type="button" id="cancelDeleteRow">CANCEL</button>
                        <button type="submit" id="actualDelete">DELETE</button>
                    </div>
                </form>
            </div>

            <div class="controls">
                <form method="GET">
                    <input type="hidden" name="page" value="1">

                    <label for="limit">
                        Show rows per page
                        <select name="limit" id="limit" onchange="this.form.submit()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                        <span class="arrow">⌄</span>
                    </label>
                </form>

                <div class="arrows">
                    <a href="#">&lt;</a>

                    <span>Page of </span>

                    <a href="#">&gt;</a>
                </div>
            </div>
        </div>


    </section>

    <div id="logoutModal" class="logoutmodal">
        <form action="logout.php" method="POST" id="logoutModalContent">
            <img src="../icons/logout.png" alt="logout">
            <h1>Log Out</h1>
            <p>Are you sure you want to log out?</p>
            <div>
                <button type="button" id="cancelLogout">CANCEL</button>
                <button type="submit">LOG OUT</button>
            </div>
        </form>
    </div>
</body>
</html>