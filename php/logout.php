<?php
session_start();/*To destroy a session, you have to start it first*/

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION = [];/*Clears all session data stored in the session array.*/
    session_unset();/*This clears the session variables and it is optional cz the above clears all the data*/
    session_destroy();/*Completely destroys the session on the server*/

    /*Below I am going to delete the session cookie but before that, I will explain sth that 
      can be a bit confusing. When you start a session, PHP does two things. It creates a 
      session file on the server and  it sends a cookie to the browser which contains the 
      session ID. So for example:
            The cookie will contain: PHPSESSID = abc123xyz
            The session file will contain:
                $_SESSION['user_id'] = 5;
                $_SESSION['role'] = 'admin';
                $_SESSION['fname'] = 'Gloria';

      abc123xyz is the session ID that the cookie uses to identify the session file on the server
      Think of the two as a cabinet and a key. The two will be used to identify the user so now the
      server knows that user 5 named Gloria wants eg index.php. Plz note that the order is the browser
      requesting for index.php, the browser sending the session cookie (cz there's session start in that
      page), php loading the session file (this involves looking for the session file with the name of the
      session id and if it doesn't exist then redirecting the user to login to create a session file). After
      this is done then that means that the identification of the user has been confirmed and so the index.php
      is displayed. Sth else I found confusing is why we start sessions in every page, I mean, won't it be 
      creating new session files for each page? So what happens is that if the first page is loaded and the 
      session ID given to the cookie, when u load the second page which also starts the session, the browser 
      will first check if a cookie already exists and if it does, it won't create another. It will use the one there
      Sth else is that different users have different session IDs but then the cookie name remains the same so eg
                User A: PHPSESSID = abc123xyz
                User B: PHPSESSID = efc456dfg
      If us look closely it says PHP Sess Id. Chat says u can change the cookie name but I don't find it necessary.
      Also the cookie name is the same across browsers
    */

    if (ini_get("session.use_cookies")) {/*This means check if PHP is using cookies to manage sessions and it is usually true. 
                                           Idk why it's necessary though cz I think it's redundant but I'm choosing to trust
                                           Chat's advice*/
        $params = session_get_cookie_params();/*This gets the cookie settings: path, domain, secure, httponly. Why? Because 
                                                when deleting a cookie, you must use the exact same settings that were used to create it.*/
        setcookie(
            session_name(),/*This returns PHPSESSID*/
            '',/*Sets the value of the session ID to empty*/
            time() - 42000,/*This sets the cookie expiration to a time in the past which makes the browser delete the cookie */
            $params["path"],/*From here to below, we reuse the original settings so the browser knows exactly which cookie to remove. */
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
}

header("Location: login.php");
exit();
}
?>
