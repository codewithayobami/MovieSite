<?php
include 'db_connect.php';
session_start();

// Ensure only admins can block/unblock users
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ($user['status'] == 'active') {
    // Block user for 30 days
    $block_until = date('Y-m-d H:i:s', strtotime('+0.1 day'));
    $conn->query("UPDATE users SET status = 'blocked', blocked_until = '$block_until' WHERE id = $user_id");
} else {
    // Unblock user
    $conn->query("UPDATE users SET status = 'active', blocked_until = NULL WHERE id = $user_id");
}

header("Location: admin_users.php");
?>
