<?php 
session_start();

$response = array();
include 'db_connect.php';

$sql_api  = "select * from movies";
$result = mysqli_query($conn, $sql_api);
if ($result) {
    header("Content-Type: json");
    header("Access-Control-Allow-Origin: *"); // This allows all websites
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type");
    $i=0;
    while($row = mysqli_fetch_assoc($result)) {
        $response[$i]['id'] = $row['id'];
        $response[$i]['title'] = $row['title'];
        $response[$i]['image'] = $row['image'];
        $response[$i]['file'] = $row['file'];
        $response[$i]['section'] = $row['section'];
        $i++;
    }
    echo json_encode($response,JSON_PRETTY_PRINT);
}

?>


