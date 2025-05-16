<?php
session_start();
include 'db_connect.php';
include 'admin_header.php';

// Fetch all movies from the database
$result = $conn->query("SELECT * FROM movies");
while ($movie = $result->fetch_assoc()) {
    echo "
    <div class='movie-section'>
        <div class='movie-card'>
            <img src='{$movie['image']}' alt='{$movie['title']}'>
            <h3>{$movie['title']}</h3>
            <a href='delete_movie.php?id={$movie['id']}' class='delete-btn'>Delete</a>
            <a href='edit_movie.php?id={$movie['id']}' class='edit-btn'>Edit</a>
        </div>
    </div>";
}
?>
