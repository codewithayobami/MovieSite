<?php
// Check if a movie ID is provided
if (!isset($_GET['movie_id'])) {
    die('Movie ID is required');
}

$movie_id = htmlspecialchars($_GET['movie_id']);

// Fetch movie details from Vidscr API
function fetchMovieDownloadLink($movie_id) {
    $apiKey = "your-vidscr-api-key"; // Replace with your actual Vidscr API key
    $url = "https://api.vidscr.com/movies/$movie_id/download?api_key=" . $apiKey;

    $response = file_get_contents($url);
    $movie = json_decode($response, true);

    if (!empty($movie['download_url'])) {
        return $movie['download_url'];
    } else {
        return false;
    }
}

// Get the download URL
$download_url = fetchMovieDownloadLink($movie_id);

if ($download_url) {
    // Redirect user to the download URL
    header("Location: $download_url");
    exit();
} else {
    echo "Download link not found for this movie.";
}
?>
