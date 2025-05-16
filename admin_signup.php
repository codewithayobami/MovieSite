<!-- <?php
// session_start();
// include 'db_connect.php';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
//     $role = $_POST['role'];

    
    // // Handle profile picture upload
    // $profile_pic = $_FILES['profile_pic']['name'];
    // $target_dir = "uploads/";
    // $target_file = $target_dir . basename($profile_pic);
    // move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

    // $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    // $stmt->bind_param("sssss", $username, $email, $password, $role);
    
    // if ($stmt->execute()) {
    //     echo "Sign up successful!";
    //     header("Location: login.php");
    // } else {
    //     echo "Error: " . $stmt->error;
    // }
    // $stmt->close();
// }
?> -->

<?php

session_start();
include 'db_connect.php';
include 'admin_header.php';

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];;

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success container'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    // Close connections
    $stmt->close();
    $conn->close();

}
?>



<div class="container mt-4">
    <h2>Add New User</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder='Username' required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder='Email' required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder='Password' required>
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" name="role" value="user" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
</div>
