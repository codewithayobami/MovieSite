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

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php include 'cdn.php'; ?>
  <style>
    body {
      background-color: black;
      font-family: Arial, sans-serif;
      color: white;
    }
    .profile-header {
      position: relative;
      background-color: blue;
      height: 200px;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 4px solid #fff;
      position: absolute;
      top: -50px;
      left: 20px;
    }
    .profile-info {
      padding: 20px;
      /* background-color: #fff; */
      margin-top: 80px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .profile-stats {
      padding: 20px;
      text-align: center;
      /* background-color: #fff; */
      margin-top: 20px;
      border-radius: 10px;
      display: flex;
      flex-direction: wrap;

    }
    .profile-stats div {
      flex: 1;
    }
    .nav-tabs .nav-link.active {
      background-color: #1da1f2;
      color: #fff;
      border-radius: 5px;
    }
    .feed-item {
      /* background-color: #fff; */
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .feed-item img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .feed-item .username {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- Profile Header -->
  <div class="profile-header">
    <center>
      <br><br><br>
      <h2>Profile</h2>
    </center>
  </div>

  <!-- Profile Image & Info -->
  <div class="container" style='background: black; color: white;'>
    <div class="row">
      <div class="col-md-12">
        <img src="<?= $user['profile'] ?>" alt="Profile Image" class="profile-img">
        <div class="profile-info bg-dark text-light">
          <h3><?= $user['username'] ?></h3>
          <p>@<?= $user['username'] ?></p>
          <a href='admin_edit_profile' class="btn btn-outline-primary">Edit Profile</a>
        </div>
      </div>
    </div>

    <br><br>
    <!-- Profile Stats -->
    <div class=" row bg-dark text-light" style='border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
      <div class="col-md-12">
        <div class="profile-stats">
          <div>
            <h6>Email<i class='fa fa-check-circle'> </i> </h6>
            <p><?= $user['email'] ?></p>
          </div>
          <div>
            <h6>Status</h6>
            <p><?= $user['status'] ?></p>
          </div>
          <div>
            <h6>Last Login</h6>
            <p><?= $user['last_login'] ?></p>
          </div>
        </div>
      </div>
    </div>   

  <br><br><br>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <?php include 'footer.php'; ?>
</body>
</html>
