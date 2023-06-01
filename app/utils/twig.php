<?php 
include $_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader($_SERVER["DOCUMENT_ROOT"] . "/../templates");
$twig = new \Twig\Environment($loader);
?>