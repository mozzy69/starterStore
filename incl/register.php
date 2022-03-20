<?php
// Include config file
require_once "connect.php";
session_start();
 
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = $username_info = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
$data = json_decode(file_get_contents("php://input"));

// Processing form data when form is submitted
//if($_SERVER["REQUEST_METHOD"] == "POST"){
if(isset($data->username)){ 
    // Validate username ////////////////////////////////////////
    if(empty(trim($data->username))){
        $username_err = "Please enter a username.";
    }else{
        $sql = "SELECT id FROM onlineshop_users WHERE username = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = trim($data->username);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                }else{
                    $username = trim($data->username);
                    $_SESSION["username"] = $username;
                }
            }else{
                //echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    // Validate email ////////////////////////////////////////
    if(empty(trim($data->email)) || !filter_var($data->email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a vaild email.";
    }else{
        $sql = "SELECT id FROM onlineshop_users WHERE email = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = trim($data->email);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $email_err = "This email is already in use.";
                }else{
                    $email = trim($data->email);
                    $_SESSION["email"] = $email;
                }
            }else{
                //echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    
    // Validate password /////////////////////////////////////////
    if(empty(trim($data->password))){
        $password_err = "Please enter a password.";     
    }elseif(strlen(trim($data->password)) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }else{
        $password = trim($data->password);
    }
    // Validate confirm password
    if(empty(trim($data->confirmPass))){
        $confirm_password_err = "Please confirm password.";     
    }else{
        $confirm_password = trim($data->confirmPass);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO onlineshop_users (username, password, email, confirmNum) VALUES (?, ?, ?, ?)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss", $param_username, $param_password, $param_email, $param_confirmNum);
            $param_username = $username;
            $param_email = $email;
            //TODO check if this random number already exists
            $param_confirmNum = rand(100000, 999999);
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            if($stmt->execute()){
                $_SESSION["confirmNum"] = $param_confirmNum;
                require_once "confirm.php";
            } else{
                //echo "Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    $response = array(
    "username_err" => $username_err,
    "email_err" => $email_err,    
    "password_err" => $password_err,
    "confirm_password_err" => $confirm_password_err,
    "username_info" => $username_info);
    echo json_encode($response);
    // Close connection
    $conn->close();
}
?>