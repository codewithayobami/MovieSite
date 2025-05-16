<?php


$conn = new mysqli("localhost", "test_user", "password123", "test_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


$sql = "
    CREATE TABLE IF NOT EXISTS movies (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image VARCHAR(255) NOT NULL,
        file VARCHAR(255) NOT NULL,
        section VARCHAR(50) NOT NULL
)";

$sql_users = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        profile VARCHAR(255),
        role ENUM('user', 'admin') DEFAULT 'user',
        status ENUM('active', 'blocked') DEFAULT 'active',
        blocked_until DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        otp VARCHAR(10) DEFAULT NULL,
        online_status ENUM('online', 'offline') DEFAULT 'offline',
        api_key VARCHAR(64) NOT NULL,
        last_active DATETIME NULL
    )

";

$sql_user_logs = "

    CREATE TABLE IF NOT EXISTS user_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )


";

$sql_otp_requests = "

    CREATE TABLE IF NOT EXISTS otp_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        otp INT(6) NOT NULL,
        otp_expiry DATETIME DEFAULT NULL
    )


";




$createChatsTable = "

CREATE TABLE IF NOT EXISTS chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    receiver_id INT,
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
)

";

$pass_resets = "

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(100) NOT NULL,
    token_expiry DATETIME
)

";


if ($conn->query($sql) === TRUE) {
    // echo "Table 'movies' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_users) === TRUE) {
    // echo "Table 'users' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_user_logs) === TRUE) {
    // echo "Table 'user_logs' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($createChatsTable) === TRUE) {
    // echo "Table 'createChatsTable' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($sql_otp_requests) === TRUE) {
    // echo "Table 'sql_otp_requests' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($conn->query($pass_resets) === TRUE) {
    // echo "Table 'pass_resets' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

?>
