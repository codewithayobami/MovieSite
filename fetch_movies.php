<?php

$section = isset($_GET['section']) ? $_GET['section'] : 'all';
$api_url = 'https://yts.mx/api/v2/list_movies.json?limit=20';

// Define section-specific queries
if ($section == 'trending') {
    $api_url .= '&sort_by=download_count';
} elseif ($section == 'anime') {
    $api_url .= '&genre=animation';
} elseif ($section == 'new') {
    $api_url .= '&sort_by=date_added';
} elseif ($section == 'top-rated') {
    $api_url .= '&sort_by=rating';
}

// Fetch movies from YTS API
$response = file_get_contents($api_url);
$data = json_decode($response, true);

if ($data && isset($data['data']['movies'])) {
    echo json_encode($data['data']['movies']);
} else {
    echo json_encode([]);
}

?>
