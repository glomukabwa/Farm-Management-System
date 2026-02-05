<?php
require 'admin_auth.php';
include 'config.php';

$success = false;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $purchaseName = $_POST['purchaseName'] ?: '';
    $purCategory = (int) $_POST['purchaseCategory'];
    $quantity = (float) $_POST['quantity'] ?: 0.00;
    $unitCost = (float) $_POST['unitCost'] ?: 0.00;
    $supplierName = $_POST['supplierName'] ?: '';
    $supplierPNumber = $_POST['supplierPNumber'] ?: '';
    $purDate = $_POST['date'] ?: date('Y-m-d H:i:s');
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO purchases (purchase_name, purchase_category_id, quantity, unit_cost, supplier_name, supplier_phone_number, purchase_date, recorded_by)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siddsssi", $purchaseName, $purCategory, $quantity, $unitCost, $supplierName, $supplierPNumber, $purDate, $userId);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        $success = true;
    }
    $stmt->close();

    if($purCategory == 1){
        $alertStmt = $conn->prepare("INSERT INTO alerts (title, description, alert_date, user_id)
                    VALUES (?, ?, ?, ?)");

        $title = 'Update Animal Records';
        $description = 'New animals have been purchased. Please create new records of the animals.

                        Link: http://localhost/Farm%20Website/php/enterAnimal.php';

        $alertStmt->bind_param("sssi", $title, $description, $purDate, $userId);
        $alertStmt->execute();
    }

    if($purCategory == 2){
        $alertStmt2 = $conn->prepare("INSERT INTO alerts (title, description, alert_date, user_id)
                    VALUES (?, ?, ?, ?)");

        $title = 'Update Product Inventory';
        $description = 'New crops have been purchased. Please update the products inventory.

                        Link:http://localhost/Farm%20Website/php/recordProduction.php';

        $alertStmt2->bind_param("sssi", $title, $description, $purDate, $userId);
        $alertStmt2->execute();
    }

    if($purCategory == 3){
        $alertStmt3 = $conn->prepare("INSERT INTO alerts (title, description, alert_date, user_id)
                    VALUES (?, ?, ?, ?)");

        $title = 'Update Feed Records';
        $description = 'New feeds have been purchased. Please update the feeds inventory.

                        Link:http://localhost/Farm%20Website/php/updateFeedsInventory.php';

        $alertStmt3->bind_param("sssi", $title, $description, $purDate, $userId);
        $alertStmt3->execute();
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Farm Purchase</title>
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
        <form method="POST">
            <h1>Enter Farm Purchase</h1>

            <div class="optionalInput"><!--I am just borrowing the styling of optional but this isn't optional-->
                <div class="oneinput">
                    <input type="text" name="purchaseName" id="purchaseName" placeholder=" " required>
                    <label for="purchaseName">Purchase Name</label>
                </div>
                <label for="" id="message">* <span id="text">Enter what was bought by the farm</span></label>
            </div>

            <div class="select-wrapper">
                <select name="purchaseCategory" id="purchaseCategory" required>
                    <option value="">Purchase Category</option>
                    <?php
                    $purchaseCategories = "SELECT * FROM purchase_categories";
                    $purchaseResult = $conn->query($purchaseCategories);
                    while($purchaseRow = $purchaseResult->fetch_assoc()){
                        echo '<option value="'.$purchaseRow['id'].'">'.$purchaseRow['name'].'</option>'; 
                    }
                    ?>
                </select>
            </div>

            <div class="oneinput" id="quantity">
                <input type="number" id="quantity-input" name="quantity" placeholder=" " required>
                <label for="quantity">Quantity</label>
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
                <label class="labelTwo">Kshs <span id="total-cost">0</span></label>
            </div>

            <div class="supplierDetails">
                <div class="oneinput" id="supplierName">
                    <input type="text" id="supplierName" name="supplierName" placeholder=" " required>
                    <label for="supplierName">Supplier Name</label>
                </div>

                <div class="optionalInput">
                    <div class="oneinput">
                        <div class="oneinput" id="supplierPNumber">
                            <input type="text" id="supplierPNumber" name="supplierPNumber" placeholder=" " required>
                            <label for="supplierPNumber">Supplier Phone Number</label>
                        </div>
                    </div>
                    <label for="" id="message">* <span id="text">Optional</span></label>
                </div>
            </div>

            <div class="date">
                <div>
                    <input type="datetime-local" id="date" name="date">
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <div class="submission">
                <button type="submit" class="purchaseButton">Enter</button>
                <?php 
                $message = '';
                if($success){
                    $message = 'Record added successfully!';
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