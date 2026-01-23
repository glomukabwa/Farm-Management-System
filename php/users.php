<?php
include 'config.php';

/*SET THE DEFAULT PAGE AND LIMIT */
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
/*Above, we need to set the defaults bcz when the user first opens users.php, the form with the limits and the 
hidden input with the page set as 1 has not been touched. So the values we've put there have not been set to be the 
default values(I was confused about this before). We need to actually set them here. Once the user changes the limit,
that is when the values will be sent here through GET and the limit will be altered and the page will be set to 1 by
default displaying page 1 with the new limit of rows.*/


/*SAFETY MEASURES */
if($page < 1){
    $page = 1;
}

if(!in_array($limit, [10,20,30])){/*If the value of $limit is not in the array[10, 20, 30] then $limit is 10*/
    $limit = 10;
}
/*To be honest, I don't think the above conditions will ever be met cz for page, I've ensured the less than arrow is
never shown when we are in page 1 and the greater than is never put in the last page AND for the limit I've put the
dropdown menu to ensure the options are restricted but there's a rule that you should never rely on the safety 
measures in frontEnd, you must always reinforce in backend. That is why I am putting them here */


/*CALCULATE THE OFFSET */
$offset = ($page - 1) * $limit;//This is a fixed formula that you must master
/*To select only a limited number of rows you need 2 things: an offset and the number of rows displayed which is the
limit. Here we are calculating the offset which basically describes what row to start from in the datatbase.
If we are displaying page 1 and the limit of rows is 10, the offset = (1 - 1) * 10 which is 0
That means that we start to count from row 1 to 10(the limit)
If we are in page 2 and the limit is 20, offset = (2 - 1) * 20 which is equal to 20
So we start counting from 21 to 40. We start counting from the value after the offset */


/*SELECT FROM THE DB */
$stmt = $conn->prepare("SELECT * FROM users
                        ORDER BY id ASC
                        LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

/*CALCULATE THE TOTAL NUMBER OF PAGES*/
$totalStmt = $conn->prepare("SELECT COUNT(*) AS total FROM users");/*Counts number of rows in users and places it
                                                                    in a column called total which is defined in the
                                                                    statement*/
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalArray = $totalResult->fetch_assoc();/*This will return only one value in an associative array which will be
                                            array[
                                                'total' => 21 (this is an example)
                                            ]*/
$totalRows = $totalArray['total'];/*This will return the value of the column which is 21 */

$totalPages = ceil($totalRows / $limit); /*ceil rounds up result that is in the brackests so if the limit is 10 the
                                           answer will be 3 pages*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/tables.css">
    <script src="../js/users.js" defer></script>
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
        <h1>Users</h1>
        
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
                    <th>First Name</th>
                    <th>Second Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </thead>

            <tbody id="table-body"><!--You have to contain the content below in a tbody so that AJAX can replace it
                                        when someone searches it. You'll notice I've replicated this code in 
                                        usersSearch.php . Plz note that you can't use a div instead. <tr> cannot
                                        be inside a div. It is strictly tbody. Notice that I have added thead for 
                                        the headers. That's the correct structure of a table-->
                <?php
                if($result->num_rows > 0){/*I am using this to display a message if there are no records*/
                    while($row = $result->fetch_assoc()){/*If you don't put it in a loop, it'll only fetch the 1st row
                    so this basically tells it to continue fetching if there is more data*/
                    ?>
                
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                            <td><?= htmlspecialchars($row['second_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone_number']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php
                    }
                }else{
                ?>
                <tr>
                    <td colspan="8">No record found</td><!--colspan means occupy the space of the specified number
                                                            of columns-->
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <form action="GET">
            <input type="hidden" name="page" value="1"><!--This resets page to 1 every time the limit has been 
                                                        changed. Chat says this is good measure and I honestly 
                                                        don't understand its explanation of why it is so just 
                                                        always do it. Its sth about the offset not being calculated
                                                        well if you don't do it-->

            <label for="limit">
                Show rows per page
                <select name="limit" id="limit" onchange="this.form.submit()">
                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                    <!--Normally if we want an option to appear as selected in a dropdown we do this:
                        <option value="10" selected>10</option> 
                        Now, why have I put the php in every option? Bcz if I don't specify what is selected,
                        the first option which is 10 will always look selected even if the user selects 20 and the
                        number of rows being displayed are 20. The backend will be working but the frontend will
                        be confusing. So what now? In the above statement for example, it is saying that if the 
                        limit has been set as 10(the setting is happening in php at the top), then make this option
                        selected but if not, don't.So it is checking: has this option been selected? If it has, make
                        it appear as if it has. If it hasn't, don't, so it won't be at the top. The php in the other
                        options will work to put the right option at the top cz if 2 options are not selected,
                        they'll be set to '' but the 1 option selected will be set to 'selected'.-->
                    <option value="20" <?=  $limit == 20 ? 'selected' : '' ?>>20</option>
                    <option value="30" <?=  $limit == 30 ? 'selected' : '' ?>>30</option>
                </select>
            </label>
        </form>

        <div class="arrows">
            <?php
            if($page > 1){//You want the less than sign to only appear for pages that are not 1  cz 1 has no preceding page
               ?>
               <!--Below, the final link should look sth like this:
                    users.php?page=1&limit=20   
                Which means display page 1 and display 20 rows-->
               <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">&lt;</a>
                <!--Don't forget to do the subtraction for the page cz we're moving to the previous page-->
                <!--Note, the reason we had to create form for the options is cz the form sends needs to send
                    a GET request for them(which is in form of a link[URL]) but for these 2 links in this div, the
                    href already has the link needed-->
               <?php
            }
            ?>

            <span>Page <?= $page ?> of <?= $totalPages ?></span>

            <?php
            if($page < $totalPages){//Cz there are no pages past total number of pages
                ?>
                <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">&gt;</a>
                <!--Don't forget to do the addition for the page cz we're moving to the next page-->
                <?php
            }
            ?>
        </div>

    </section>
</body>
</html>