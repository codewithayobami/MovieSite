<?php
session_start();
// Ensure only admins can access
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'admin_header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the movie ID is passed
if (!isset($_GET['id'])) {
    header("Location: movies_list.php");
    exit();
}

$movie_id = $_GET['id'];

// Fetch the movie details from the database
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

// If movie is not found, redirect
if (!$movie) {
    header("Location: movies_list.php");
    exit();
}

// Process form submission to update the movie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $section = $_POST['section'];
    $castNames = $_POST['cast_names']; // Array for multiple cast names
    $castImages = $_FILES['cast']; // Array for multiple cast images

    // Update image if a new one is uploaded
    $imageTarget = $movie['image'];
    if ($_FILES['image']['size'] > 0) {
        $image = $_FILES['image'];
        $imageTarget = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imageTarget);
    }

    // Update movie file if a new one is uploaded
    $fileTarget = $movie['file'];
    if ($_FILES['file']['size'] > 0) {
        $file = $_FILES['file'];
        $fileTarget = 'movies/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $fileTarget);
    }

    // Upload each cast image and associate with the cast name
    $castTargets = [];
    for ($i = 0; $i < count($castImages['name']); $i++) {
        if ($castImages['size'][$i] > 0) {
            $castTarget = 'cast/' . basename($castImages['name'][$i]);
            if (move_uploaded_file($castImages['tmp_name'][$i], $castTarget)) {
                $castTargets[] = [
                    'image' => $castTarget,
                    'name' => $castNames[$i] // Get the cast name corresponding to the image
                ];
            }
        } else {
            // Use existing image if not updated
            $castTargets[] = [
                'image' => $movie['cast'][$i]['image'],
                'name' => $castNames[$i]
            ];
        }
    }

    // Convert cast data array to a JSON string to store in the database
    $castJson = json_encode($castTargets);

    // Update the database
    $stmt = $conn->prepare("UPDATE movies SET title = ?, image = ?, file = ?, section = ?, cast = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $imageTarget, $fileTarget, $section, $castJson, $movie_id);

    if ($stmt->execute()) {
        echo "Movie updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <script>
        function addCastField() {
            const castContainer = document.getElementById('cast-container');
            const inputCount = castContainer.getElementsByClassName('cast-group').length;

            const castGroup = document.createElement('div');
            castGroup.className = 'cast-group mt-3';

            const castNameInput = document.createElement('input');
            castNameInput.type = 'text';
            castNameInput.name = 'cast_names[]';
            castNameInput.placeholder = `Cast Name ${inputCount + 1}`;
            castNameInput.className = 'form-control mt-2';
            castNameInput.required = true;

            const castImageInput = document.createElement('input');
            castImageInput.type = 'file';
            castImageInput.name = 'cast[]';
            castImageInput.accept = 'image/*';
            castImageInput.className = 'form-control mt-2';

            castGroup.appendChild(castNameInput);
            castGroup.appendChild(castImageInput);

            castContainer.appendChild(castGroup);
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <center>
            <h2>Edit Movie</h2>
        </center>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Movie Name</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" class='form-control' required>
            </div>
            <div class="form-group">
                <label for="image">Movie Image</label>
                <input type="file" name="image" accept="image/*" class='form-control'>
                <p>Current Image: <?php echo $movie['image']; ?></p>
            </div>
            <div class="form-group" id="cast-container">
                <label>Cast Information</label>
                <?php 
                $castData = json_decode($movie['cast'], true);
                foreach ($castData as $index => $cast) { ?>
                    <div class="cast-group">
                        <input type="text" name="cast_names[]" value="<?php echo $cast['name']; ?>" class='form-control mt-2' required>
                        <input type="file" name="cast[]" accept="image/*" class='form-control mt-2'>
                        <p>Current Cast Image: <?php echo $cast['image']; ?></p>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="btn btn-secondary mt-2" onclick="addCastField()">Add More Cast</button>
            <div class="form-group">
                <label for="file">Movie Video</label>
                <input type="file" name="file" accept="video/*" class='form-control'>
                <p>Current File: <?php echo $movie['file']; ?></p>
            </div>
            <div class="form-group">
                <label for="section">Select Genre</label>
                <select name="section" class='form-control' required>
                    <option value="anime" <?php if ($movie['section'] == 'anime') echo 'selected'; ?>>Anime</option>
                    <option value="trending" <?php if ($movie['section'] == 'trending') echo 'selected'; ?>>Trending</option>
                    <option value="top-rated" <?php if ($movie['section'] == 'top-rated') echo 'selected'; ?>>Top Rated</option>
                    <option value="english" <?php if ($movie['section'] == 'english') echo 'selected'; ?>>English Play</option>
                    <option value="new" <?php if ($movie['section'] == 'new') echo 'selected'; ?>>New</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Movie</button>
        </form>
    </div>
</body>
</html>
