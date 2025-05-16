<!-- user_signup.php -->
<?php
function generateApiKey() {
    return
    bin2hex(random_bytes(32));
}

session_start();
include 'db_connect.php'; // Include database connection file


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Capture the password correctly
    $role = $_POST['role']; // Role (either user or admin)
    $profile_pic = $_FILES['profile_pic'];
    $plans = $_POST['plans'];
    $apiKey = generateApiKey();

    // Ensure the 'uploads' directory exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if ($plans == 'basic') {
        // $timeout = 60 * 60 ;
        $timeout = date('Y-m-d H:i:s', strtotime('+7 days'));

    }

    if ($plans == 'premium') {
        $timeout = date('Y-m-d H:i:s', strtotime('+21 days'));
    }

    if ($plans == 'vip') {
        $timeout = date('Y-m-d H:i:s', strtotime('+42 days'));
    }

    // Handle the profile picture upload
    $profile_pic_target = $uploadDir . basename($profile_pic['name']);
    if (move_uploaded_file($profile_pic['tmp_name'], $profile_pic_target)) {
        
        $email = $_POST['email'];
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $edu = "<div class='alert alert-danger'>Email Already Exist</div>";
            global $edu;
        } else {

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);



            // Insert into the database
            $sql = "INSERT INTO users (username, email, password, profile, role, api_key, plans, timeout) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $username, $email, $hashed_password, $profile_pic_target, $role, $apiKey, $plans, $timeout);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success col-md-4 container'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
                header('Location: login.php'); // Redirect to login page
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to upload profile picture.</div>";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
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
<body style="background: black; color: white;">
    <br><br><br>
    <div class="container mt-3">       
        <form method="POST" action="signup.php" enctype="multipart/form-data">
            <div style='background: rgb(24, 23, 23); padding: 20px; border-radius: 15px;'>
                <?php echo $edu; ?>
                <center>
                    <h2 style="color: rgb(212, 19, 19); font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">Become A Member!</h2>
                    <p style='color: grey;'>Let's journey together 🪐</p>
                </center>
                    <div class="form-group">
                        <input type="text" class="form-control bg-dark text-light" id="username" name="username" placeholder='Username' required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control  bg-dark text-light" id="email" name="email" placeholder='Email' required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control  bg-dark text-light" id="password" name="password" placeholder='Password' required>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control bg-dark text-light" id="profile_pic" name="profile_pic" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <select name="plans" id="plans" class="form-control bg-dark text-light">
                            <option value="basic" hidden selected disabled >Select Your plans</option>
                            <option value="basic" id='500' >₦500 | 1 week</option>
                            <option value="premium">₦1000 | 3 weeks</option>
                            <option value="vip">₦5000 | 1 half months</option>
                        </select>
                    </div>
                    <input type="hidden" name="role" value="user"> <!-- Hidden role for User -->
                    <button type="submit" class="btn btn-primary form-control">Sign Up</button>
                    <br>
                    <br>
                    <p style="color: lightgrey;">Already a member? <a href="login.php">Click to verify Now!</a></p>
            </div>
        
        </form>
    </div>
</body>
</html>