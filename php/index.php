<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/index.js" defer></script><!--defer here means:wait until the HTML is parsed before running your JS, so you don’t 
    need DOMContentLoaded(an event that can be added to an action listener) if you use defer.-->
</head>
<body>
    <section class="sidebar">
        <div class="logo">
            <p>MF</p>
        </div>

        <div class="links">
            <div class="top-links">
                <a href="http://localhost/Farm%20Website/php/index.php"><img src="../icons/category.png" alt="overview">OVERVIEW</a>
                <a href="#"><img src="../icons/feeds.png" alt="feeds">ENTER FEED</a>
                <a href="#"><img src="../icons/sales.png" alt="sales">ENTER SALE</a>
                <a href="#"><img src="../icons/calendar.png" alt="calendar">CALENDAR</a>

                <a href="#" class="products-menu">
                    <img src="../icons/product.png" alt="products">
                    PRODUCTS <span class="arrow"> > </span>
                </a>
                <div class="products-submenu">
                    <a href="#"><img src="../icons/milk.png" alt="">Milk</a>
                    <a href="#"><img src="../icons/bull.png" alt="">Bulls</a>
                    <a href="#"><img src="../icons/chicken.png" alt="">Chicken</a>
                    <a href="#"><img src="../icons/eggs.png" alt="">Eggs</a>
                    <a href="#"><img src="../icons/pig.png" alt="">Pigs</a>
                    <a href="#"><img src="../icons/greens.png" alt="">Kales</a>
                    <a href="#"><img src="../icons/maize.png" alt="">Maize</a>
                </div>
            </div>

            <div class="bottom-links">
                <a href="#"><img src="../icons/profile.png" alt="profile">PROFILE</a>
                <a href="#"><img src="../icons/settings.png" alt="settings">SETTINGS</a>
                <a href="#"><img src="../icons/logout.png" alt="log out">LOG OUT</a>
            </div>
        </div>
    </section>

    <section class="main-content">
        <h1>Hello User</h1>
        
        <div class="content">
            <div class="left">

                <div class="feedings">
                    <h2>Today's Feeding</h2>

                    <table>
                        <tr>
                            <th></th>
                            <th>Morning Meal</th>
                            <th>Evening Meal</th>
                            <th>Water Refill</th>
                        </tr>
                        <tr>
                            <td id="animal">Cows</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                        <tr>
                            <td id="animal">Calves</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                        <tr>
                            <td id="animal">Bulls</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                        <tr>
                            <td id="animal">Pigs</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                        <tr>
                            <td id="animal">Piglets</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                        <tr>
                            <td id="animal">Chicken</td>
                            <td>✕</td>
                            <td>✕</td>
                            <td>✕</td>
                        </tr>
                    </table>

                    <a href="#">EDIT</a>
                </div>

                <div class="weekly-sales">
                    <h2>Weekly Sales Report</h2>
                </div>
            </div>

            <div class="right">
                <div class="alerts">
                    <h2>Alerts</h2>
                    <p>No alerts found</p>
                    <a href="#">See More</a>
                </div>

                <div class="todays-sales">
                    <h2>Today's Sales</h2>
                    <p>Ksh 0</p>
                </div>
            </div>
        </div>

    </section>
</body>
</html>