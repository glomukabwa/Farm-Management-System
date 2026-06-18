<?php
require 'auth.php';
include 'config.php';

/*Default tally*/
$eggsInStock = 0;
$weekTotalSales = 0.00;
$adultHens = 0;
$femaleChicks = 0;

/*Getting the eggs in stock from the DB */
$eggsIdRes = $conn->query("SELECT id FROM products WHERE name = 'Eggs'");
$eggsIdRow = $eggsIdRes->fetch_assoc();
$eggsId = (int)$eggsIdRow['id'];
$eggsStockRes = $conn->query("SELECT quantity_available FROM product_inventory WHERE product_id = $eggsId");
$eggsStockRow = $eggsStockRes->fetch_assoc();
$eggsInStock = $eggsStockRow['quantity_available'];

/*Getting the total sales(weekly) from the DB */
$weekTotalSalesStmt = $conn->query("SELECT COALESCE(SUM(total_cost), 0.00) as weeklyTotal
                                FROM product_sales
                                WHERE 
                                    product_id = $eggsId AND
                                    sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                ");/*DATE_SUB(x, INTERVAL y DAY) */
                                /*Above COALESCE returns the first non-null value so for example:
                                    COALESCE(null, null, 2, 7, null) will return 2 cz it's the first non-null
                                    value in the set of numbers
                                */
$weekTotalSalesRow = $weekTotalSalesStmt->fetch_assoc();
$weekTotalSales = $weekTotalSalesRow['weeklyTotal'];

/*Getting the total sales(weekly) from the DB */
$adultHensStmt = $conn->query("SELECT COUNT(*) as count FROM hens");
$adultHensRow = $adultHensStmt->fetch_assoc();
$adultHens = $adultHensRow['count'] ?? 0;/*Now u see why the null coalesce operator(??) is important*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eggs</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/products.css">
    <script src="../js/main.js" defer></script>
    <script src="../js/Chart.js"defer></script>
    <script src="../js/eggs.js" defer></script>
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
                    <a href="http://localhost/Farm%20Website/php/eggs.php"><img src="../icons/eggs.png" alt="eggs">Eggs</a>
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
                <h2>Eggs In Stock</h2>
                <p><?= $eggsInStock ?></p>
                <p>Trays</p>
            </div>

            <div>
                <h2>Total Sales (Weekly) </h2>
                <p>Ksh</p>
                <p><?= $weekTotalSales ?></p>
            </div>

            <div>
                <h2>Hen Population</h2>
                <p></p>
                <p><?= $adultHens ?></p>
            </div>

            <div>
                <h2>Female Chicks</h2>
                <p></p>
                <p><?= $femaleChicks ?></p>
            </div>
        </div>


        <div class="graphs">
            <div class="productionGraph">
                <h2>Egg Production</h2>

                <?php
                $weeklyProduction = [
                    'Monday' => 0,
                    'Tuesday' => 0,
                    'Wednesday' => 0,
                    'Thursday' => 0,
                    'Friday' => 0,
                    'Saturday' => 0,
                    'Sunday' => 0
                ];

                $eggsProdStatement = $conn->prepare("SELECT 
                                                        DAYNAME(created_at) AS day,
                                                        SUM(quantity) as total
                                                    FROM production_records
                                                    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                                        AND product_id = ?
                                                    GROUP BY DAYNAME(created_at)
                                                    ORDER BY WEEKDAY(created_at)");
                $eggsProdStatement->bind_param("i", $eggsId);
                $eggsProdStatement->execute();
                $eggsProdStatementRes = $eggsProdStatement->get_result();
                
                while($eggsProdRow = $eggsProdStatementRes->fetch_assoc()){
                    $day = $eggsProdRow['day'];
                    $totalQnty = $eggsProdRow['total'];

                    $weeklyProduction[$day] = $totalQnty;
                }

                $weeklySales = [
                    'Monday' => 0,
                    'Tuesday' => 0,
                    'Wednesday' => 0,
                    'Thursday' => 0,
                    'Friday' => 0,
                    'Saturday' => 0,
                    'Sunday' => 0
                ];

                $eggSalesStmt = $conn->prepare("SELECT
                                                    DAYNAME(sale_date) as day,
                                                    SUM(total_cost) as total
                                                FROM product_sales
                                                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                                    AND product_id = ?
                                                GROUP BY DAYNAME(sale_date)
                                                ORDER BY WEEKDAY(sale_date)");
                $eggSalesStmt->bind_param("i", $eggsId);
                $eggSalesStmt->execute();
                $eggSalesStmtRes = $eggSalesStmt->get_result();
                
                while($eggSalesRow = $eggSalesStmtRes->fetch_assoc()){
                    $day = $eggSalesRow['day'];
                    $total = $eggSalesRow['total'];

                    $weeklySales[$day] = $total;
                }

                ?>

                <div class="eggsProdChartContent"
                    data-labels = '<?php echo json_encode(array_keys($weeklyProduction)) ?>'
                    data-values = '<?php echo json_encode(array_values($weeklyProduction))?>'>
                </div>

                <div>
                    <canvas id="eggsProdChart"></canvas>
                </div>
                <a id="updatebtn" href="http://localhost/Farm%20Website/php/recordProduction.php">Update Production Records</a>
            </div>

            <div class="salesGraph">
                <h2>Egg Sales</h2>

                <div class="eggsSalesChartContent"
                    data-labels= '<?php echo json_encode(array_keys($weeklySales))?>'
                    data-values='<?php echo json_encode(array_values($weeklySales))?>'>
                </div>

                <div>
                    <canvas id="eggsSalesChart"></canvas>
                </div>
                <a id="updatebtn"  href="http://localhost/Farm%20Website/php/enterSale.php">Update Sales Records</a>
            </div>
        </div>


        <div class="cowsTable" id="cowsTableSection"><!--I'll use the id for the page reload when I want it to 
            scroll down to this location-->
            <h2>Hen Records</h2>

            <div class="topControls">
                <form class="right" method="POST" id="searchForm">
                    <div class="select-wrapper" id="searchCirteria">
                        <select name="searchCriteria" id="searchCriteria">
                            <option value="">-- Search By --</option>
                            <option value="name">Name</option>
                            <option value="breed">Breed</option>
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

                    <button id="searchSthBtn" type="submit">SEARCH</button>
                </form>

                <div class="left">
                    <button id="moreOptions">MORE OPTIONS</button>

                    <ul class="optionsMenuBar">
                        <li>Add new cow</li>
                        <li>Select all rows <span id="slctIndication"></span></li>
                        <li>Delete</li>
                    </ul>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Health Status</th>
                        <th>Life Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody id="table-body">
                    <?php
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

                    if($page < 1){
                        $page = 1;
                    }

                    if(!in_array($limit, [10,20,30])){
                        $limit = 10;
                    }

                    $offset = ($page - 1) * $limit;

                    $hensStmt = $conn->prepare("SELECT * FROM hens
                                                ORDER BY id ASC
                                                LIMIT ?,?");
                    $hensStmt->bind_param("ii", $offset, $limit);
                    $hensStmt->execute();
                    $henRes = $hensStmt->get_result();

                    $totalRowsStmt = $conn->prepare("SELECT COUNT(*) AS total FROM hens");
                    $totalRowsStmt->execute();
                    $totalRowsRes = $totalRowsStmt->get_result();
                    $totalRowsRow = $totalRowsRes->fetch_assoc();
                    $totalRowsValue = $totalRowsRow['total'];
                    $totalPages = ceil($totalRowsValue / $limit);

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

                    if($henRes->num_rows > 0){
                        while($hensRow = $henRes->fetch_assoc()){
                            /*Storing the id of the respective field for future use*/
                            $rowId = $hensRow['id'];

                            $breedName = '';
                            $breedId = (int)$hensRow['breed_id'];
                            if($hensRow['breed_id'] != null){
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
                            if((int)$hensRow['health_status_id'] == (int)$healthyRes['id']){
                                $healthStatusName = "Healthy";
                                $healthStatusColor = "green";
                            }elseif((int)$hensRow['health_status_id'] == (int)$sickRes['id']){
                                $healthStatusName = "Sick";
                                $healthStatusColor = "red";
                            }elseif((int)$hensRow['health_status_id'] == (int)$quaraRes['id']){
                                $healthStatusName = "Quarantined";
                                $healthStatusColor = "yellow";
                            }

                            /*Determining color depending of lifecycle status id */
                            if((int)$hensRow['lifecycle_status_id'] == (int)$aliveRes['id']){
                                $lifeStatusName = "Alive in the farm";
                                $lifeStatusColor = "green";
                            }elseif((int)$hensRow['lifecycle_status_id'] == (int)$soldRes['id']){
                                $lifeStatusName = "Sold";
                                $lifeStatusColor = "yellow";
                            }elseif((int)$hensRow['lifecycle_status_id'] == (int)$deadRes['id']){
                                $lifeStatusName = "Dead";
                                $lifeStatusColor = "red";
                            }
                        ?>
                            <tr>
                                <td><input type="checkbox" name="rowSelected" value="<?= $rowId ?>"></td>
                                <td><?= htmlspecialchars($hensRow['tag_name'] ?? 'Undefined') ?></td>
                                <td><?= htmlspecialchars($breedName) ?></td>
                                <td><?= htmlspecialchars($healthStatusName) ?></td>
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

            <div class="addAnimalOverlay">
                <form method="POST" id="enterNewAnimal">
                    
                    <span id="closeAddAnimal" class="closePopup">&times;</span>

                    <h2>Add New Cow</h2>

                    <div class="oneinput">
                        <input type="number" id="quantity" name="quantity" placeholder=" " required>
                        <label for="quantity">Quantity</label>
                    </div>

                    <div>
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
                        <label id="message">* <span id="text">Optional</span></label>
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

                    <div class="date">
                        <div>
                            <input type="date" id="date" name="date">
                        </div>
                        <label id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
                    </div>

                    <div class="submission">
                        <button type="submit">Enter</button>
                        <span id="successMessage"></span>
                    </div>

                </form>
            </div>

            <div class="editOverlay">
                <form method="POST">
                    <span id="closeEditPopup" class="closePopup">&times;</span>
                    <span id="deleteBtn"><img id="dustbin" src="../icons/delete.png" alt="trashcan"></span>

                    <div>
                    <div class="oneinput">
                        <input type="text" id="Name" name="Name" placeholder=" " >
                        <!--I'm not making it required so that a user can remove the name of a cow and leave it as 
                            undefined for whatever reason they have. I'm allowing this cz the tagName in the DB is
                            NULLABLE so I want to allow the users to have the option of having undefined animals
                            until they choose to name them
                        -->
                        <label for="Name">Name</label>
                    </div>
                    <span id="validNameIndicator"></span>
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
                        <input type="number" step="0.01" id="milkProd" name="milkProd" placeholder=" " required>
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

                    <button type="button" class="actualEdit">EDIT</button>
                </form>
            </div>

            <div class="deleteRowOverlay">
                <form action="" method="POST" id="">
                    <img id="deleteLogo" src="../icons/delete.png" alt="trashcan">
                    <p>Are you sure you want to delete this row?</p>
                    <div>
                        <button type="button" id="cancelDeleteRow">CANCEL</button>
                        <button type="button" id="actualDelete">DELETE</button>
                    </div>
                </form>
            </div>

            <div class="controls">
                <form method="GET">
                    <input type="hidden" name="page" value="1">

                    <label for="limit">
                        Show rows per page
                        <select name="limit" id="limit" onchange="this.form.submit()">
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                            <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30</option>
                        </select>
                        <span class="arrow">⌄</span>
                    </label>
                </form>

                <div class="arrows">
                    <?php
                    if($page > 1){
                        ?>
                        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">&lt;</a>
                        <?php
                    }
                    ?>

                    <span>Page <?= $page ?> of <?= $totalPages ?></span>
                    
                    <?php
                    if($page < $totalPages){
                        ?>
                        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">&gt;</a>
                        <?php
                    }
                    ?>
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