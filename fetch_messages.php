<?php
session_start();
include 'db_connect.php';

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['chat_with'];

// Fetch messages between the two users
$sql = "SELECT * FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $message_class = ($row['sender_id'] == $sender_id) ? 'sent' : 'received';
    echo "<div class='message {$message_class}'>" . htmlspecialchars($row['message']) . "</div>";
}

$stmt->close();
$conn->close();
?>
