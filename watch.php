<?php 
include 'db_connect.php'; 
session_start(); 

function encodeUrl($url) {
    return htmlspecialchars(urlencode($url));
}

function getFileExtension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}
?>

<div>
  <?php
  if (isset($_GET['id'])) {
    $user_id = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM movies WHERE id = $user_id");

    if ($result && $result->num_rows > 0) {
      while ($movie = $result->fetch_assoc()) {
        // Decode the JSON string for cast
        $casts = json_decode($movie['cast'], true);

        // Encode the movie file URL
        $encodedFileUrl = encodeUrl($movie['file']);

        // Get the file extension to handle file format
        $fileExtension = getFileExtension($movie['file']);

        // Ensure the correct video format is displayed
        echo "
          <div class='movie-card'>
            <video controls class='movie-video'>
              <source src='{$encodedFileUrl}' type='video/{$fileExtension}'>
              Your browser does not support the video tag.
            </video>
            <br><br>
            <h2 class='movie-title container'>Title: {$movie['title']}</h2>
            <a href='{$encodedFileUrl}' class='download-btn' download='{$movie['title']}.{$fileExtension}'>Download</a>
            <div class='cast-container'>
            <br>
              <h3 class='cast-title'>Cast</h3>
              <div class='cast-list'>
        ";

        // Check if $casts is an array and contains valid data
        if (is_array($casts)) {
          foreach ($casts as $cast) {
            // Check if both 'image' and 'name' keys exist in the cast array
            if (isset($cast['image']) && isset($cast['name'])) {
              echo "
                <div class='cast-item'>
                  <img src='{$cast['image']}' alt='{$cast['name']}' class='cast-image'>
                  <p class='cast-name'>{$cast['name']}</p>
                </div>
              ";
            }
          }
        } else {
          echo "<p>No cast information available.</p>";
        }

        echo "
              </div>
            </div>
          </div>
        ";
      }
    } else {
      echo "<p class='no-results'>No movies found.</p>";
    }
  } else {
    echo "<p class='error-message'>Invalid movie ID.</p>";
  }
  ?>
</div>

<?php include 'cdn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Or Download Movie On Vidva</title>
    <style>
body {
  background: #000;
  font-family: Arial, sans-serif;
}

.movie-card {
  margin: 0px;
}

.movie-video {
  width: 100%;
  height: 300px;
  border-bottom: 2px solid #e50914;
}

.movie-title {
  color: #fff;
  font-size: 18px;
  margin: 10px 0;
}

.download-btn {
  display: inline-block;
  padding: 10px 20px;
  margin: 10px 10px 15px;
  background-color: blue;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.download-btn:hover {
  background-color: #b20610;
}

.no-results, .error-message {
  color: #fff;
  text-align: center;
  margin-top: 20px;
  font-size: 18px;
}

.cast-container {
  margin-top: 25px;
  padding: 20px;
}

.cast-title {
  color: #fff;
  font-size: 16px;
  margin-bottom: 10px;
}

.cast-list {
  display: flex;
  overflow-x: auto;
}

.cast-item {
  margin-right: 10px;
  text-align: center;
}

.cast-image {
  height: 150px;
  width: auto;
  border-radius: 5px;
}

.cast-name {
  color: #fff;
  font-size: 14px;
  margin-top: 5px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
  .movie-card {
    width: 100%;
  }
  .movie-video {
    height: 300px;
  }
}
</style>
</head>
<body>
    
    <br><br><br><br>

</body>
</html>