<?php
include 'db_connect.php';
include 'admin_header.php';

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}



// Fetch user details
$user_id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User Details</title>
    <style>
        img{
            border-radius: 200px;
            height: 100px;
            width: 100px;
        }
    </style>
</head>
<body>
    <br>
    <center>
        <h3>User Details</h3>
    </center>

    <br>


    <div class="container mt-1 col-md-4" >
        <div class="card">
            <div class="card-body">
                <center>
                    <img src="<?php echo $user['profile']; ?>" alt="Profile Image" >
                </center>
                <br>
                <br>
            <div>
            <div style="display: flex; font-weight: bold; font-size: larger; color: rgb(58, 56, 56);">
                <p style="color: rgb(75, 73, 73);">Name:</p>
                <p style="margin-left: auto; color: rgb(75, 73, 73);"><?= $user['username'] ?></p>
            </div>
        </div>
        <div>
            <div style="display: flex; font-weight: bolder; font-size: larger; color: rgb(75, 73, 73);">
                <p style="color: rgb(75, 73, 73);;">Email:</p>
                <p style="margin-left: auto; color: rgb(75, 73, 73);"><?= $user['email'] ?></p>
            </div>
        </div>
        <div>
            <div style="display: flex; font-weight: bolder; font-size: larger; color: rgb(75, 73, 73);">
                <p style="color: rgb(75, 73, 73);;">Role:</p>
                <p style="margin-left: auto; color: rgb(75, 73, 73);"><?= $user['role'] ?></p>
            </div>
        </div>
        <div>
            <div style="display: flex; font-weight: bolder; font-size: larger; color: rgb(75, 73, 73);">
                <p style="color: rgb(75, 73, 73);;">Status:</p>
                <p style="margin-left: auto; color: rgb(75, 73, 73);"><?= $user['status'] ?></p>
            </div>
        </div>
        <div>
            <div style="display: flex; font-weight: bolder; font-size: larger; color: rgb(75, 73, 73);">
                <p style="color: rgb(75, 73, 73);;">Last Login:</p>
                <p style="margin-left: auto; color: rgb(75, 73, 73);"><?= $user['last_login'] ?></p>
            </div>
        </div>
        <br>
        <center>
            <a class="btn btn-primary" href="admin_users.php" >Back</a>                        
            <a class="btn btn-warning" href="block_user.php?id=<?= $user['id'] ?>"><?= $user['status'] == 'active' ? 'Block' : 'Unblock' ?></a>
            <a class="btn btn-danger" href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </center>
            </div>
        </div>
        
    </div>
        <br>
</body>
</html>
