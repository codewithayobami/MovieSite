<?php
session_start();
include 'db_connect.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get the user from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Update last active timestamp
            $update_sql = "UPDATE users SET last_active = NOW() WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);

            if (!$update_stmt) {
                die("Error preparing update statement: " . $conn->error);
            }

            $update_stmt->bind_param("i", $user['id']);
            if ($update_stmt->execute()) {
                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header('Location: admin_dashboard.php'); // Redirect to admin dashboard
                } else {
                    header('Location: movie.php?section=all'); // Redirect to user dashboard
                }
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error updating last active status.</div>";
            }

        } else {
            echo "<div class='alert alert-danger'>Invalid email or password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid email or password.</div>";
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
    <title>Login</title>
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
    <div class="container mt-3">
        <form method="POST" action="login.php">
            <div style='background: rgb(24, 23, 23); padding: 20px; border-radius: 15px;'>
                <br>
                <center>
                    <h2 style="color:  rgb(212, 19, 19); font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">Are You A Member?</h2>
                    <p style="color: grey;">Let's Verify!</p>
                </center>
                <br>
                    <div class="form-group">
                        <input type="email" class="form-control bg-dark text-light " id="email" name="email" placeholder='Email' required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control bg-dark text-light "  id="password" name="password" placeholder='Password' required>
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Login</button>
                    <br>
                    <br>
                    <p style="color: lightgrey;">Not a member yet? You can <a href="signup.php">Register Here Now!</a> </p>
            </div>
        </form>
    </div>
</body>
</html>
