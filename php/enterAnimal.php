<?php
include 'config.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $animalType = (int)($_POST['animalType']);
    $breed = !empty($_POST['breed']) ? (int)($_POST['breed']): null;
    /*With the above and tagNumber below, they are optional so incase they aren't set, the value should be null not zero cz if you put zero,
     u'll be saying that there's a foreign key 0 and there isn't so it'll bring an error during submission.
     And then sth else is that above, u'll noticed I used the long empty line instead of just using ?: If I had written this:
        $breed = (int) ($_POST['breed'] ?: null), if no value was entered and the default value would have to be null, it would try to 
        cast null to an int and that would bring an error. If I did this:
        $breed = (int) ($_POST['breed']) ? null, Notice the brackets ends b4 the question mark. If let's say a breed existed with the id 0, ?:
        considers 0 as an empty so it would assign null to any breed id with the value 0. Chat has advised to always cast after checking the 
        condition hence the long version of empty */
    $tagNumber = trim($_POST['tagNumber'] ?: '');
    $gender = $_POST['gender'] ?: ''; /*Note the ?: instead of ?? So I've learnt that ?? does not check if the input is empty, it
                                        just checks if it is missing(has been submitted by the form). I know it doesn't make sense why 
                                        sb would need to check if the form has submitted the input cz if u've assigned name to the input
                                        then u're assured everything will be submitted but check the comments where the input for tagNumber
                                        is and u'll start to get it.Chat also says that at my level I might not understand its importance
                                        but I'll get it as I progress. It says that for sessions and pages then the isset is important.
                                        However, my intention was to check if the input is empty and ?: does that. It is short for
                                        $gender = !empty($_POST['gender']) ? $_POST['gender'] : '' ; So I've replaced ?? with ?: where 
                                        I felt was necessary. Also sth you should know is that 0 is also considered empty so it's not just ''*/
    $healthStatus = (int) ($_POST['healthStatus']);
    $createdAt = $_POST['date'] ?: date('Y-m-d');/*So apparently, as much as I have put DEFAULT CURRENT_DATE for dates, SQL won't put a default
                                                date because I have already said in the prepared statement below that I will give it a date. If 
                                                I give it an empty date('') meaning the user doesn't pick a date, it will not provide a default
                                                cz that's the value the date is assigned. However, the dilema comes in where I have made the 
                                                created_at field NOT NULL in the DB. So it'll not give me a default date but it will throw an error.
                                                So here, I am saying that if the date is empty, give it the current date. I'm basically assigning a 
                                                default date but in PHP. That's what this line is for: date('Y-m-d')*/

    $query = "INSERT INTO animals (animal_type_id, breed_id, tag_number, gender, health_status_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissis", $animalType, $breed, $tagNumber, $gender, $healthStatus, $createdAt);
    $stmt->execute();
}

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
        <form method="POST">
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
                        echo '<option value="'.$typeRow['id'].'">'.$typeRow['name'].'</option>'; 
                    }
                    ?>
                </select>
            </div>

            <div>
                <div class="select-wrapper">
                    <select name="breed" id="breed">
                        <option value="">Breed</option>
                        <?php 
                        $breeds = "SELECT * FROM breeds";
                        $breedResult = $conn->query($breeds);
                        while($breedRow = $breedResult->fetch_assoc()){
                            echo '<option value="'.$breedRow['id'].'">'.$breedRow['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <label for="" id="message">* <span id="text">Optional</span></label>
            </div>

            <div class="optionalInput">
                <div class="oneinput">
                    <input type="text" id="tagNumber" name="tagNumber" placeholder=" " >
                        <!--value=" htmlspecialchars($_POST['tagNumber'] ?? '') ?>"
                        Okay so now I am starting to see why ?? exists. Here it checks if the form 
                        submitted tagNumber. If it did, it tells it to display the value, but if it didn't,
                        it tells it to keep the input empty. If we used ?: here, if the user entered 0, null
                        or false the clicked the arrow to go back, the input wouldn't be retained bcz ?: 
                        considers those values equivalent to empty so that's one reason ?? exists-->
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
                        echo '<option value="'.$healthRow['id'].'">'.$healthRow['status_name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="date">
                <div>
                    <input type="date" id="date" name="date">
                </div>
                <label for="" id="message">* <span id="text">Click the icon on the right to open the date picker</span></label>
            </div>

            <div class="submission">
                <button type="submit">Enter</button>
                <?php
                $message = ''; /*<p> below displays the message all the time so u wanna ensure that is there's nothing to say, it displays nothing*/
                if($_SERVER["REQUEST_METHOD"] === "POST"){/*Cz we don't want it to display message every time the page reloads */
                    if($stmt->affected_rows > 0){/*This will make sure that this batch of code is only considered when an insertinon is made */
                        $message = "Successful addition of animal!";
                    }
                }  
                ?> 
                <p id="successMessage"><?= htmlspecialchars($message)?></p>
            </div>
            
        </form>
    </section>
</body>
</html>