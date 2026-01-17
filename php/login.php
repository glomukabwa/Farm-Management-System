<?php
session_start();
include 'config.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    //if($email != '' && $password != '')
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/login.js" defer></script>
</head>
<body>
    <form method="POST" id="loginForm" novalidate>
        <!--novalidate basically tells the browser: don’t run your own validation, let me handle it in JavaScript.
            If you don't put it, the browser will give you its own pop-up message when the required inputs are not 
            entered and ignore the JS messaged you have told it to display-->
        <h1>Welcome back</h1>
        <div class="oneinput">
            <input id="email" type="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
            <span id="emailMessage"></span>
        </div>
        <div class="oneinput">
            <input id="password" type="password" name="password" placeholder=" " required autocomplete="new-password">
            <label for="password">Password</label>
            <span id="passwordMessage"></span>
        </div>
        <span id="btnMessage"></span>
        <button type="submit" id="submitButton">Log In</button>
        <p class="noacount">Don't have an account? <a href="http://localhost/Farm%20Website/php/signup.php">Sign Up</a></p>
    </form>
</body>
</html>