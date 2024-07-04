<?php

require 'vendor/autoload.php'; // Include Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Generate a unique token
    $token = bin2hex(random_bytes(32)); // Adjust token length if necessary

    // Database connection and update query
    include('database.php'); // Assuming this file contains your database connection
    $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
    $stmt->bind_param("ss", $token, $email);

    if ($stmt->execute()) {
        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME'); // Use environment variables or config file
            $mail->Password = getenv('SMTP_PASSWORD'); // Use environment variables or config file
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient details
            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), 'Sender Name'); // Replace with sender's name
            $mail->addAddress($email); // Add recipient's email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'Please click the following link to reset your password: <a href="http://localhost/reset_password.php?token=' . $token . '">Reset Password</a>';

            // Send email
            $mail->send();
            echo 'Password reset link sent successfully.';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error updating reset token in the database.";
    }
}
?>
