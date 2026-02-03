<?php
require 'admin_auth.php';
include 'config.php';

$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pName = $_POST['pname'] ?: '';
    $category = (int) $_POST['categoryName'];
    $unit = $_POST['unit'];
    $date = $_POST['date'] ?: date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO products (name, category_id, unit, created_at)
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $pName, $category, $unit, $date);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        $success = true;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Product</title>
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
            <h1>Enter Product</h1>

            <div class="oneinput">
                <input type="text" id="pname" name="pname" placeholder=" " required>
                <label for="pname">Product Name</label>
            </div>

            <div class="select-wrapper">
                <select name="categoryName" id="categoryName" required>
                    <option value="">Category</option>
                    <?php
                    $categories = "SELECT * FROM product_categories";
                    $categoriesResult = $conn->query($categories);
                    while($categoriesRow = $categoriesResult->fetch_assoc()){
                        echo '<option value="'.$categoriesRow['id'].'">'.$categoriesRow['category_name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="oneinput">
                <input type="text" id="unit" name="unit" placeholder=" " required>
                <label for="unit">Unit</label>
            </div>

            <div class="date">
                <div>
                    <input type="datetime-local" id="date" name="date" required>
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <div class="submission">
                <button type="submit">Enter</button>
                <?php 
                $message = '';
                if($success){
                    $message = 'Product added successfully!';
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