<?php
session_start();
include 'config.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if($email !== '' && $password !== ''){
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");//Use the unique identifier to select the row you want
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()){//This returns true if an actual row exists. Fetch assoc_returns an array with the db columns as the key
            //Verify the password now that you have the row:
            if(password_verify($email, $row['password_hash'])){
                //Store the values in a session
                /*When you call session_start(), PHP looks for a session cookie (PHPSESSID) in the browser.
                If it finds one, it resumes that session; if not, it creates a new one. The data you put in $_SESSION[...] lives only until the 
                session ends (for example, when the browser is closed, the cookie expires, or you call session_destroy()). Once the session is 
                gone, all variables (user_id, role, etc.) disappear. 
                If the user comes back tomorrow, their old session is gone. They must log in again, and thats why you set all the session variables
                below again. Notice that I am setting the exact same sessions I set in sign up.*/
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                header("Location: index.php");
                exit; //The php code ends here if the login is successful
            }else{
                ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function(){
                        //Above, if you don't put DOMContentLoaded, u'll never see the message cz the script will always execute b4 the html even loads
                        const passMessage = document.getElementById("passwordMessage");
                        passMessage.textContent = "Incorrect Password";
                    });
                </script>
                <?php
            }
        }else{
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function(){
                    <?php
                    //$email = '';
                    ?>
                    const emailMessage = document.getElementById("emailMessage");
                    emailMessage.textContent = "Please enter the correct email";
                })
            </script>
            <?php
        }
    }
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
            <input id="email" type="email" name="email" placeholder=" " required
                value=<?php echo htmlspecialchars($email ?? '', ENT_QUOTES) ?>>
            <!--The safety measure for outputing things entered by the user is using htmlspecialchars() to turn any 
                special characters like &,$ etc into html characters like &and; to avoid issues with the html code
                if a malicious user enters sth that could break the html. ENT_QUOTES means also convert '' into html
                special chars cz I am guessing it is not included in htmlspecialchars()-->
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