<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Mailgun API credentials
    $api_key = 'your-mailgun-api-key';  // Replace with your Mailgun API key
    $domain = 'your-domain.com';        // Replace with your Mailgun domain (e.g., sandbox1234.mailgun.org)
    $recipient = 'siteowner@example.com';  // Replace with the site owner's email address

    // Mailgun API URL
    $url = 'https://api.mailgun.net/v3/' . $domain . '/messages';

    // Email content
    $email_subject = 'New Contact Form Submission: ' . $subject;
    $email_message = "You have received a new message from your website contact form.\n\n" .
                     "Here are the details:\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Subject: $subject\n" .
                     "Message:\n$message\n";

    // Mailgun API request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $api_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'from' => $name . ' <' . $email . '>',
        'to' => $recipient,
        'subject' => $email_subject,
        'text' => $email_message
    ]);

    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] == 200) {
        $success_message = "Your message was successfully sent!";
    } else {
        $error_message = "There was a problem sending your message. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Contact Us</h2>

        <?php
        // Display success or error messages
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        } elseif (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="invalid-feedback">Please enter your name.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
                <div class="invalid-feedback">Please enter the subject.</div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                <div class="invalid-feedback">Please enter your message.</div>
            </div>

            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form Validation Script -->
    <script>
    // Bootstrap validation script
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission if validation fails
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>
