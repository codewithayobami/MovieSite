<!-- admin_signup.php -->
<?php
session_start();
include 'db_connect.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Capture the password correctly
    $role = $_POST['role']; // Role (either user or admin)
    $profile_pic = $_FILES['profile_pic'];

    // Ensure the 'uploads' directory exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Handle the profile picture upload
    $profile_pic_target = $uploadDir . basename($profile_pic['name']);
    if (move_uploaded_file($profile_pic['tmp_name'], $profile_pic_target)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        $sql = "INSERT INTO users (username, email, password, profile, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $profile_pic_target, $role);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to upload profile picture.</div>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
      
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
</head>
<body style='background: black;'>
    <br><br><br>
    <br><br>
    <div class="container mt-4">
        <center>
            <h2 class="text-info">Admin Sign Up</h2>
        </center>
        <br>
        <form method="POST" action="adminsignuppagesecrete.php" enctype="multipart/form-data" >
            <div class="form-group">
                <input type="text" class="form-control bg-dark text-light" id="username" name="username" placeholder='Username' required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control bg-dark text-light" id="email" name="email" placeholder='Email' required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control bg-dark text-light" id="password" name="password" placeholder='Password' required>
            </div>
            <div class="form-group">
                <input type="file" class="form-control bg-dark text-light" id="profile_pic" name="profile_pic" accept="image/*" required>
            </div>
            <input type="hidden" name="role" value="admin"> <!-- Hidden role for Admin -->
            <br>
            <center>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </center>
        </form>
    </div>
</body>
</html>