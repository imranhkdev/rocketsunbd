<?php
// CORS header
header('Content-Type: application/json');

// Form data নাও
$name    = strip_tags(trim($_POST['name'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = strip_tags(trim($_POST['subject'] ?? ''));
$message = strip_tags(trim($_POST['message'] ?? ''));

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // SMTP Config
    $mail->isSMTP();
    $mail->Host       = 'rocketsunbd.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@rocketsunbd.com';
    $mail->Password   = '@info#Rsbd21%';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Sender & Receiver
    $mail->setFrom('info@rocketsunbd.com', 'Rocket Sun BD');
    $mail->addAddress('info@rocketsunbd.com', 'Rocket Sun BD');
    $mail->addReplyTo($email, $name);

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = "Contact Form: $subject";
    $mail->Body    = "
        <h3>New message from Contact Form</h3>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong><br>$message</p>
    ";

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent. Thank you!']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Error: {$mail->ErrorInfo}"]);
}
?>