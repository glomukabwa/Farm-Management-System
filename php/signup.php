<?php
include 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/signup.css">
    <script src="../js/signup.js" defer></script>
</head>
<body>
    <form action="POST">
        <h1>Hello</h1>
        <div class="name">
            <div class="oneinput">
                <input type="text" id="firstName" placeholder=" " required><!--You put some space in the placeholder quotes so that it can act as a placeholder that isn't visible and its purpose is so that u can use :not(placeholder-shown) in css to check if sth has been typed for the label to move to the corner-->
                <label for="firstName">First Name</label>
            </div>
            <input type="text" placeholder="Second Name" required>
        </div>
        <input type="text" placeholder="Email" required>
        <input type="text" placeholder="Phone Number" required>

        <div class="select-wrapper">
            <select name="role" id="role">
                <option value="">Role</option>
                <option value="staff">Staff</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <!--<div class="custom-select-wrapper">
            <div class="custom-select">
                <div class="selected">Role ▼</div>
                <ul class="options">
                    <li data-value="staff">Staff</li>
                    <li data-value="manager">Manager</li>
                    <li data-value="admin">Admin</li>
                </ul>
            </div>
            <input type="hidden" name="role" id="role">
        </div>-->


        <input type="text" placeholder="Enter Password" required>
        <input type="text" placeholder="Confirm Password" required>

        <button>Sign Up</button>

        <p class="already">Already have an account?<a href="#">Log In</a></p>

    </form>
</body>
</html>