<?php
$host = "139.162.223.85";
$user = "binder";
$password = "binderBINDER1234*!*";
$db = "todo";

// * Initialise la connection à notre base de données
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>