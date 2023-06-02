<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");

session_start();

if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

$user = $_SESSION["user"];

$error_template = $twig->load("error.html");
echo $error_template->render(['error' => $error, 'user' => $user])
    ?>