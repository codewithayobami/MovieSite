<?php
include 'db_connect.php';
session_start();

// Ensure only admins can delete users
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get the user ID and validate it
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug: Check if the user ID is valid
if ($user_id > 0) {
    // Check if user exists
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    if ($result->num_rows === 0) {
        echo "No user found with ID: " . $user_id;
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        header("Location: admin_users.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid user ID.";
}

// Close the connection
$conn->close();
?>
