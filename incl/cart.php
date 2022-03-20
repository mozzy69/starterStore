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
//$cartData = $_GET['q'];

$cartData = file_get_contents("php://input");
echo $cartData;
$sql = "";
$stmt;

//this will be true if user has just registered or loggedin and been shopping before
if(isset($_SESSION["username"]) && isset($_SESSION["guestname"])){
    //check if user already exists and update thier cart
    //they shopped first as guest then loggedin to existing account
    $sql = "SELECT id FROM onlineshop_activecart WHERE username = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = $_SESSION["username"];
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows > 0){ 
                    $stmt->bind_result($id);
                    if($stmt->fetch()){
                        $sql = "UPDATE onlineshop_activecart SET cart=? WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("si", $param_cart, $param_id);
                        $param_cart = $cartData;
                        $param_id = $id;
                        $stmt->execute();
                        //delete previous guest cart
                        if(isset($_SESSION["guestname"])){
                            $sql = "DELETE FROM onlineshop_activecart WHERE username=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $param_guest);
                            $param_guest = $_SESSION["guestname"];
                            $stmt->execute();
                        }
                        //$stmt->close();
                        //$conn->close();
                        unset($_SESSION["guestname"]);
                        die("");
                    }else{
                        echo "something else went wrong";
                        }        
                    }else{
                        //swop guestname for username
                        $sql = "UPDATE onlineshop_activecart SET username=? WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("si", $param_user, $param_id);
                        $param_user = $_SESSION["username"];
                        $tempID = str_replace("Guest","", $_SESSION["guestname"]);
                        $param_id = (int)$tempID;
                        $stmt->execute();
                        unset($_SESSION["guestname"]);
                        die("");
                    }
            }
        }
    }

//this is a part of the previous if statement. Prev if sets sql this sets user for stmt->execute
if(isset($_SESSION["username"])){
        //check if user is logged in but does not have an active cart
        $sql = "SELECT id FROM onlineshop_activecart WHERE username = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = $_SESSION["username"];
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows > 0){ 
                    $sql = "UPDATE onlineshop_activecart SET cart=? WHERE username=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $param_cart, $param_user);
                }else{
                    $sql = "INSERT INTO onlineshop_activecart (username, cart) VALUES (?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $param_user, $param_cart);;
                }
            }
        }
    $param_user = $_SESSION["username"];
    
    }elseif(isset($_SESSION["guestname"])){
        
        $sql = "UPDATE onlineshop_activecart SET cart=? WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $param_cart, $param_user);
        $param_user = $_SESSION["guestname"];    
}else{
        //new entry for guest
        //will fail to set correct int eg guest30 if no entries exist and id is eg 29
        //in other words, if all preveious entries have been deleted 
        
        //create entry to determine what the correct id is for the guest
        $sql = "INSERT INTO onlineshop_activecart (username) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param_user);
        $param_user = "tempuser";
        $stmt->execute();
    
        $sql = "SELECT id FROM onlineshop_activecart WHERE username=?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_tempuser);
            $param_tempuser = "tempuser";
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows > 0){ 
                    $stmt->bind_result($id);
                    echo $id;
                    if($stmt->fetch()){
                        $sql = "UPDATE onlineshop_activecart SET cart=?, username=? WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssi", $param_cart, $param_user, $param_id);
                        //$param_cart = $cartData;
                        //$currentID = $id + 1;
                        $_SESSION["guestname"] = "Guest" . $id; 
                        $param_user = $_SESSION["guestname"];
                        $param_id = $id;
                        //$stmt->execute();
                    }else{
                        echo $stmt->error;
                    }
                }else{
                    echo $stmt->error;
                }
            }else{
                echo $stmt->error;
            }
        }else{
            echo $stmt->error;
        }
        /*
        //get new id for tempuser
        $sql = "SELECT id FROM onlineshop_activecart WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param_user);
        $param_user = "tempuser";
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        
        //update guestname
        $sql = "UPDATE onlineshop_activecart SET cart=?, username=? WHERE id=?";
        $stmt = $conn->query($sql);
        $stmt->bind_param("ssi", $param_cart, $param_user, $param_id);
        //$row = mysqli_fetch_row($result);
        $currentID = $id + 1;
        $_SESSION["guestname"] = "Guest" . $currentID; 
        $param_user = $_SESSION["guestname"];
        $param_id = $id;*/
}
$param_cart = $cartData;
if($stmt->execute()){
    echo "all good";
}else{
    echo $stmt->error;
}

$stmt->close();
$conn->close();

?>