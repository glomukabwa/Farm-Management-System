<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/calendar.css">
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
            <form method="POST" id="eventform">
                <span id="closePopup">&times;</span>

                <div class="oneinput">
                    <input type="text" id="eventTitle" name="eventTitle" placeholder=" " required>
                    <label for="eventTitle">Event Title</label>
                </div>

                <div class="time">
                    <div class="timeSelector">
                        <div class="oneinput">
                            <input type="time" id="startTime" name="startTime" required>
                            <label for="startTime">Start Time</label>
                        </div>
                        <div class="oneinput">
                            <input type="time" id="endTime" name="endTime" required>
                            <label for="endTime">End Time</label>
                        </div>
                    </div>

                    <p>* Click the clock icon on the right to select the time *</p>
                </div>

                <div class="oneinput">
                    <textarea name="description" id="description" placeholder=" "></textarea>
                    <label for="description">Add Description</label>
                </div>

                <button class="submitBtn" type="submit">Enter</button>
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
                        console.log("Date clicked");
                        const eventPop = document.getElementById("eventPopup");
                        eventPop.classList.add("show");

                        /*Closing the popup*/
                        const closePop = document.getElementById("closePopup");
                        closePop.onclick = function(){
                            eventPop.classList.remove("show");
                        }

                        eventPop.onclick = function(e){
                            if(e.target === eventPop){/*The eventPop is the overlay*/
                                eventPop.classList.remove("show");
                            }
                        }

                        /*const title = prompt("Event Title:");

                        if(title) {
                            calendar.addEvent({
                                title: title,
                                start: info.dateStr
                            })
                        }*/
                    }
                });

                calendar.render();

            });
        </script>
    </section>
</body>
</html>