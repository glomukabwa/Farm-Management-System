<?php
/*Instead of repeating the info below in every admin page, you can just do this and require it*/
session_start();/*Yes, for every page, a session must be started. Chat says its cz PHP does not auto-resume 
                sessions. If you don’t call session_start(), PHP pretends the session doesn’t exist. */

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    /*For admin pages, I have to makes sure that it is a valid user who is known by the system(has signed up/
    logged in) and that they are an admin not any other kind of user
    Also, the OR above really confused me first but I finally understand it that's why I am using it to 
    remember. What it means is that if either of the conditions is true then redirect them to login. At
    first I had put and in place of or but then that means that onlly if both are true should you redirect
    the user. This means that if one is false and the other is true, eg someone has logged in but they are
    not an admin, it'll allow them to access the page anyway cz for and, both conditions must be true to
    block the user. If this is confusing, just write two separate ifs. Make the first if for checking if
    the user_id is set and the second for checking if the role is admin. It'll work the same as or*/
    header("Location: login.php");
    exit;/*We must always put exit after header because header only tells PHP to redirect but it doesn't stop
        it from executing code that come after it. It would redirect but run the remaining code in the 
        background. In this case there is no remaining code but it is good practice to put exit after header
        so that all attention can be directed to the next page. Notice the exits after the headers in 
        signup.php and login.php */
}

/*Okay so now u have to require this in every admin page. For all the other pages, u'll require the regular
auth.php. Just note that sessions must be started for every page where u want the user activity remembered
and that is literally every page. Well, except logout I think. We'll see when we get there*/
?>