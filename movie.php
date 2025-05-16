


<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db_connect.php';

// Fetch movies from the database
$result = $conn->query("SELECT * FROM movies");
if (!$result) {
    die("Error fetching movies: " . $conn->error);
}

// Define sections and store movies in them
$sections = ['anime' => [], 'trending' => [], 'top-rated' => [], 'new' => [], 'english' => [], 'kdrama' => [], 'vidva' => []];


while ($movie = $result->fetch_assoc()) {
    // Normalize section names
    $section = trim(strtolower($movie['section']));
    
    // Group kdrama variations together
    if (in_array($section, ['kdrama', 'k-drama'])) {
        $sections['kdrama'][] = $movie;
    } elseif (isset($sections[$section])) {
        $sections[$section][] = $movie;
    }
}

// Shuffle movies within each section
foreach ($sections as $section => &$movies) {
    shuffle($movies);
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if (!$user || $user['status'] === 'blocked') {
    header('Location: blocked.php');
    exit();
}



$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VIDVA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling for movie titles */
        .movie-card h3 {
            color: white;
            text-decoration: none;
            margin-top: 10px;
        }

        /* Ensuring that links in the movie cards have no default underline */
        .movie-card {
            text-decoration: none;
        }
    </style>
    <script>
        function searchMovies() {
            const query = document.getElementById('search').value.trim();
            const searchResults = document.getElementById('search-results');
            
            if (query === "") {
                searchResults.style.display = 'none';
                searchResults.innerHTML = ''; 
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search.php?q=' + encodeURIComponent(query), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    searchResults.innerHTML = xhr.responseText;
                    searchResults.style.display = xhr.responseText ? 'flex' : 'none';
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <header>
        <h1>VIDVA</h1>
        <input type="text" id="search" class="search-bar" placeholder="Search movies..." onkeyup="searchMovies()">
    </header>
    
    <div id="search-results"></div>

    <nav class="navbar">
        <a href="?section=all" class="<?= !isset($_GET['section']) || $_GET['section'] == 'all' ? 'active' : '' ?>">All</a>
        <a href="?section=trending" class="<?= $_GET['section'] == 'trending' ? 'active' : '' ?>">Trending</a>
        <a href="?section=anime" class="<?= $_GET['section'] == 'anime' ? 'active' : '' ?>">Animation</a>
        <a href="?section=new" class="<?= $_GET['section'] == 'new' ? 'active' : '' ?>">New</a>
        <a href="?section=top-rated" class="<?= $_GET['section'] == 'top-rated' ? 'active' : '' ?>">Top</a>
        <a href="?section=k-drama" class="<?= $_GET['section'] == 'k-drama' ? 'active' : '' ?>">Kdrama</a>
        <a href="?section=english" class="<?= $_GET['section'] == 'english' ? 'active' : '' ?>">English</a>
    </nav>

    <div class="container">
        <?php
        // Get the selected section from the query parameter
        $selected_section = isset($_GET['section']) ? $_GET['section'] : 'all';

        if ($selected_section == 'all') {
            // Display all sections
            foreach ($sections as $section => $movies) {
                echo "<div class='movie-section'>";
                echo "<h2>" . ucfirst($section) . "</h2>";
                echo "<div class='movie-grid'>";
                foreach ($movies as $movie) {
                    if ($movie['section'] === $section) {
                        echo "
                            <a id='t' href='watch.php?id={$movie['id']}' class='movie-card'>
                                <img src='{$movie['image']}' alt='{$movie['title']}'>
                                <h3>{$movie['title']}</h3>
                            </a>";
                    }
                }
                echo "</div></div>";
            }
        } else {
            // Display only the selected section
            echo "<div class='movie-section'>";
            echo "<h2>" . ucfirst($selected_section) . "</h2>";
            echo "<div class='movie-grid'>";
            foreach ($sections[$selected_section] as $movie) {
                echo "
                    <a id='t' href='watch.php?id={$movie['id']}' class='movie-card'>
                        <img src='{$movie['image']}' alt='{$movie['title']}'>
                        <h3>{$movie['title']}</h3>
                    </a>";
            }
            echo "</div></div>";
        }
        ?>
    </div>

    <?php
    // Check if user is blocked
    if ($user['status'] === 'blocked') {
        echo "<script>alert('Your account is blocked. Please contact support.');</script>";
        header("Location: blocked.php");
    }
    include "footer.php";
    ?>
   
    
</body>
</html>


