<?php
session_start();
/*Sessions are used to store information across multiple pages for a single user. Normally, HTTP is stateless — every request is independent,
so the server doesn’t “remember” you between page loads. A session fixes that by giving each user a unique session ID (usually stored in 
the user’s browser as a cookie). Every request the browser makes to your server includes that cookie, so PHP knows “this request belongs 
to the same user as before.” It uses it to keep track of data (like login status, flash messages, shopping cart contents) across requests.
The session ID is just a key. You decide what values to attach to it in $_SESSION. 
For a flash message, we store $_SESSION['flash'] = "Flash message".
For login, you can store an email, userId etc. Since I want a more user friendly webiste, I am going to apply auto-log in which is basically 
allowing the user to access the index page without redirecting them to log in to confirm their credentials. Once they sign-up, they'll be 
able to access the website immediately. The auto-logging concept comes in when you store the user details in session variables here instead 
of in log in for the first time. In log in, you'll notice that I have stored the session variables again there and that is because after some 
time, the session expires and the variables need to be set again. Also, for pages that need admin authorization(admin priviledges), I'll need 
to redirect them to log in for the user_id, user_role etc to be set incase they aren't. Just look at admin_auth.php and the pages that require
it like farmRecords.php and enterRecord.php to understand*/
include 'config.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $fname = trim($_POST['firstName'] ?: '');
    $sname = trim($_POST['secondName'] ?: '');
    $email = trim($_POST['email'] ?: '');
    $pnumber = trim($_POST['phoneNumber'] ?: '');
    $role = trim($_POST['role'] ?: '');
    $newPassword = trim($_POST['newPassword'] ?: '');
    $confirmPassword = trim($_POST['confirmPassword'] ?: '');

    /*I've checked for email duplication in JS*/

    /*I've already checked that new password and confirm password are the same but chat says its not advisable
    to depend on UI for functionalities so its good measure to have a backup here */
    if($newPassword != '' && $confirmPassword != ''){
        if($newPassword === $confirmPassword){
            /*Hashing password*/
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); 
            $stmt = $conn->prepare(
                "INSERT INTO users (first_name, second_name, email, phone_number, role, password_hash) 
                values (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fname, $sname, $email, $pnumber, $role, $hashedPassword);
            if($stmt->execute()){
                $newUserId = $conn->insert_id;//gets the auto-generated ID in the DB

                session_regenerate_id(true);
                /*Give this user a brand‑new session ID and deletes the old session data at the same time.Why this is important:
                    Prevents session fixation attacks: Without regeneration, an attacker could trick a user into using a known 
                    session ID, then hijack it after login.
                    Ensures clean state:When a user logs in or signs up, you don’t want leftover session data from before (like guest
                    state or error flags).
                    Professional practice:Most secure apps regenerate the session ID at key points: login, signup, logout, privilege 
                    changes.Plz note that u only do this when you start a session in the pages mentioned above.
                */

                $_SESSION['user_id'] = $newUserId;
                $_SESSION['user_role'] = $role;/*I'll use this to confirm that user is an admin in admin_auth.php */
                $_SESSION['user_name'] = $fname;/*I'll use this in the index page to display the greeting("Hello {user}") */

                header("Location: index.php");
                exit;
            } else {
                ?>
                <script>alert("Sign Up failed. Please try again")</script>
                <?php
            }
            $stmt->close();/*You have to close the statement in the block it was created cz this is an if statement so there is a chance 
            the statement might never be created in the first place if the if condition is not met so don't close it outside the second
            if(that is what I had done)*/
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/signup.css">
    <script src="../js/signup.js" defer></script>
</head>
<body>
    <form method="POST">
        <h1>Hello</h1>
        <div class="name">
            <div class="oneinput">
                <input type="text" id="firstName" name="firstName" placeholder=" " required><!--You put some space in the placeholder quotes so that it can act as a placeholder that isn't visible and its purpose is so that u can use :not(placeholder-shown) in css to check if sth has been typed for the label to move to the corner-->
                <label for="firstName">First Name</label>
            </div>
            <div class="oneinput">
                <input type="text" id="secondName" name="secondName" placeholder=" " required>
                <label for="secondName">Second Name</label>
            </div>
        </div>

        <div class="oneinput">
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
            <span id="emailMessage"></span>
        </div>
        <div class="oneinput">
            <input type="text" id="phoneNumber" name="phoneNumber" placeholder=" " required>
            <label for="phoneNumber">Phone Number</label>
        </div>

        <div class="select-wrapper">
            <select name="role" id="role" required>
                <option value="">Role</option><!--value being empty here will ensure that an option has been
                picked cz if a user tries to pick this first option, the browser will block submission cz a 
                non-empty value is required-->
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


        <div class="oneinput">
            <input type="password" id="newPassword" name="newPassword" placeholder=" " required autocomplete="new-password"><!--autocomplete="new-password" clears both the password field and email which are autofilling-->
            <label for="newPassword">Enter Password</label>
            <span id="pStrengthMessage"></span>
        </div>
        <div class="oneinput">
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder=" " required >
            <label for="confirmPassword">Confirm Password</label>
            <span id="confirmMessage"></span>
        </div>

        <button type="submit">Sign Up</button>

        <p class="already">Already have an account? <a href="http://localhost/Farm%20Website/php/login.php">Log In</a></p>

    </form>
</body>
</html>