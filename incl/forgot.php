<?php
//Set PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include config file
require_once "connect.php";
require_once  "vendor/autoload.php";
session_start();
$forgot_msg = $forgot_err = "";
$data = json_decode(file_get_contents("php://input"));

if(isset($data->email)){
    $email = $data->email;
    //echo $email;
    $sql = "SELECT confirm FROM onlineshop_users WHERE email = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $param_email);
        $param_email = $email;
        $stmt->execute();
        $stmt->store_result();
        //following will only work if user has confimred email first i.e. confirm === 1
        if($stmt->num_rows == 1){
            $stmt->bind_result($boundConfirm);
            $stmt->fetch();
            //echo $boundConfirm. "<br>";
            if($boundConfirm == 1){
                $mail = new PHPMailer(true);
                sendEmail($mail, $email, $boundConfirm, DOMAIN);
                //$stmt->close();
                //$conn->close();
                $forgot_msg = "Please check your email to reset your password.";
            }else{
                $forgot_err = "This account has not been confirmed. First confirm this email address.";
            }
        }else{
            $forgot_err = "Email not found.";
        }
    }
    $stmt->close();
}
$response = array("forgot_err" => $forgot_err,
                  "forgot_msg" => $forgot_msg);
echo json_encode($response);
$conn->close();
 
    //PHPMailer send email to user
    function sendEmail($param, $param4, $param5, $param6){
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
                $param->Subject = 'User Profiles App Password Reset';
                $param->Body    = 
        "Hi <br> 
        A request for a password reset has been sent to this email address <br>
        Please follow the link below to reset your passowrd <br>
        <a href=\"".$param6."/incl/resetByEmail.php?email=".$param4."&confirm=".password_hash($param5, PASSWORD_DEFAULT)."\">here</a> ";
                $param->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $param->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$param->ErrorInfo}";
            }
    }//endsendEmail

?>