<?php
session_start();
// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
include 'admin_header.php';

// Update last active timestamp for current user
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}

// Fetch all users from the database
$result = $conn->query("SELECT *, (NOW() - INTERVAL 5 MINUTE) < last_active AS online_status FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <style>
        .table {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <center>
        <h1>Manage Users</h1>
    </center>
    <br><br>

    <div class="table">
    <table class='table table-bordered text-light container'>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
                <th>Last Login</th>
                <th>Online Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['status'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td><?= $user['last_login'] ?></td>
                    <td><?= $user['online_status'] ? 'Online' : 'Offline' ?></td>
                    <td>
                        <a href="view_user.php?id=<?= $user['id'] ?>">View Details</a>
                        <a href="block_user.php?id=<?= $user['id'] ?>"><?= $user['status'] == 'active' ? 'Block' : 'Unblock' ?></a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    
</body>
</html>
<br><br>
