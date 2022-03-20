<?php

//VALIDATING AND SANITIZING DATA
    /*
    Always validate any data before adding it to a database you can use 
    filter_var($email, FILTER_VALIDATE_EMAIL) 
    to throw an exception if the email is not valid
    other parameters also include 
    //FILTER_SANITIZE_STRING <h1>Hello</h1> -> Hello
    //FILTER_VALIDATE_INT 
    
    Once data is validated, it can be sanitized with commands such as
    htmlspecialchars()
    mysqli_real_escape_string()
    */

//use one or all of the following

function sanitizeString($var){
    //remove HTML characters
    $var = filter_var($var, FILTER_SANITIZE_STRING);
    //remove whitespace at ends
    $var = trim($var);
    //prevents escape characters being injected into MySQL string
    //$var = mysqli_real_escape_string($var);
    //removes unwanted slashes from string
    $var = stripslashes($var);
    //converts interpretable HTML eg <b>Hi</b> to &lt;b&gt;hi&lt;/b&gt;
    $var = htmlspecialchars($var);
    //removes html entirely from code
    $var = strip_tags($var);
    //return the string sanitized
    return $var;
}

?>