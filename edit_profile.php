<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();


if ( $user['status'] == 'blocked') {
    header('Location: blocked.php');
}






// Fetch current user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    // Check if a new profile picture was uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic = $_FILES['profile_pic']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($profile_pic);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            // Update query including the new profile picture
            $sql = "UPDATE users SET username = ?, email = ?, profile = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $target_file, $user_id);
        } else {
            echo "Error uploading profile picture.";
        }
    } else {
        // Update query without changing the profile picture
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }
    
    // Execute the update query
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
        // Update the session variables after updating the profile
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
    } else {
        echo "<div class='alert alert-danger'>Error updating profile: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php include 'cdn.php'; ?>
  <style>
    body {
      background-color: black;
    }

    .edit-profile-card {
      max-width: 600px;
      margin: 50px auto;
      /* background-color: #fff; */
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .edit-profile-header {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-control {
      margin-bottom: 15px;
      border-radius: 8px;
    }

    .save-btn {
      background-color: #1da1f2;
      color: white;
      border-radius: 8px;
      padding: 10px;
      width: 100%;
    }

    .save-btn:hover {
      background-color: #1991d6;
    }

    .cancel-btn {
      color: #6c757d;
      text-align: center;
      display: block;
      margin-top: 20px;
    }

    .profile-picture-label {
      font-weight: bold;
      display: inline-block;
      margin-bottom: 10px;
    }

    .profile-picture-upload {
      position: relative;
      display: inline-block;
    }

    .profile-picture {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-bottom: 10px;
    }

    .upload-btn {
      display: inline-block;
      padding: 8px 12px;
      background-color: #1da1f2;
      color: #fff;
      border-radius: 8px;
      cursor: pointer;
    }

    .upload-btn:hover {
      background-color: #1991d6;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="edit-profile-card bg-dark">
      <div class="edit-profile-header">Edit Profile</div>

    

      <!-- Edit Profile Form -->
      <form method="POST" action="" enctype="multipart/form-data">
          <!-- Profile Picture Upload Section -->
      <div class="text-center">
      <?php if (!empty($user['profile'])): ?>
                <img src="<?php echo $user['profile']; ?>" alt="Profile Picture" style="max-width: 100px;">
            <?php endif; ?>
        <div class="profile-picture-upload">
        <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">

        </div>
      </div>
        <div class="mb-3">
          <label for="fullName" class="form-label">Username:</label>
          <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>


        <button type="submit" class="btn save-btn">Save Changes</button>
        <a href="profile.php" class="cancel-btn">Cancel</a>
      </form>
    </div>
  </div>
  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


