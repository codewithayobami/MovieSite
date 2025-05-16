<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Blocked</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: red;
            font-family: 'Roboto', sans-serif;
        }

        .blocked-container {
            text-align: center;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border: 2px solid #f5c6cb;
        }

        .blocked-container h1 {
            color: #721c24;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .blocked-container p {
            font-size: 1.2rem;
            color: #721c24;
            margin-bottom: 20px;
        }

        .blocked-container .timer {
            background-color: red;
            color: #721c24;
            font-size: 1.5rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .blocked-container .support-link {
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;
        }

        .blocked-container .support-link:hover {
            text-decoration: underline;
        }

        .icon {
            font-size: 50px;
            color: #721c24;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .blocked-container {
                padding: 20px;
                max-width: 100%;
            }

            .blocked-container h1 {
                font-size: 1.5rem;
            }

            .blocked-container p, .blocked-container .timer {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="blocked-container">
        <div class="icon">&#128274;</div> <!-- Unicode Lock Icon -->
        <h1>Account Blocked</h1>
        <p>Your account has been blocked for 30 days due to a violation of our terms.</p>
        <div class="timer">30 Days Remaining</div>
        <p>Please contact our <a href="#" class="support-link">support team</a> if you believe this is a mistake.</p>
    </div>

</body>
</html>
