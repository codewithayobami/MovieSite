
<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: request_password_reset.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $user_id = $_SESSION['user_id'];

        // Update the new password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_new_password, $user_id);

        if ($stmt->execute()) {
            echo "Password reset successful! You can now <a href='login.php'>login</a>.";
            // Clear session variables
            session_unset();
            session_destroy();
        } else {
            $error_message = "Error updating password. Please try again.";
        }
    } else {
        $error_message = "New password and confirmation do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php if (isset($error_message)) echo $error_message; ?>
</body>
</html>