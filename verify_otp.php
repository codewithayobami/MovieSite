
<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_input = $_POST['otp'];

    // Check if the OTP is valid and not expired (10 minutes = 600 seconds)
    if ($otp_input == $_SESSION['otp'] && (time() - $_SESSION['otp_time'] <= 60)) {
        header("Location: reset_password.php");
        exit;
    } else {
        $error_message = "Invalid OTP or OTP has expired.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
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
<body style='background: black;'>
    <br><br><br>
    <form method="POST" class='container'>
        <label for="otp">Enter the OTP sent to your email:</label>
        <br>
        <input type="text" name="otp" required class='form-group col-md-4 container bg-dark text-light'>
        <button type="submit" class='btn btn-ig btn-info'>Verify OTP</button>
    </form>
    <?php if (isset($error_message)) echo $error_message; ?>
</body>
</html>
