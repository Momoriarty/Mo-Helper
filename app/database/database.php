<?php
require_once('crud.php');

$dotenvFile = '.env';
if (file_exists($dotenvFile)) {
    $lines = file($dotenvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[$key] = trim($value);
        }
    }
} else {
    die('.env file not found!');
}

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];

try {
    $db = new Database("mysql:host=$servername;dbname=$database", $username, $password);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>