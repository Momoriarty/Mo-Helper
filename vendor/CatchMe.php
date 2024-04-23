<?php
require 'autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateOTP($length = 6)
{
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = 'Ar.wijaya1221@gmail.com'; 

    $otp = generateOTP();
    session_start();
    $_SESSION['otp'] = $otp;

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'arganteng19@gmail.com'; 
    $mail->Password = 'lbfz dejw jtfp uiro';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587; 

    $mail->setFrom('arganteng19@gmail.com', 'Momo OTP');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Kode OTP Anda';
    $mail->Body = 'Kode OTP Anda adalah: ' . $otp;

    try {
        $mail->send();
        echo 'Email OTP telah berhasil dikirim ke ' . $email;
        // Redirect ke halaman verifikasi OTP
        header("Location: verify_otp.php");
        exit;
    } catch (Exception $e) {
        echo 'Email tidak dapat dikirim. Error: ' . $mail->ErrorInfo;
    }
}
?>