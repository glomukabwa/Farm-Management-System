<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Sale</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/enterDataForms.css">
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
        <form method="GET">
            <h1>Enter Sale</h1>

            <div class="select-wrapper">
                <select name="productName" id="productName" required>
                    <option value="">Product Name</option>
                    <?php
                    $products = "SELECT * FROM products";
                    $productsResult = $conn->query($products);
                    while($productsRow = $productsResult->fetch_assoc()){
                        echo '<option value="'.$productsRow['id'].'">'.$productsResult['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="quantityAndUnit">
                <div class="oneinput" id="quantity">
                    <input type="number" id="quantity" name="quantity" placeholder=" " required>
                    <label for="quantity">Quantity</label>
                </div>
                <div class="select-wrapper" id="unit">
                    <select name="unit" id="unit" required>
                        <option value="">Unit</option>
                        <option value="Kgs">Kgs</option>
                        <option value="Bales">Bales</option>
                        <option value="Sacks">Sacks</option>
                    </select>
                </div>
            </div>

            <div class="optionalInput"><!--I am just borrowing the styling of optional but this isn't optional-->
                <div class="oneinput">
                    <input type="text" name="unitCost" id="unitCost" placeholder=" " required>
                    <label for="unitCost">Unit Cost</label>
                </div>
                <label for="" id="message">* <span id="text">Enter cost of one good</span></label>
            </div>

            <div class="totalCost">
                <label>Total Cost:</label>
                <label class="labelTwo">Kshs 0</label>
            </div>

            <div class="date">
                <div>
                    <input type="date" id="date" name="date">
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <button type="submit">Enter</button>
        </form>
    </section>
</body>
</html>