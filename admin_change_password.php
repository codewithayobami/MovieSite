<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the new password in the database
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_new_password, $current_user_id);
            if ($update_stmt->execute()) {
                $success_message = "Password changed successfully!";
                header("Location: login.php");
            } else {
                $error_message = "Error updating password. Please try again.";
            }
            $update_stmt->close();
        } else {
            $error_message = "New password and confirmation do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
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
<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.css">
</head>
<body class='text-light' style='background: black;'>
    <br><br><br>
    <div class="container">
        <center>
            <h2 style='color: blue;'>Change Password</h2>
        </center>
    <br>
    <br>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="password" class="form-control bg-dark text-light" id="current_password" name="current_password" required placeholder='Current Password'>
            </div>
            <div class="form-group">
                <input type="password" class="form-control bg-dark text-light" id="new_password" name="new_password" required placeholder='New Password'>
            </div>
            <div class="form-group">
                <input type="password" class="form-control bg-dark text-light" id="confirm_password" name="confirm_password" required placeholder='Confirm New Password'>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>
