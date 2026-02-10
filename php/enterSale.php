<?php
require 'admin_auth.php';
include 'config.php';

$success = false;
$inStock = true;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $choice = $_POST['saleChoice'];
    if($choice === 'productChoice'){
        $productId = (int) $_POST['productName'];
        $quantity = (float) $_POST['quantity'] ?: 0.00;
        $unitCost = (float) $_POST['unitCost'] ?: 0.00;
        $date = $_POST['date'] ?: date('Y-m-d H:i:s');
        $user = (int) $_SESSION['user_id'];
        
        $checkInventory = $conn->prepare("SELECT * FROM product_inventory WHERE product_id = ? AND quantity_available >= ?");
        $checkInventory->bind_param("id", $productId, $quantity);
        $checkInventory->execute();
        $inventoryResult = $checkInventory->get_result();
        if($inventoryResult->num_rows === 0){
            $inStock = false;
        }else{
            $stmt = $conn->prepare("INSERT INTO product_sales (product_id, quantity, unit_cost, sale_date, sold_by)
                                VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iddsi", $productId, $quantity, $unitCost, $date, $user);
            $stmt->execute();

            $productUpdate = $conn->prepare("UPDATE product_inventory
                                        SET quantity_available = quantity_available - ?
                                        WHERE product_id = ? AND quantity_available >= ?");
            $productUpdate->bind_param("did", $quantity, $productId, $quantity);
            $productUpdate->execute();
        
            if($stmt->affected_rows > 0 && $productUpdate->affected_rows > 0){
                $success = true;
            }

            $stmt->close();
            $productUpdate->close();
        }
        $checkInventory->close();

    }elseif($choice === 'animalChoice'){
        $animalid = (int) $_POST['animalType'];
        $animalGender = $_POST['gender'];
        $animalQuantity = (int) $_POST['quantity'] ?: 0;
        $aniUnitCost = (float) $_POST['unitCost'] ?: 0.00;
        $saleDate = $_POST['date'] ?: date('Y-m-d H:i:s');
        $userId = (int) $_SESSION['user_id'];

        $checkAnimalInventory = $conn->prepare("SELECT * FROM animals WHERE lifecycle_status_id = 1 AND animal_type_id = ? AND gender = ?");
        $checkAnimalInventory->bind_param("is", $animalid, $animalGender);
        $checkAnimalInventory->execute();
        $animalInventoryResult = $checkAnimalInventory->get_result();
        if($animalInventoryResult->num_rows === 0 || $animalInventoryResult->num_rows < $animalQuantity){
            $inStock = false;
        }else{
            $stmt2 = $conn->prepare("INSERT INTO animal_sales (animal_type_id, gender, quantity, unit_cost, sale_date, sold_by)
                                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isidsi", $animalid, $animalGender, $animalQuantity, $aniUnitCost, $saleDate, $userId);
            $stmt2->execute();

            $animalUpdate = $conn->prepare("UPDATE animals
                                        SET lifecycle_status_id = 2
                                        WHERE lifecycle_status_id = 1 AND animal_type_id = ? AND gender = ?
                                        ORDER BY id ASC
                                        LIMIT ? ");
            $animalUpdate->bind_param("isi", $animalid, $animalGender, $animalQuantity);
            $animalUpdate->execute();

            if($stmt2->affected_rows > 0 && $animalUpdate->affected_rows > 0){
                $success = true;
            }

            $stmt2->close();
            $animalUpdate->close();
        }
        $checkAnimalInventory->close();
    }
    
}
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
    <script src="../js/sweetalert2.all.min.js"></script>
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
            <h1>Enter Sale</h1>

            <div class="select-wrapper">
                <select name="saleCategory" id="saleCategory" required>
                    <option value="">-- Pick a sale Category --</option>
                    <option value="productSale">Product Sale</option>
                    <option value="animalSale">Animal Sale</option>
                </select>
            </div>

            <div class="productMode">
                <input type="hidden" name="saleChoice" value="productChoice">

                <div class="select-wrapper">
                    <select name="productName" id="productName" required>
                        <option value="">Product Name</option>
                        <?php
                        $products = "SELECT * FROM products";
                        $productsResult = $conn->query($products);
                        while($productsRow = $productsResult->fetch_assoc()){
                            echo '<option value="'.$productsRow['id'].'">'.$productsRow['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="quantityAndUnit">
                    <div class="oneinput" id="quantity">
                        <input type="number" id="quantity-input" name="quantity" placeholder=" " required>
                        <label for="quantity">Quantity</label>
                    </div>
                    <div class="select-wrapper" id="unit">
                        <select name="unit" id="unitSelect" required>
                            <option value="">Unit</option>
                            <?php
                            $units = "SELECT * FROM products";
                            $unitsResult = $conn->query($units);
                            while($unitsRow = $unitsResult->fetch_assoc()){
                                echo '<option value="' .$unitsRow['unit']. '">' .$unitsRow['unit']. '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="optionalInput"><!--I am just borrowing the styling of optional but this isn't optional-->
                    <div class="oneinput">
                        <input type="number" name="unitCost" id="unitCost" placeholder=" " required>
                        <label for="unitCost">Unit Cost</label>
                    </div>
                    <label for="" id="message">* <span id="text">Enter cost of one good</span></label>
                </div>

                <div class="totalCost">
                    <label>Total Cost:</label>
                    <label class="labelTwo" >Kshs <span id="total-cost">0</span></label>
                </div>

                <div class="date">
                    <div class="oneinput">
                        <input type="date" id="date" name="date">
                        <label for="date">Sale Date</label>
                    </div>
                    <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
                </div>

            </div>

            <div class="animalMode hidden">
                <input type="hidden" name="saleChoice" value="animalChoice">

                <div class="select-wrapper">
                    <select name="animalType" id="animalType" required>
                        <option value="">Animal Type</option>
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
                    <select name="gender" id="gender" required>
                        <option value="">Gender</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </select>
                </div>

                <div class="oneinput">
                    <input type="number" id="quantity-input2" name="quantity" placeholder=" " required>
                    <label for="quantity-input2">Quantity</label>
                </div>

                <div class="optionalInput"><!--I am just borrowing the styling of optional but this isn't optional-->
                    <div class="oneinput">
                        <input type="number" name="unitCost" id="unitCost2" placeholder=" " required>
                        <label for="unitCost2">Unit Cost</label>
                    </div>
                    <label for="" id="message">* <span id="text">Enter cost of one good</span></label>
                </div>

                <div class="totalCost">
                    <label>Total Cost:</label>
                    <label class="labelTwo" >Kshs <span id="total-cost2">0</span></label>
                </div>

                <div class="date">
                    <div class="oneinput">
                        <input type="date" id="date" name="date">
                        <label for="date">Sale Date</label>
                    </div>
                    <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
                </div>
            </div>

            <div class="submission">
                <button type="submit">Enter</button>
                <?php 
                $message = '';
                if($success && $inStock){
                    $message = 'Record added successfully!';
                }elseif(!$inStock){
                    ?>
                    <script>
                        Swal.fire({
                            title: 'Out of Stock!',
                            text: 'The quantity of the product you have selected is not available.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'messageContainer',
                                title: 'messageTitle',
                                content: 'messageText',
                                confirmButton: 'messageConfirmButton'
                            }
                        });
                    </script>
                    <?php
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