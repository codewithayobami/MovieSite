<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Request Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #ff5722;
            border-color: #ff5722;
        }
        .btn-primary:hover {
            background-color: #ff784e;
            border-color: #ff784e;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Request a Movie</h2>
            <form id="movie-request-form">
                <div class="mb-3">
                    <label for="user_name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label for="user_email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="movie_name" class="form-label">Movie Name</label>
                    <input type="text" class="form-control" id="movie_name" name="movie_name" placeholder="Enter the movie name" required>
                </div>
                <div class="mb-3">
                    <label for="movie_description" class="form-label">Movie Description</label>
                    <textarea class="form-control" id="movie_description" name="movie_description" rows="4" placeholder="Describe the movie" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Request</button>
            </form>
            <div id="status-message" class="mt-3 text-center"></div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            // Initialize EmailJS with your user ID
            emailjs.init('Bo8Wns7lznLQ5PHhj');
        })();

        document.getElementById('movie-request-form').addEventListener('submit', function(event) {
            event.preventDefault();

            emailjs.sendForm('service_1gx3h7f', 'template_om7nndp', this)
                .then(function() {
                    document.getElementById('status-message').innerHTML = '<span class="text-success">Movie request submitted successfully!</span>';
                }, function(error) {
                    document.getElementById('status-message').innerHTML = `<span class="text-danger">Failed to submit request: ${error.text}</span>`;
                });
        });
    </script>
</body>
</html>
