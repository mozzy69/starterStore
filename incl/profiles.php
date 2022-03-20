<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

session_start();
//unset($_SESSION["guestname"]);
include "connect.php";

if(isset($_SESSION["username"])){
    $sql = "SELECT id, username, datetime, email, confirm, address, firstname, surname FROM onlineshop_users WHERE username = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $param_user);
        $param_user = $_SESSION["username"];
        if($stmt->execute()){
            //echo "all good";
            $stmt->store_result();
            if($stmt->num_rows > 0){
                $stmt->bind_result($id, $username, $datetime, $email, $confirm, $address, $firstname, $surname);
                $stmt->fetch();
                $userArr = array("id"=>$id,
                                 "username"=>$username,
                                 "datetime"=>$datetime, 
                                 "email"=>$email,
                                 "confirm"=>$confirm,
                                 "address"=>$address,
                                 "firstname"=>$firstname,
                                 "surname"=>$surname);
            echo json_encode($userArr);
        }else{
            echo $stmt->error;
            }
        }else{
            echo "not cool";
        }
        $stmt->close();
    }
    $conn->close();
}

?>