<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as necessary

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                          // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                               // Enable SMTP authentication
    $mail->Username   = 'temidudu2003@gmail.com';           // Your Gmail address
    $mail->Password   = 'ymkvadsdzdovxbwu';                // Your app password or Gmail password
    $mail->SMTPSecure = 'tls';                              // Enable TLS encryption
    $mail->Port       = 587;                                // TCP port to connect to

    //Recipients
    $mail->setFrom('temidudu2003@gmail.com', 'Your Name');  // Set sender's email and name
    $mail->addAddress('temidudu2003@gmail.com');            // Add a recipient (your email)

    // Content
    $mail->isHTML(true);                                   // Set email format to HTML
    $mail->Subject = 'Test Mail';
    $mail->Body    = 'This is a test email from my PHPMailer setup.';
    $mail->AltBody = 'This is a test email from my PHPMailer setup.';

    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo "Email sending failed: {$mail->ErrorInfo}";
}
?>
