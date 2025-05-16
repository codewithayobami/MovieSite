<?php 
session_start();
include("db_connect.php");


if (isset($_GET['q'])) {
    $query = $conn->real_escape_string($_GET['q']);
    $result = $conn->query("SELECT * FROM movies WHERE title LIKE '%$query%'");

    if ($result->num_rows > 0) {
        while ($movie = $result->fetch_assoc()) {
            echo "<div class='movie-card'>
                    <img src='{$movie['image']}' alt='{$movie['title']}'>
                    <h3>{$movie['title']}</h3>
                    <a href='{$movie['file']}' class='download-btn' download='{$movie['title']}.mp4'>Download</a>
                  </div>";
        }
    } else {
        echo "<p>No results found for your search.</p>";
    
    }
}

$conn->close();

?>