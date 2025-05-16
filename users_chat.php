<?php
session_start();
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


$current_user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .user-list {
            max-width: 600px;
            margin: 50px auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .user-list h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .user-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="user-list">
        <h3>Start a Chat</h3>
        <ul class="list-group">
            <?php while ($user = $result->fetch_assoc()): ?>
                <li class="list-group-item user-item">
                    <span><?php echo htmlspecialchars($user['username']); ?></span>
                    <a href="chat.php?chat_with=<?php echo $user['id']; ?>" class="btn btn-primary">Chat</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
