<?php
include 'config.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Animal</title>
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
            <h1>Enter Animal</h1>

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
                <select name="animalType" id="animalType" required>
                    <option value="">Animal Type</option>
                    <?php
                    $animalTypes = "SELECT * FROM animal_types";
                    $typeResult = $conn->query($animalTypes);
                    while($typeRow = $typeResult->fetch_assoc()){
                        echo '<option value="'.$typeRow['name'].'">'.$typeRow['name'].'</option>'; 
                    }
                    ?>
                </select>
            </div>

            <div>
                <div class="select-wrapper">
                    <select name="breed" id="breed" required>
                        <option value="">Breed</option>
                        <?php 
                        $breeds = "SELECT * FROM breeds";
                        $breedResult = $conn->query($breeds);
                        while($breedRow = $breedResult->fetch_assoc()){
                            echo '<option value="'.$breedRow['name'].'">'.$breedRow['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <label for="" id="message">* <span id="text">Optional</span></label>
            </div>

            <div class="optionalInput">
                <div class="oneinput">
                    <input type="text" id="tagNumber" name="tagNumber" placeholder=" " required>
                    <label for="tagNumber">Tag Number</label>
                </div>
                <label for="" id="message">* <span id="text">Optional</span></label>
            </div>

            <div class="select-wrapper">
                <select name="gender" id="gender" required>
                    <option value="">Gender</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                </select>
            </div>

            <div class="select-wrapper">
                <select name="healthStatus" id="healthStatus" required>
                    <option value="">Health Status</option>
                    <?php 
                    $healthStatuses = "SELECT * FROM animal_statuses";
                    $healthResult = $conn->query($healthStatuses);
                    while($healthRow = $healthResult->fetch_assoc()){
                        echo '<option value="'.$healthRow['status_name'].'">'.$healthRow['status_name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit">Enter</button>
        </form>
    </section>
</body>
</html>