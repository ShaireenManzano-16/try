<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate form fields
    if (empty($name) || empty($email) || empty($message)) {
        echo 'Please fill in all fields.';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP Server Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';                // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'shaireenmanzano8@gmail.com'; // Your Gmail address
        $mail->Password = 'ebwl osuu kcrl bljs';        // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email to Inquirer
        $mail->setFrom('noreply@yourdomain.com', 'Shang Dev'); // No-reply email address
        $mail->addAddress($email, $name);                      // Inquirer's email
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for your message';
        $mail->Body = "
            <h1>Thank You, $name!</h1>
            <p>We have received your message:</p>
            <blockquote>$message</blockquote>
            <p>We will get back to you shortly.</p>
        ";
        $mail->AltBody = "Thank You, $name!\n\nWe have received your message:\n$message\n\nWe will get back to you shortly.";

        $mail->send();

        // Notify the Website Owner
        $mail->clearAddresses(); // Clear recipient list for the second email
        $mail->addAddress('shaireenmanzano8@gmail.com', 'Shang Dev'); // Owner's email
        $mail->setFrom('noreply@yourdomain.com', 'Website Contact Form'); // No-reply email address for owner notification
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "
            <h1>New Contact Form Submission</h1>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <blockquote>$message</blockquote>
        ";
        $mail->AltBody = "New Contact Form Submission\n\nName: $name\nEmail: $email\nMessage:\n$message";

        $mail->send();

        // Add JavaScript for alert and redirect
        echo '<script>
                alert("Your message has been sent successfully!");
                window.location.href = "index.html#contact";
              </script>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
