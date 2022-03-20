<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

//unset($_SESSION["guestname"]);
include "connect.php";
session_start();
$data = json_decode(file_get_contents("php://input"));

if(isset($_SESSION["username"])){
        foreach($data as $x => $x_value) {
            //$sql .= "Key=" . $x . ", Value=" . $x_value;
            $sql = "SELECT stock FROM onlineshop_products WHERE product = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $param_product);
            $param_product = $x;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows > 0){
                    $stmt->bind_result($stock);
                    if($stmt->fetch()){
                        $sql = "UPDATE onlineshop_products SET stock=? WHERE product=?";
                        if($stmt = $conn->prepare($sql)){
                            $stmt->bind_param("ss", $param_stock, $param_product);
                            $param_stock = $stock - intval($x_value);
                            if($stmt->execute()){
                                echo "ok usa";
                            }else{
                                echo $stmt->error;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }
}
$conn->close();

?>