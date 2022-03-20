<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //header("location: index.php");
    //if you reach here output message and terminate the script (msg none supplied)
    //exit;
    //echo "user logged in";
}
 
// Include config file
require_once "connect.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
$data = json_decode(file_get_contents("php://input")); 

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($data->username))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($data->username);
    }
    
    // Check if password is empty
    if(empty(trim($data->password))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($data->password);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, confirm FROM onlineshop_users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                /*call store_result() for prepared statements that use
                  SELECT, SHOW, DESCRIBE, EXPLAIN that you want buffered
                  by the client, result stored on client for faster access*/
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password, $confirm);
                    if($stmt->fetch()){
                        //password_verify checks if a password matches a hash
                        if(password_verify($password, $hashed_password) && $confirm == 1){
                            // Password is correct, so start a new session
                            //session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to index page
                            //header("location: index.php");
                            //echo "Thanks for logging in";
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                            //echo $password_err;
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                    //echo $username_err;
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    $response = array(
    "username_err" => $username_err,
    "password_err" => $password_err);
    echo json_encode($response);
    
    // Close connection
    $conn->close();
}
?>