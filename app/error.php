<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");

session_start();

if (isset($_GET["error"])) {
    $error = $_GET["error"];
} else {
    $error = "An error occurred.";
}

$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

$error_template = $twig->load("error.html");
echo $error_template->render(['error' => $error, 'user' => $user]);
?>
