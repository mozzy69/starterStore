<?php
// Initialize the session
session_start();

if(isset($_SESSION["username"]) || isset($loggedInUser)){
    // Unset all of the session variables
    unset($loggedInUser);
    $_SESSION = array();
    // Destroy the session.
    session_destroy();
    echo "You have successfully been logged out."; 
}else{
    echo "Please login first.";
}

// Redirect to login page
//header("location: login.php");
//exit;
?>