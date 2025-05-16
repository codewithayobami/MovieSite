<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in to view your API key.");
}

// Get the logged-in user's ID from the session
$logged_in_user_id = $_SESSION['user_id'];

// Fetch the API key and other details for the logged-in user
$sql = "SELECT api_key, status, created_at FROM api_keys WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();
$api_key = "";
$status = "";
$created_at = "";

if ($row = $result->fetch_assoc()) {
    $api_key = $row['api_key'];
    $status = $row['status'];
    $created_at = $row['created_at'];
} else {
    die("API key not found for the logged-in user.");
}

// Hide the last 20 characters of the API key
$hidden_api_key = substr($api_key, 0, -26) . str_repeat('*', 20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Key Viewer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .api-container {
            background: #111;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 255, 0, 0.5);
            width: 100%;
            max-width: 450px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 80%;
        }

        .api-header {
            margin-bottom: 20px;
        }

        .api-header h2 {
            margin: 0;
            font-size: 24px;
            color: #0f0;
        }

        .api-key {
            background: #222;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            letter-spacing: 1px;
            overflow-wrap: break-word;
            position: relative;
            word-wrap: break-word;
        }

        .api-key .reveal-btn {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #0f0;
        }

        .copy-btn {
            background: #0f0;
            color: #000;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.3s ease;
        }

        .copy-btn:hover {
            background: #1f1;
        }

        .status-info {
            font-size: 14px;
            color: #ccc;
            margin: 10px 0;
        }

        .status-info span {
            font-weight: bold;
        }

        .date-info {
            font-size: 14px;
            color: #ccc;
            margin: 10px 0;
        }

        .endpoint {
            font-size: 14px;
            color: #ccc;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .api-container {
                padding: 20px;
                max-width: 90%;
                height: auto;
            }

            .api-header h2 {
                font-size: 20px;
            }

            .api-key {
                font-size: 14px;
                padding: 10px;
            }

            .copy-btn {
                font-size: 14px;
                padding: 10px;
            }

            .status-info, .date-info, .endpoint {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .api-container {
                padding: 15px;
                max-width: 95%;
                height: auto;
            }

            .api-header h2 {
                font-size: 18px;
            }

            .api-key {
                font-size: 12px;
                padding: 8px;
            }

            .copy-btn {
                font-size: 12px;
                padding: 8px;
            }

            .status-info, .date-info, .endpoint {
                font-size: 10px;
            }
        }
    </style>
    <script>
        function toggleKey() {
            const key = document.getElementById('apiKey');
            const revealIcon = document.getElementById('revealIcon');
            const hiddenIcon = document.getElementById('hiddenIcon');

            if (key.textContent.includes('*')) {
                key.textContent = "<?= $api_key ?>";
                revealIcon.style.display = 'none';
                hiddenIcon.style.display = 'inline';
            } else {
                key.textContent = "<?= $hidden_api_key ?>";
                revealIcon.style.display = 'inline';
                hiddenIcon.style.display = 'none';
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    </script>
</head>
<body>
    <div class="api-container">
        <div class="api-header">
            <h2>Your API Key</h2>
        </div>
        <div class="api-key">
            <span id="apiKey"><?= $hidden_api_key ?></span>
            <span class="reveal-btn" onclick="toggleKey()">
                <i id="revealIcon" class="fas fa-eye"></i>
                <i id="hiddenIcon" class="fas fa-eye-slash" style="display: none;"></i>
            </span>
        </div>
        <button class="copy-btn" onclick="copyToClipboard('<?= $api_key ?>')">
            <i class="fas fa-copy"></i> Copy API Key
        </button>

        <div class="status-info">
            <span>Status:</span> <?= ucfirst($status) ?>
        </div>
        <div class="date-info">
            <span>Date Created:</span> <?= date('F j, Y, g:i a', strtotime($created_at)) ?>
        </div>

        <h3>API Endpoint</h3>
        <div class="endpoint">https://movieflix.com.fiatpro.org/api_vidva_movies.php</div>
        <button class="copy-btn" onclick="copyToClipboard('https://movieflix.com.fiatpro.org/api_vidva_movies.php')">
            <i class="fas fa-copy"></i> Copy Endpoint
        </button>

        <div class="footer">
            <p>Powered by <strong>Vidva</strong></p>
        </div>
    </div>
</body>
</html>