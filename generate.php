<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $websiteUrl = $_POST['website_url'];
    $appName = $_POST['app_name'];
    $appType = $_POST['app_type']; // Either 'apk' or 'aab'

    // Ensure the generated_apps folder exists
    if (!is_dir('generated_apps')) {
        mkdir('generated_apps', 0777, true); // Create the folder if it doesn't exist
    }

    // Command to generate APK or AAB file (example command, replace with actual tool command)
    $outputFile = 'generated_apps/' . $appName . ($appType == 'apk' ? '.apk' : '.aab');
    $generateCommand = "generate_apk_command --url='$websiteUrl' --name='$appName' --output='$outputFile'"; // Replace with actual command

    // Execute the command and capture any output or error
    $output = shell_exec($generateCommand . ' 2>&1');

    // Check if the APK/AAB was generated successfully
    if (file_exists($outputFile)) {
        $message = "Your $appType file has been generated successfully! <a href='$outputFile'>Download $appName.$appType</a>";
    } else {
        $message = "Error generating the $appType file: $output";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate APK/AAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Generate APK or AAB File from Website</h1>

        <!-- Display message after form submission -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php } ?>

        <!-- APK/AAB generation form -->
        <form action="generate.php" method="post">
            <div class="mb-3">
                <label for="website_url" class="form-label">Website URL</label>
                <input type="url" name="website_url" class="form-control" id="website_url" placeholder="Enter your website URL" required>
            </div>

            <div class="mb-3">
                <label for="app_name" class="form-label">App Name</label>
                <input type="text" name="app_name" class="form-control" id="app_name" placeholder="Enter the app name" required>
            </div>

            <div class="mb-3">
                <label for="app_type" class="form-label">App Type</label>
                <select name="app_type" class="form-select" id="app_type" required>
                    <option value="apk">APK (for Android)</option>
                    <option value="aab">AAB (for Play Store)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Generate App</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
