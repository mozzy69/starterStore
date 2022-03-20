<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

session_start();

include "connect.php";
$prodAscArr = array();
$prodCart = "{}";

if(isset($_SESSION["username"]) || isset($_SESSION["guestname"])){
    $user;
    if(isset($_SESSION["username"])){
        $user = $_SESSION["username"];    
    }else{
        $user = $_SESSION["guestname"];
    }
    $sql = "SELECT cart FROM onlineshop_activecart WHERE username =\"" .$user. "\"";
    //echo $sql;
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = mysqli_fetch_row($result);
        $prodCart = $row[0];
    }else{
        $prodCart = "{}";
    }
    //echo $row[0];
    
}

//build the product modal
$productID = $_GET['q'];
$sql = "SELECT * FROM onlineshop_products WHERE id=" . $productID;
$result = $conn->query($sql);
if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $prodAscArr = array("cart"=>$prodCart,
                        "id"=>$row["id"], 
                        "product"=>$row["product"], 
                        "short_desc"=>$row["short_desc"], "long_desc"=>$row["long_desc"], 
                        "price"=>$row["price"], 
                        "image"=>$row["image"], 
                        "gallery"=>$row["gallery"],
                        "options_checkbox"=>$row["options_checkbox"]);
}
   
echo json_encode($prodAscArr);

?>