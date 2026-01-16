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
    <form action="POST">
        <h1>Welcome back</h1>
        <div class="oneinput">
            <input id="email" type="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
        </div>
        <div class="oneinput">
            <input id="password" type="password" name="password" placeholder=" " required autocomplete="new-password">
            <label for="password">Password</label>
        </div>
        <button type="submit">Log In</button>
        <p class="noacount">Don't have an account? <a href="http://localhost/Farm%20Website/php/signup.php">Sign Up</a></p>
    </form>
</body>
</html>