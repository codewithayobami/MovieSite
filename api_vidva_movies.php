<?php
session_start();
include 'db_connect.php';

// Ensure the API connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure the api_keys table exists
$sql = "CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(64) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
)";
if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

// Generate API keys for users without one
function generateApiKey() {
    return bin2hex(random_bytes(32));
}

$sql = "SELECT id FROM users WHERE id NOT IN (SELECT user_id FROM api_keys)";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($user = mysqli_fetch_assoc($result)) {
        $user_id = $user['id'];
        $api_key = generateApiKey();

        $insert_sql = "INSERT INTO api_keys (api_key, user_id) VALUES ('$api_key', $user_id)";
        if (!mysqli_query($conn, $insert_sql)) {
            echo "Error generating API key for user ID $user_id: " . mysqli_error($conn) . "\n";
        }
    }
}

// Set headers for API
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Check for Authorization header with Bearer token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization header is required']);
    exit;
}

$authorization_header = $headers['Authorization'];

// Check if the Bearer token is in the correct format
if (strpos($authorization_header, 'Bearer ') !== 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Authorization format is invalid']);
    exit;
}

$api_key = substr($authorization_header, 7); // Extract the API key from the Bearer token

// Validate the API key
$sql = "SELECT * FROM api_keys WHERE api_key = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid or inactive API key']);
    exit;
}

// Fetch movies
$sql_api = "SELECT * FROM movies";
$result = mysqli_query($conn, $sql_api);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));  // Show query error if it fails
}

if ($result->num_rows > 0) {
    $response = [];
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $response[$i]['id'] = $row['id'];
        $response[$i]['title'] = $row['title'];
        $response[$i]['image'] = $row['image'];
        $response[$i]['file'] = $row['file'];
        $response[$i]['section'] = $row['section'];
        $i++;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No movies found or query failed']);
}
?>