<?php
require_once "connect.php";
require_once "sanitize.php";

session_start();

$data = json_decode(file_get_contents("php://input"));

if(isset($data->firstname)){
    $username = $data->username;
    $email = $data->email;
    $firstname = sanitizeString($data->firstname);
    $surname = sanitizeString($data->surname);
    $address1 = sanitizeString($data->address->address1);
    $address2 = sanitizeString($data->address->address2);
    $city = sanitizeString($data->address->city);
    $state = sanitizeString($data->address->state);
    $zip = sanitizeString($data->address->zip);
    //echo $username;
    
    $sql = "UPDATE onlineshop_users SET address=?, firstname=?, surname=? WHERE email=?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ssss", $param_address, $param_firstname, $param_surname, $param_email);
        // Set parameters
        $mainAddress = array("address1" => $address1,
                             "address2" => $address2,
                             "city" => $city,
                             "state" => $state,
                             "zip" => $zip);
        $param_address = json_encode($mainAddress);
        $param_firstname = $firstname;
        $param_surname = $surname;
        $param_email = $email;
        $stmt->execute();
        $stmt->store_result();
        echo "Profile Successfully Updated";       
    } 
}

?>