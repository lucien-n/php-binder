<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_binder.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
$chat_template = $twig->load('/chat.html');
echo $chat_template->render(['user' => $user]);
?>