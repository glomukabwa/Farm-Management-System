<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <section class="sidebar">
        <div class="logo">
            <p>MF</p>
        </div>

        <div class="links">
            <div class="top-links">
                <a href="http://localhost/Farm%20Website/php/index.php">OVERVIEW</a>
                <a href="#">ENTER FEED</a>
                <a href="#">ENTER SALE</a>
                <a href="#">PRODUCTS</a>
                <a href="#">Milk</a>
                <a href="#">Bulls</a>
                <a href="#">Chicken</a>
                <a href="#">Eggs</a>
                <a href="#">Pigs</a>
                <a href="#">Kales</a>
                <a href="#">Maize</a>
                <a href="#">CALENDAR</a>
            </div>

            <div class="bottom-links">
                <a href="#">PROFILE</a>
                <a href="#">SETTINGS</a>
                <a href="#">LOG OUT</a>
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