<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function send_verification_email($email, $username, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';                // Use Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'pankajalebiya31@gmail.com';      // Your Gmail address
        $mail->Password = 'glnn plxj gspd nqbl';         // Use an App Password, not your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('pankajalebiya31@gmail.com', 'Color Game');
        $mail->addAddress($email, $username);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $verify_link = "http://localhost/color_game/verify.php?token=$token";
        $mail->Body    = "Hi <b>$username</b>,<br><br>Please click the link below to verify your email:<br><a href='$verify_link'>$verify_link</a><br><br>Thank you!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
