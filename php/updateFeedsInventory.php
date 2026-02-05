<?php
require 'admin_auth.php';
include 'config.php';

$success = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Feeds Inventory</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/enterDataForms.css">
    <script src="../js/main.js" defer></script>
    <script src="../js/recordOptions.js" defer></script>
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
            <h1>Update Feeds Inventory</h1>

            <div class="select-wrapper">
                <select name="feed" id="feedSelect" required>
                    <option value="">Feed</option>
                    <option value="New Feed">-- New Feed --</option>
                    <?php
                    $feeds = "SELECT * FROM feeds";
                    $feedsResult = $conn->query($feeds);
                    while($feedsRow = $feedsResult->fetch_assoc()){
                        echo '<option value="'.$feedsRow['id'].'">'.$feedsRow['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="existingFeed">

                <div class="quantityAndUnit">
                    <div class="oneinput" id="quantity">
                        <input type="number" id="quantity-input" name="quantity" placeholder=" " required>
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

                <div class="expiryDate">
                    <label for="date" id="expiryLabel">Expiry Date: </label>
                    <div class="date">
                        <div>
                            <input type="date" id="date" name="date">
                        </div>
                        <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
                    </div>
                </div>
            </div>

            <div class="newFeed">
                <div class="oneinput">
                    <input type="text" name="feedName" id="feedName" placeholder=" " required>
                    <label for="feedName">Feed Name</label>
                </div>

                <div class="quantityAndUnit">
                    <div class="oneinput" id="quantity">
                        <input type="number" id="quantity-input" name="quantity" placeholder=" " required>
                        <label for="quantity">Quantity</label>
                    </div>
                    <div class="oneinput">
                        <input type="text" id="unit" name="unit" placeholder=" " required>
                        <label for="unit">Unit</label>
                    </div>
                </div>

                <div class="optionalInput"><!--This is not optional, I am just borrowing the styling-->
                    <div class="oneinput">
                        <input type="number" id="reorderLevel" name="reorderLevel" placeholder=" " required>
                        <label for="reorderLevel">Reorder Level</label>
                    </div>
                    <label for="" id="message">* <span id="text">Please enter minimum quantity for a restock alert</span></label>
                </div>

            </div>

            <div class="submission">
                <button type="submit">Enter</button>
                <?php 
                $message = '';
                if($success){
                    $message = 'Record updated successfully!';
                }
                ?>
                <p id="successMessage"><?= htmlspecialchars($message) ?></p>
            </div>
        </form>

        
    </section>
</body>
</html>