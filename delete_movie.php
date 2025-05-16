

<!-- Delete Movie (delete_movie.php) -->
<?php
include 'db_connect.php';
$id = $_GET['id'];
$conn->query("DELETE FROM movies WHERE id = $id");
header("Location: admin_movie.php"); // Redirect back to the admin movies page
?>
