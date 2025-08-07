<?php
// Turnstile secret key
$secretKey = '0x4AAAAAABpX3NFVjNiYTmg7QV8Jzn6z2PE';

// Check Turnstile response
$token = $_POST['cf-turnstile-response'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];

$verifyResponse = file_get_contents("https://challenges.cloudflare.com/turnstile/v0/siteverify", false, stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query([
            'secret'   => $secretKey,
            'response' => $token,
            'remoteip' => $ip
        ])
    ]
]));

$responseData = json_decode($verifyResponse);

if (!$responseData->success) {
    die('Turnstile verification failed. Please try again.');
}

// Sanitize input
$name    = htmlspecialchars(trim($_POST['name']));
$email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

// Prepare email
$to      = 'info@adravemedia.com';
$subject = "New Contact Form Message from $name";
$body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
$headers = "From: $name <$email>\r\nReply-To: $email\r\n";

// Send mail
if (mail($to, $subject, $body, $headers)) {
    echo "Message sent successfully!";
} else {
    echo "Failed to send message.";
}
?>
