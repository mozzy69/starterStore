<?php
//Set PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once  "vendor/autoload.php";
 
// Define variables and initialize with empty values
$email = $_SESSION["email"];
$username = $_SESSION["username"];
$confirmNum = $_SESSION["confirmNum"];
$username_info = "";
$sql = "SELECT confirmNum FROM onlineshop_users WHERE email = ?";

if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($boundConfirmNum);
                    if($stmt->fetch()){
                        if($boundConfirmNum == $confirmNum){
                        $sql = "UPDATE onlineshop_users SET confirm='0' where email=?";
                        if($stmt = $conn->prepare($sql)){
                            $stmt->bind_param("s", $param_email);
                            $param_email = $email;
                            if($stmt->execute()){
                                $username_info = "Thanks for registering, ". $username . ". Please check your email for further instructions.";
                            } else{
                                echo "Something went wrong. Please try again later.";
                                }
                            }
                        }
                    }
                    $mail = new PHPMailer(true);
                    sendEmail($mail, $username, $email, $confirmNum, "Starter Store App", DOMAIN);
                }else{
                    $email_err = "The email address provided could not be found on our database.";
                    }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
//$stmt->close();
//$conn->close();
 


    //PHPMailer send email to user
    function sendEmail($param, $param3, $param4, $param5, $param6, $param7){
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
                $param->setFrom('lyndon@rabbitmacht.co.za', 'lyndon');
                $param->addAddress($param4, 'a good guy');     // Add a recipient
                $param->addReplyTo('lyndon@rabbitmacht.co.za', 'Information');

                // Content
                $param->isHTML(true);                                  // Set email format to HTML
                $param->Subject = 'Please Confirm Your Email Address';
                $param->Body    = 
        "Hi ".$param3. " <br> 
        Welcome to " .$param6. "<br>
        Please confirm your email address by visiting the link below <br>
        <a href=\"".$param7."/incl/confirmEmail.php?email=".$param4."&confirmNum=".password_hash($param5, PASSWORD_DEFAULT)."\">here</a> ";
                $param->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $param->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$param->ErrorInfo}";
            }
    }//endsendEmail

?>