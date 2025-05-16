<?php
session_start();
include 'db_connect.php';


// Check if the 'chat_with' parameter is set in the URL
if (isset($_GET['chat_with'])) {
    $chat_with = $_GET['chat_with'];
    $current_user_id = $_SESSION['user_id'];

    // Fetch the messages between the current user and the selected user
    $sql = "SELECT * FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $current_user_id, $chat_with, $chat_with, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display chat messages
    echo "<div class='chat-container bg-dark text-light'>";
    while ($message = $result->fetch_assoc()) {
        $sender = ($message['sender_id'] == $current_user_id) ? "You" : "Other";
        echo "<p><strong>$sender:</strong> " . htmlspecialchars($message['message']) . "</p>";
    }
    echo "</div>";

    // Form to send a new message
    echo "
        <form method='POST' action='send_message.php' id='send-message-form' class='send-message-form'>
            <input type='hidden' name='receiver_id' value='$chat_with'>
            <div class='col-md-12'>
                <div class='form-group'>
                    <textarea name='message' placeholder='Type your message here...' class='form-control bg-dark text-light'></textarea>
                    <button type='submit' class='btn btn-primary ml-2'>Send</button>
                </div>
            </div>
        </form>
    ";

} else {
    echo "<p>No user selected for chat.</p>";
}

// $stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.css">
<style>
     .chat-container {
            /* max-width: ; */
            margin: 50px auto;
            background-color: #f1f1f1;
            border-radius: 8px;
            padding: 40px;
            height: 65vh;
            background: black;
        }
        .messages {
            height: 300px;
            overflow-y: scroll;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 10px;
            color: white;
        }
        .message.sent {
            text-align: right;
        }
        .message.received {
            text-align: left;
        }
        .send-message-form {
            display: flex;
        }
        .send-message-form input {
            flex-grow: 1;
        }
</style>
</head>
<body style='background: black;'>
    
</body>
</html>









