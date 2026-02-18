<?php
require 'admin_auth.php';
include 'config.php';

$success = false;
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $product = (int) $_POST['productName'];
    $quantity = (int) $_POST['quantity'] ?: 0;
    $user = $_SESSION['user_id'];
    $date = $_POST['date'] ?: date('Y-m-d H:i:s');
    $comment = $_POST['comment'] ?: null;

    $stmt = $conn->prepare("INSERT INTO production_records (product_id, quantity, recorded_by, created_at, comment)
            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $product, $quantity, $user, $date, $comment);
    $stmt->execute();

    $updateInventory = $conn->prepare("UPDATE product_inventory
                                       SET quantity_available = quantity_available + ?
                                       WHERE product_id = ?");
    $updateInventory->bind_param("ii", $quantity, $product);
    $updateInventory->execute();

    if($stmt->affected_rows > 0 && $updateInventory->affected_rows > 0){
        $success = true;
    }
    $stmt->close();
    $updateInventory->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Production</title>
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
                <a href="http://localhost/Farm%20Website/php/calendar.php"><img src="../icons/calendar.png" alt="calendar">CALENDAR</a>

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
            <h1>Record Production</h1>

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
                    <select name="unit" id="unit" required>
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

            <div class="date">
                <div>
                    <input type="datetime-local" id="date" name="date">
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <div class="optionalInput">
                <div class="oneinput">
                    <textarea name="comment" id="comment" placeholder=" "></textarea>
                    <label for="comment">Comment</label>
                </div>
                <label for="" id="message">* <span id="text">Optional</span></label>
            </div>

            <div class="submission">
                <button type="submit">Enter</button>
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