<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"] . '/..');
$dotenv->safeLoad();

$host = $_ENV["DB_HOST"];
$user = $_ENV["DB_USER"];
$pass = $_ENV["DB_PASS"];
$name = $_ENV["DB_NAME"];

$conn = new mysqli($host, $user, $pass, $name);

if ($conn -> connect_error) {
    die('Couldn\'t connect to ' . $host . ": " . $conn->connect_error);
}
?>