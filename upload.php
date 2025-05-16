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

// Check if directories exist, if not create them
if (!is_dir('images')) {
    mkdir('images', 0755, true);
}
if (!is_dir('movies')) {
    mkdir('movies', 0755, true);
}
if (!is_dir('cast')) {
    mkdir('cast', 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $image = $_FILES['image'];
    $file = $_FILES['file'];
    $section = $_POST['section'];
    $castImages = $_FILES['cast']; // Array for multiple cast images
    $castNames = $_POST['cast_names']; // Array for multiple cast names

    // Define target paths
    $imageTarget = 'images/' . basename($image['name']);
    $fileTarget = 'movies/' . basename($file['name']);
    $castTargets = [];

    // Upload movie image
    $imageUploadSuccess = move_uploaded_file($image['tmp_name'], $imageTarget);

    // Upload movie file
    $fileUploadSuccess = move_uploaded_file($file['tmp_name'], $fileTarget);

    // Upload each cast image and associate with the cast name
    for ($i = 0; $i < count($castImages['name']); $i++) {
        $castTarget = 'cast/' . basename($castImages['name'][$i]);
        if (move_uploaded_file($castImages['tmp_name'][$i], $castTarget)) {
            $castTargets[] = [
                'image' => $castTarget,
                'name' => $castNames[$i] // Get the cast name corresponding to the image
            ];
        } else {
            echo "Error uploading cast image " . ($i + 1) . ": " . $castImages['error'][$i] . "<br>";
        }
    }

    // Convert cast data array to a JSON string to store in the database
    $castJson = json_encode($castTargets);

    // If all uploads were successful, insert into the database
    if ($imageUploadSuccess && $fileUploadSuccess) {
        $stmt = $conn->prepare("INSERT INTO movies (title, image, file, section, cast) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $imageTarget, $fileTarget, $section, $castJson);
        
        if ($stmt->execute()) {
            echo "Movie uploaded successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Movie</title>
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
            castImageInput.required = true;

            castGroup.appendChild(castNameInput);
            castGroup.appendChild(castImageInput);
            castContainer.appendChild(castGroup);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("uploadForm");
            const progressBar = document.getElementById("progressBar");
            const progressContainer = document.getElementById("progressContainer");
            const statusMessage = document.getElementById("statusMessage");

            form.addEventListener("submit", function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();

                xhr.open("POST", "", true);

                xhr.upload.onprogress = function(event) {
                    if (event.lengthComputable) {
                        const percentComplete = Math.round((event.loaded / event.total) * 100);
                        progressBar.style.width = percentComplete + "%";
                        progressBar.textContent = percentComplete + "%";
                    }
                };

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Movie uploaded successfully!");
                        progressBar.style.width = "0%";
                        statusMessage.textContent = "Upload Complete!";
                    } else {
                        alert("Error uploading the movie.");
                    }
                };

                xhr.onerror = function() {
                    alert("An error occurred during the upload.");
                };

                progressContainer.style.display = "block";
                xhr.send(formData);
            });
        });
    </script>
</head>
<body>
    <div class="container mt-4">
        <center><h2>Upload A Movie</h2></center>
        <form method="POST" id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Movie Name</label>
                <input type="text" name="title" placeholder="Movie Title" class='form-control' required>
            </div>
            <div class="form-group">
                <label for="image">Movie Image</label>
                <input type="file" name="image" accept="image/*" class='form-control' required>
            </div>
            <div class="form-group" id="cast-container">
                <label>Cast Information</label>
                <div class="cast-group">
                    <input type="text" name="cast_names[]" placeholder="Cast Name 1" class='form-control mt-2' required>
                    <input type="file" name="cast[]" accept="image/*" class='form-control mt-2' required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" onclick="addCastField()">Add More Cast</button>
            <div class="form-group">
                <label for="file">Movie Video</label>
                <input type="file" name="file" accept="video/*" class='form-control' required>
            </div>
            <div class="form-group">
                <label for="section">Select Genre</label>
                <select name="section" class='form-control' required>
                    <option value="anime">Anime</option>
                    <option value="trending">Trending</option>
                    <option value="top-rated">Top Rated</option>
                    <option value="english">English Play</option>
                    <option value="new">New</option>
                    <option value="kdrama">K-Drama</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Upload Movie</button>
        </form>
        <div id="progressContainer" style="display: none; margin-top: 20px;">
            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
            </div>
            <p id="statusMessage"></p>
        </div>
    </div>
</body>
</html>
