<?php 
session_start();

$response = array();

include 'db_connect.php';

$sql_movie_api = "select * from users";
$result = mysqli_query($conn, $sql_movie_api);

if ($result) {
    header("Content-Type: json");
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    $i=0;
    while ($row = mysqli_fetch_assoc($result)) {
        $response[$i]['id'] = $row['id'];
        $response[$i]['username'] = $row['username'];
        $response[$i]['email'] = $row['email'];
        $i++;
    }
    echo json_encode($response,JSON_PRETTY_PRINT);
}

?>
