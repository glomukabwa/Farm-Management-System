<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../fullcalendar/index.global.min.js"></script>
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
        <div id="calendar"></div>

        <div id="eventPopup">
            <form method="POST">
                <div class="oneinput">
            <input type="text" id="phoneNumber" name="phoneNumber" placeholder=" " required>
            <label for="phoneNumber">Phone Number</label>
        </div>
            </form>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const calendarEl = document.getElementById("calendar");

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'timeGridWeek, timeGridDay',
                        center: 'title',
                        right: 'prev, next' //user can switch between the two
                    },
                    dateClick: function(info) {
                        const title = prompt("Event Title:");

                        if(title) {
                            calendar.addEvent({
                                title: title,
                                start: info.dateStr
                            })
                        }
                    }
                });

                calendar.render();
            });
        </script>
    </section>
</body>
</html>