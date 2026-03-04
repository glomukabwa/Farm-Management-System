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
            </div>

            <div class="salesGraph">
                <h2>Milk Sales</h2>
            </div>
        </div>

        <div class="cowsTable">
            <h2>Cows(Female) Records</h2>
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