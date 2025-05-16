<?php
session_start();
include 'db_connect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ( $user['status'] == 'blocked') {
    header('Location: blocked.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>settings</title>
    <?php include 'cdn.php'; ?>
</head>
<style>
        .profile-header {
      position: relative;
      background-color: blue;
      height: 200px;
    }
</style>
<body style='background: black; color: white;'>
      <!-- Profile Header -->
  <div class="profile-header">
    <center>
      <br><br><br>
      <h2>Settings</h2>
    </center>
  </div>
  <br><br>
  <div class="container">
    <a href="change_password.php" style='text-decoration: none;'>
        <div style='padding: 17px; border-radius: 5px; display: flex;' class='bg-dark'>
            <h5>Change Password</h5>
            <i style='margin-left: auto; position: relative; bottom: 23px; color: white;'>→</i>
        </div>
    </a>
    <br>
    <a href="edit_profile.php" style='text-decoration: none;'>
        <div style='padding: 17px; border-radius: 5px; display: flex;' class='bg-dark'>
            <h5>Edit Profile</h5>
            <i style='margin-left: auto; position: relative; bottom: 23px; color: white;'>→</i>
        </div>
    </a>
    <br>
    <a href="users_chat.php" style='text-decoration: none;'>
        <div style='padding: 17px; border-radius: 5px; display: flex;' class='bg-dark'>
            <h5>Chats</h5>
            <i style='margin-left: auto; position: relative; bottom: 23px; color: white;'>→</i>
        </div>
    </a>
    <br>
    <a href="users_upload.php" style='text-decoration: none;' onclick="alert('Tell us which movie we should upload')">
        <div style='padding: 17px; border-radius: 5px; display: flex;' class='bg-dark'>
            <h5>Request Movie Upload</h5>
            <i style='margin-left: auto; position: relative; bottom: 23px; color: white;'>→</i>
        </div>
    </a>
    <br>
    <a href="login.php" style='text-decoration: none;' onclick="return confirm('Are you sure you wanna logout')">
        <div style='padding: 17px; border-radius: 5px; display: flex;' class='bg-dark'>
            <h5>logout</h5>
            <i style='margin-left: auto; position: relative; bottom: 23px; color: white;'>→</i>
        </div>
    </a>
    <br>
    <br>
    <br>
    
  </div>
  <?php include 'footer.php'; ?>

</body>
</html>