<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once  "vendor/autoload.php";


//unset($_SESSION["guestname"]);
include "connect.php";
//Emails in HTML escaped
include_once "confirmOrder_customerESC.php";
include_once "confirmOrder_merchantESC.php";
session_start();
$data = json_decode(file_get_contents("php://input"));
$sql = "";
$stmt;
$emailLineItems = "";

//this is a part of the previous if statement. Prev if sets sql this sets user for stmt->execute
if(isset($_SESSION["username"])){
        //check if user is logged in but does not have an active cart
        $sql = "SELECT id, email FROM onlineshop_users WHERE username = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = $_SESSION["username"];
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows > 0){ 
                    $stmt->bind_result($userid, $useremail);
                    if($stmt->fetch()){
                        $sql = "SELECT id, cart FROM onlineshop_activecart WHERE username = ?";
                        if($stmt = $conn->prepare($sql)){
                            $stmt->bind_param("s", $param_username);
                            $param_username = $_SESSION["username"];
                            if($stmt->execute()){
                                $stmt->store_result();
                                if($stmt->num_rows > 0){
                                    $stmt->bind_result($cartid, $cartactive);
                                    //$emailLineItems = buildLineItems($cartactive);
                                    if($stmt->fetch()){
                                        $sql = "INSERT INTO onlineshop_orders (userid, username, useremail, activecartid, cart, delivery, details, status, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("ississsss", $param_userid, $param_username, $param_useremail, $param_activecartid, $param_cart, $param_delivery, $param_details, $param_status, $param_total);
                                        $param_userid = $userid;
                                        $param_username = $_SESSION["username"];
                                        $param_useremail = $useremail;
                                        $param_activecartid = $cartid;
                                        $param_cart = $cartactive;
                                        $param_delivery = $data->delivery;
                                        $param_details = $data->details;
                                        $param_status = $data->status;
                                        $param_total = $data->grandTotal;
                                        if($stmt->execute()){
                                            $sql = "SELECT id FROM onlineshop_orders WHERE username = ? ORDER BY id DESC";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("s", $param_username);
                                            $param_username = $_SESSION["username"];
                                            $stmt->execute();
                                            $stmt->store_result();
                                            if($stmt->num_rows > 0){
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $row = $result->fetch_array(MYSQLI_NUM);
                                                //echo $row[0];
                                                $newOrderID = $row[0];
                                                $sql = "UPDATE onlineshop_orders SET orderNumber=? WHERE id=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("ss", $param_orderNumber, $param_newOrderID);
                                                $param_orderNumber = $data->orderDate . $newOrderID; 
                                                $param_newOrderID = $newOrderID;
                                                //Send confirmation Emails
                                                //$newcart =  json_decode($cartactive);
                                                $newDelivery = json_decode($param_delivery);
                                                $newDeliveryStr = "<table style=\"margin-left:24px;\"><tr><td> Email : </td><td>" . $newDelivery->email . "</td></tr><tr><td> Name : </td><td>" . $newDelivery->firstname . " " . $newDelivery->surname . "</td></tr><tr><td> Address : </td><td>" . $newDelivery->address1 . ", " . $newDelivery->address2 . ", " . $newDelivery->city . ", " . $newDelivery->state . ", " . $newDelivery->zip . "</td></tr></table>";
                                                //echo $newDeliveryStr;
                                                $confirmOrder_customerStr = confirmOrder_customer($bodyStart, strval($param_orderNumber), $bodyStart01, $newDeliveryStr, $bodyStart02, buildLineItems($cartactive), $bodyEnd, "$ ".number_format($param_total, 2), $bodyEnd01);
                                                //echo buildLineItems($newcart);
                                                $mail = new PHPMailer(true);
                                                sendEmail($mail, $param_username, $useremail, "Starter Store App", DOMAIN, $confirmOrder_customerStr);
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            
                        }
                    }
                }
            }
        }
}
if($stmt->execute()){
    $response = array(  "userid" => $userid,
                        "username" => $_SESSION["username"],
                        "useremail" => $useremail,
                        "cart" => $cartactive,
                        "ordernumber" => $param_orderNumber);
    echo json_encode($response);
    //echo json_encode($cartactive);
    
    //echo buildLineItems($cartactive);
    
    //$myvar = json_decode($cartactive,true);
    //echo var_dump($myvar);
}else{
    echo $stmt->error;
}

$stmt->close();
$conn->close();

function buildLineItems($param){
    $newcart =  json_decode($param);
    $tableRows = "";
    foreach($newcart as $x=>$y){
        if($y->quantity > 0){
            $tableRows .= "<tr><td>" . $y->short_desc . "</td><td>" . $y->price . "</td><td>" . $y->quantity . "</td><td>". number_format($y->price * $y->quantity, 2) ."</td></tr>";  
            //$tableRows .= $x;
        }    
    }
    return $tableRows;
    //return var_dump($newcart);
} 

//PHPMailer send email to user
function sendEmail($param, $param3, $param4, $param6, $param7, $param8){
            try {
                //Server settings
                $param->SMTPDebug = 0;                    // Enable verbose debug output
                $param->isSMTP();                         // Set mailer to use SMTP
                $param->Host       = 'smtp.mailtrap.io';  // Specify main and backup SMTP servers
                $param->SMTPAuth   = true;                // Enable SMTP authentication
                $param->Username   = 'XXXXXXXXXXXXXX';    // SMTP username
                $param->Password   = 'XXXXXXXXXXXXXX';    // SMTP password
                $param->SMTPSecure = 'TLS';               // Enable TLS encryption, `ssl` also accepted
                $param->Port       = 2525;                // TCP port to connect to

                //Recipients
                $param->setFrom('support@rabbitmacht.co.za', 'RABBITMACHT Support');
                $param->addAddress($param4, 'a good guy');     // Add a recipient
                $param->addReplyTo('support@rabbitmacht.co.za', 'RABBITMACHT Support');

                // Content
                $param->isHTML(true);                                  // Set email format to HTML
                $param->Subject = 'Order Confirmation';
                //$param->Body    ="Hi ".$param3. " <br>  Welcome to " .$param6. "<br> Please confirm your email address by visiting the link below <br> <a href=\"".$param7."/incl/confirmEmail.php?email=".$param4."&confirmNum=".password_hash($param5, PASSWORD_DEFAULT)."\">here</a> ";
                $param->Body = $param8;
                $param->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $param->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$param->ErrorInfo}";
            }
    }//endsendEmail

?>