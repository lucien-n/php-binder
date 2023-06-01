<?php 
require_once(__DIR__ . "/vendor/autoload.php");

$host = $_ENV["DB_HOST"];
$user = $_ENV["DB_USER"];
$pass = $_ENV["DB_PASS"];
$name = $_ENV["DB_NAME"];

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname='. $name .'', $user, $pass);
} catch (PDOException $e) {
    die('Couldn\'t connect to ' . $host . ": " . $e->getMessage());
}
?>