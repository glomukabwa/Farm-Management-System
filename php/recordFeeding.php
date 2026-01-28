<?php
include 'config.php';
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
            <h1>Record Feeding</h1>

            <!--<div class="selectOption">
                <div class="topOption">Animal type <span class="downwardArrow">▼</span></div>
                <ul class="options">
                    <li data-value="cow" class="first">Cow</li>
                    <li data-value="chicken">Chicken</li>
                    <li data-value="pig" class="last">Pig</li>
                </ul>
                <input type="hidden" name="animalType" id="animalTypeInput">
            </div>-->

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
                    $mealCategories = "SELECT * FROM care_tasks";
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
                    <input type="int" id="quantity" name="quantity" placeholder=" " required>
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

            <button type="submit">Enter</button>
        </form>
    </section>
</body>
</html>