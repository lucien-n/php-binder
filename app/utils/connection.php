<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"] . '/..');
$dotenv->safeLoad();

$host = $_ENV["DB_HOST"];
$user = $_ENV["DB_USER"];
$pass = $_ENV["DB_PASS"];
$name = $_ENV["DB_NAME"];

$conn = mysqli_connect($host, $user, $pass, $name);

if (!$conn) {
    die('Couldn\'t connect to ' . $host . ': ' . mysqli_connect_error());
}

return $conn;
?>