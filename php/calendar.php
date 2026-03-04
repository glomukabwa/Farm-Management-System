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
    <script src="../js/main.js" defer></script>
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
                    <a href="http://localhost/Farm%20Website/php/dairy.php"><img src="../icons/milk.png" alt="milk">Dairy</a>
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
                <a href="#" id="logoutNav"><img src="../icons/logout.png" alt="log out">LOG OUT</a>
            </div>
        </div>
    </section>

    <section class="main-content">
        <div id="calendar"></div>

        <div id="eventPopup">
            
            <form method="POST" id="eventform">
                <span id="closePopup">&times;</span>
                <span id="deleteBtn"><img src="../icons/delete.png" alt="trashcan"></span>

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
                    events: 'loadEvents.php',

                    dateClick: function(info) {
                        console.log("Date clicked");
                        const eventPop = document.getElementById("eventPopup");
                        eventPop.classList.add("show");
                        document.getElementById("eventform").reset();
                        /*The above line clears the form so that there is no field that is autofilled.
                          Below I will set the default dates and they will be set on empty fields bcz
                          I've cleared the form. Without this line, the form was showing the previous 
                          title, date, time of the event that had been submitted and trying to disable
                          it from html wasn't working.
                        */

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

                        const startformattedTime = `${starthours}:${startminutes}`;

                        const endHours = String((clickedDate.getHours() + 1) % 24).padStart(2, '0');
                        /*Above I am setting the default end time of an event as one hour after the start time.
                          I am using modulus 24 to ensure that there's nothing that goes beyond 23 eg 23 + 1 is 24 and there's nothing such as 24 in time so when u say modulus 24, it gives you 00 which means
                          it restarts the time from midnight correctly.*/

                        const endformattedTime = `${endHours}:${startminutes}`; /*the minutes never change, only the hours are affected in the addition*/

                        
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
                        document.getElementById("startTime").value = startformattedTime;
                        document.getElementById("endTime").value = endformattedTime;


                        /*Setting the content of the event after it has been submitted*/
                        document.getElementById("eventform").onsubmit = function(e) {
                            e.preventDefault();
                            /*Every time you want to use fetch(AJAX), you must preventDefault(). Why? When a page reloads,
                            all the JS tasks are cancelled, everything siezes and returns itself to the state it is initially
                            supposed to be. This means that if u submit and the page reloads, in the background the fetch is
                            doing its work right? It might get interrupted b4 it has sfinished so u might end up with half saved
                            data, no saved data or fully saved data. Since we don't want to depend on luck, we ensure we prevent
                            the default behaviour. You're, probably wondering, so what happens when a user deliberately reloads
                            the page? Up there when initializing the FullCalendar, u'll notive a line like events: 'loadEvents.php'
                            This line uses php to display all events in the DB so even when the page reloads, all events saved will
                            still be displayed cz they are retrieved as often as the page is reloaded*/

                            const EventTitle = document.getElementById("eventTitle").value;
                            const EventDate = document.getElementById("eventD").value;
                            const EventStart = document.getElementById("startTime").value;
                            const EventEnd = document.getElementById("endTime").value;
                            const Eventdesc = document.getElementById("description").value;

                            fetch('saveEvent.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                    /*Initially, I thought that JS and PHP can only communicate using JSON but I've learnt that there are
                                      a number of ways they can communicate. Some of them are:
                                            multipart/form-data                 ->      File uploads
                                            application/x-www-form-urlencoded   ->      Classic forms (most common)
                                            application/json                    ->      APIs / modern SPAs
                                            Query strings (?id=5)               ->      GET requests
                                            Plain text                          ->      Rare but valid

                                      This is one of them, it is number 2 on the table. It is the way data submitted by forms is normally
                                      packaged. It is very easy for PHP to understand this format and unlike JSON, you won't have to decode
                                      it to PHP when it is sent. Below, new URLserachParams() is what will convert the JS data to the form 
                                      format. In the PHP file, u'll notice I'm just using the normal eg $_POST['EventTitle'] to access the data
                                    */
                                },
                                body: new URLSearchParams({
                                    EventTitle,
                                    EventDate,
                                    EventStart,
                                    EventEnd,
                                    Eventdesc
                                })
                            });

                            calendar.refetchEvents();/*So that when the eventPopup disappears the new event is already shown on the calendar */

                            eventPop.classList.remove("show");

                        }
                    },

                    eventClick: function(info){
                        /*When FullCalendar triggers a callback like dateClick or eventClick, it passes an object
                          with useful data which in this case we are calling info. You can give it any other name
                          For eventClick, it looks like this:
                                    info = {
                                        event: EventObject,
                                        el: clickedHTMLElement,
                                        jsEvent: nativeClickEvent,
                                        view: calendarView
                                    } 
                            event holds the data of the event that we already got through loadEvents.php
                            Chat says that FullCalendar does the mapping internally and knows which event is being
                            clicked so u don't have to worry about that. el is the HTML container that will contain
                            the data. In this case, el is the form. If you want to change styling, u'd use el to
                            do it eg: info.el.style.border = "solid 0.2rem blue"
                            Below u'll see how we'll use info.event
                        */
                        const eventPop = document.getElementById("eventPopup");
                        eventPop.classList.add("show");

                        document.getElementById("eventTitle").value = info.event.title;
                        document.getElementById("eventD").value = info.event.startStr.split("T")[0];
                        /*Above, you can't use split on a data object which is what event.start would return so
                          you say event.startStr to get the formatted string. loadEvents.php gave it the data
                          it needs and FullCalendar stored it the way it wants to so now if u want to access
                          its data, you use its rules. I was getting confused on why we'd use startStr when in 
                          the php file, we stored the events using just start but now that we have handed the data
                          to FullCalendar, we use its rules to access it*/
                        document.getElementById("startTime").value = info.event.startStr.split("T")[1].slice(0,5);
                        /*Since the time has seconds too, we need to extract only the HH:MM. Slice means the 
                          slicing that we learnt in data analytys so we're saying give me the number from index
                          0 to the one before index 5. If you look at HH:MM:SS, the element before index 5 is the
                          last minute so that's how we get the right time format to display in the form
                        */
                        document.getElementById("endTime").value = info.event.endStr.split("T")[1].slice(0,5);
                        console.log(info.event);
                        console.log(info.event.extendedProps.description);
                        document.getElementById("description").value = info.event.extendedProps.description;

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

                        const EventId = info.event.id;

                        /*Deleting popup*/
                        const DeleteBtn = document.getElementById("deleteBtn");
                        DeleteBtn.onclick = function(){
                            fetch('deleteEvent.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    EventId
                                })
                            });

                            info.event.remove();/*calendar.refetchEvents() runs simultaneously to the fetch above
                            so u might notice that even though it has been deleted in the DB, it is not reflecting
                            cz it is refetching b4 the DB is done deleting so instead, I am deleting in the UI as
                            the Db deletes in the background. This reflects faster. I can either do this or put
                            echo 'success message' right after the sql statement in deleteEvent.php then do the 
                            following: 
                            .then(response => response.text())
                            .then(data => {
                                    if(data === 'success message'){
                                        calendar.refetchEvents();
                                        eventPop.classList.remove("show");
                                    }
                                })
                            
                            The above code would ensure that it only refetches and removes the popup after the DB
                            is done deleting but then I was afraid of a delay even though I think it would be small,
                            I'd rather it's fast. I think I might incooperate calendar.addEvent() for the form
                            submit button if I get another glitch with calendar.refetchEvents(); cz I have had
                            instances where I have had to reload the page for the event to show
                            */

                            eventPop.classList.remove("show");
                        }

                        document.getElementById("eventform").onsubmit = function(e){
                            e.preventDefault();

                            const EventTitle = document.getElementById("eventTitle").value;
                            const EventDate = document.getElementById("eventD").value;
                            const EventStart = document.getElementById("startTime").value;
                            const EventEnd = document.getElementById("endTime").value;
                            const Eventdesc = document.getElementById("description").value;

                            fetch('updateEvent.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    EventId,
                                    EventTitle,
                                    EventDate,
                                    EventStart,
                                    EventEnd,
                                    Eventdesc
                                })
                            });

                            calendar.refetchEvents();

                            eventPop.classList.remove("show");
                        }

                    }

                });

                calendar.render();

            });
        </script>
    </section>

    <div id="logoutModal" class="logoutmodal">
        <form action="logout.php" method="POST" id="logoutModalContent">
            <img src="../icons/logout.png" alt="logout">
            <h1>Log Out</h1>
            <p>Are you sure you want to log out?</p>
            <div>
                <button type="button" id="cancelLogout">CANCEL</button>
                <button type="submit">LOG OUT</button>
            </div>
        </form>
    </div>
</body>
</html>