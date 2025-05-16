<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_input = $_POST['otp'];
    $email = $_SESSION['email'];

    // Check if the OTP matches and is not expired
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $otp_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is correct, mark email as verified
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "Email verified successfully! You can now <a href='login.php'>login</a>.";
    } else {
        echo "Invalid OTP or OTP has expired.";
    }
}

?>

<!-- HTML Form for OTP -->
<form method="POST" action="">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verify OTP</button>
</form>