<?php
include 'db_connect.php';

session_start();

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
    <title>User Profile</title>
    <?php include 'cdn.php'; ?>
    
    <style>
        img{
            border-radius: 200px;
            height: 100px;
            width: 100px;
        }
    </style>
</head>
<body class='bg-dark text-light'>
    <br><br>
        <div style='height: 60vh;'>
            <img src="<?php echo $user['profile']; ?>" alt="Profile Image" >
            <div >
                <p>Name: </p>
                <h5><?= $user['username'] ?></h5>
            </div>
            <div >
                <p>Email: </p>
                <h5><?= $user['email'] ?></h5>
            </div>
            <div >
                <p>Role: </p>
                <h5><?= $user['role'] ?></h5>
            </div>
            <div >
                <p>Status: </p>
                <h5><?= $user['status'] ?></h5>
            </div>
            <div >
                <p>Last Login: </p>
                <h5><?= $user['last_login'] ?></h5>
            </div>
        </div>

    <?php include 'footer.php'; ?>
</body>
</html>
