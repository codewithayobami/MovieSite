


<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$response = array();

include 'db_connect.php';

$sql_movie_api = "SELECT * FROM users";
$result = mysqli_query($conn, $sql_movie_api);

if ($result) {
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Check for Authorization header (works with Apache/Nginx, or when using a local server)
    $headers = getallheaders();
    // if (!isset($headers['Authorization'])) {
    //     // Fallback method to check 'Authorization' header from PHP's $_SERVER superglobal
    //     if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    //         echo json_encode(['error' => "API key is required!"]);
    //         exit;
    //     }
    //     $apiKey = $headers['Authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'];  // Fallback to $_SERVER if needed
    // } else {
    //     $apiKey = $headers['Authorization'];
    // }

    if (isset($headers['Authorization'])) {
        # code...
        $apiKey = $headers['Authorization'];
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $apiKey = $_SERVER['HTTP_AUTHORIZATION'];
    } else {
        echo json_encode(['Error' => "Api key must be provided"]);
    }

    // Valid API Key
    $stmt = $conn->prepare("SELECT * FROM users WHERE api_key = ?");
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['Error' => 'Invalid API key']);
        exit;
    }

    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $response[$i]['id'] = $row['id'];
        $response[$i]['username'] = $row['username'];
        $response[$i]['email'] = $row['email'];
        $i++;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo json_encode('error');
}

