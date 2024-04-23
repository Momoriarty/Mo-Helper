<?php
session_start();

if (!isset ($_SESSION['otp'])) {
    header("Location: vendor/CatchMe.php");
    exit;
}

$otpStored = $_SESSION['otp'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otpEntered = $_POST['otp'];

    unset($_SESSION['otp']);
    if (!isset ($_SESSION['otp'])) {
        $_SESSION['KeyLock'] = uniqid();
    }
    if ($otpStored == $otpEntered) {
        header("Location: ../");
    } else {
        echo 'OTP yang Anda masukkan salah. Silakan coba lagi.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
</head>

<body>
    <h2>Verifikasi Kode OTP</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="otp">Masukkan Kode OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <button type="submit">Verifikasi</button>
    </form>
</body>

</html>