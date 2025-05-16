<?php
session_start();
include 'PHPMailer-master/src/PHPMailer.php';
include 'PHPMailer-master/src/SMTP.php';
include 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exeception;

include 'db_connect.php';
// include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        
        // Generate OTP and set expiry time
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['otp_time'] = time(); // Current time in seconds


        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Username = 'vidva009@gmail.com';
            $mail->Password = 'VIDVA1234';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
     
            $mail->setFrom('vidva009@gmail.com', 'VIDVA');
            $mail->addAddress("$email");

            $mail->isHTML(true);
            $mail->Subject = "Verify your email address";
            $mail->Body = "
                 <p><strong>Your Otp Code:</strong> $otp</p>
            ";
            $mail->AltBody = "Dear $email,\n This is your otp verification code: $otp";

            $mail->send();
            echo "Otp sent: $otp";
            header("Location: verify_otp.php?=otp_sent_successfully. 202409$otp 89092024648");
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "<p class='alert alert-danger container'> Email not registered. </p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>
</head>
  
      <!-- jQuery -->
      <script src='cdn/jquery-3.1.1.min.js' type='text/javascript'></script>
    <script src='cdn/jquery-ui.min.js' type='text/javascript'></script>
    <link href='cdn/jquery-ui.min.css' rel='stylesheet' type='text/css'>
    
   <link rel="stylesheet" href="cdn/external.css" type="text/css">
    <!--Bootstrap-->
    <script src="cdn/popper.min.js"></script>
    <link href='cdn/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="cdn/bootstrap.min.css">
     <script src='cdn/bootstrap.bundle.min.js' type='text/javascript'></script> 
  <link rel="stylesheet" href="cdn/bootstrap1.min.css"> 
  <script src="cdn/bootstrap.min.js"></script>
  <style>
        body{
            background: black;
            color: white;
        }
  </style>
    <body style='color: white;'>
    <br><br><br>
    <center>
    <h4>Pls Verify Your Email Address</h4>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <form method="POST" class='col-md-8 form-group'>
        <label for="email">Enter your email:</label>
        <br>
        <input type="email" name="email" required class='form-control col-md-4 container bg-dark text-light'>
        <br>
        <button type="submit"  class='btn btn-ig btn-info'>Send OTP</button>
    </form>
    </center>
   
    <?php if (isset($error_message)) echo $error_message; ?>
</body>
</html>
