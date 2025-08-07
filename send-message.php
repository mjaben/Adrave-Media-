<?php
// Basic input sanitization
$name    = htmlspecialchars(strip_tags($_POST['name']));
$email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(strip_tags($_POST['message']));

// Check for empty required fields
if (empty($name) || empty($email) || empty($message)) {
    echo "All fields are required!";
    exit;
}

// Email settings
$to      = "info@adravemedia.com";
$subject = "New Message from Adrave Media Website";
$headers = "From: $name <$email>" . "\r\n" .
           "Reply-To: $email" . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8";

// Email body
$body = "You have received a new message from the Adrave Media website:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Message:\n$message\n";

// Send the email
if (mail($to, $subject, $body, $headers)) {
    echo "success";
} else {
    echo "Something went wrong. Please try again.";
}
?>
