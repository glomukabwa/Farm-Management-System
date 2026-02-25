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

                <div class="dtAndMessage">
                    <div class="time">
                        <div>
                            <div class="oneinput">
                                <input type="date" id="eventD" name="eventD" required>
                                <label for="eventD">Date</label>
                            </div>
                        </div>
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
                    </div>

                    <p>* Click the clock icon on the right to select the date/time *</p>
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


                        /*Getting the event data and displaying it as default on form*/
                        const clickedDate = info.date;/*This gives you the date and time of where the user has clicked
                                                        There is info.date and info.dateStr.info.date is a JS Date Object. 
                                                        It's output looks sth like: Wed Feb 25 2026 09:00:00 GMT+0300 (East Africa Time)
                                                        It is used for calculations since it has everything that is needed and in a 
                                                        structure that JS understands. You will see its use below
                                                        info.dateStr returns a formatted date and it in string format. It looks like 
                                                        this: 2026-02-25T07:30:00+03:00 
                                                        Despite the fact that this one looks like sth the computer would understand
                                                        better than a human while the first one looks more user friendly, this one
                                                        can't be used for calculations. It is a string. It is used for things such as
                                                        DB use cz DB stores date as YYYY-MM-DD. You'll see it when I am assigning the
                                                        default date below. You can't extract the hours or minutes from this as we've
                                                        done below since it is treated as one whole string*/

                        const starthours = String(clickedDate.getHours()).padStart(2, '0');/*We convert it to a string cz padStart() only works with strings. getHours() returns a number
                                                                                             Padding is necessary bcz html expects time in form of HH:MM, also having single digits looks awkward*/
                        const startminutes = String(clickedDate.getMinutes()).padStart(2, '0');

                        const startformattedDate = `${starthours}:${startminutes}`;

                        const endHours = String((clickedDate.getHours() + 1) % 24).padStart(2, '0');
                        /*Above I am setting the default end time of an event as one hour after the start time.
                          I am using modulus 24 to ensure that there's nothing that goes beyond 23 eg 23 + 1 is 24 and there's nothing such as 24 in time so when u say modulus 24, it gives you 00 which means
                          it restarts the time from midnight correctly.*/

                        const endformattedDate = `${endHours}:${startminutes}`; /*the minutes never change, only the hours are affected in the addition*/

                        
                        /*Assigning the default values of the date and time to the form inputs*/
                        document.getElementById("eventD").value = info.dateStr.split('T')[0];
                        /*Above I've introduced info.dateStr. Eg if the output is 2026-02-25T07:30:00+03:00 , 
                          split('T') will split the string into 2 strings and it'll look like this:
                                [
                                 '2026-02-25',
                                 '07:30:00+03:00'
                                ]
                          Notice that the T is not part of either strings, there's just the part b4 and after T
                          So now [0] targets the first element of the array which is just the year. Why are we 
                          doing this? The datepicker expects just the year in that format so if u just assign
                          info.dateStr without doing some modifications, it won't display the default date*/
                        document.getElementById("startTime").value = startformattedDate;
                        document.getElementById("endTime").value = endformattedDate;
                    }
                });

                calendar.render();

            });
        </script>
    </section>
</body>
</html>