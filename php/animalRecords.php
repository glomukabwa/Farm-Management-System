<?php
require 'admin_auth.php';
include 'config.php';

/*Pagination*/
//Setting the defaults
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

//Safety measures
if($page < 1){
    $page = 1;
}

if(!in_array($limit, [10,20,30])){
    $limit = 10;
}

$stmt = $conn->prepare("SELECT * FROM animals
                        ORDER BY id ASC
                        LIMIT ?,?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$totalStmt = $conn->prepare("SELECT COUNT(*) AS total FROM animals");
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalRecords = (int) $totalRow['total'];
$totalPages = ceil($totalRecords/$limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Records</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/tables.css">
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

        <form method="GET">
            <div class="search">
                <input id="search" type="text" placeholder=" ">
                <label for="search">
                    <img src="../icons/search.png" alt="search">
                    <span>Search</span>
                </label>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Animal Type</th>
                    <th>Breed</th>
                    <th>Lifecycle Status</th>
                    <th>Gender</th>
                    <th>Health Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = $result->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['animal_type_id']) ?></td>
                        <td><?= htmlspecialchars($row['breed_id']) ?></td>
                        <td><?= htmlspecialchars($row['lifecycle_status_id']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['health_status_id']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <div class="controls">
            <form method="GET">
                <input type="hidden" value="1" name="page">

                <label for="limit">
                    Show rows per page
                    <select name="limit" id="limit" onchange="this.form.submit()">
                        <option value="10"<?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20"<?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        <option value="30"<?= $limit == 30 ? 'selected' : '' ?>>30</option>
                    </select>
                    <span class="arrow">⌄</span>
                </label>
            </form>

            <div class="arrows">
                <?php
                if($page > 1){
                    ?>
                    <a id="lessThan" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">&lt;</a>
                    <?php
                }
                ?>
                <span> Page <?= $page ?> of <?= $totalPages ?> </span>
                <?php
                if($page < $totalPages){
                    ?>
                    <a id="greaterThan" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">&gt;</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</body>
</html>