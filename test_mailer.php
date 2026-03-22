<?php
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';
require_once __DIR__ . '/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'cpanel10wh.jpt1.cloud.z.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply.johnhayhotels@theforestwing.com';
    $mail->Password   = 'TFL@123!@#';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('noreply.johnhayhotels@theforestwing.com', 'Mailer Test');
    // Using the same email to avoid needing a real external inbox for the test
    $mail->addAddress('noreply.johnhayhotels@theforestwing.com', 'Test User');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test Message';
    $mail->Body    = 'This is a test message to verify SMTP settings.';
    $mail->AltBody = 'This is a test message to verify SMTP settings.';

    $mail->send();
    echo "\n\nMessage has been sent successfully!\n";
} catch (Exception $e) {
    echo "\n\nMessage could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
}
