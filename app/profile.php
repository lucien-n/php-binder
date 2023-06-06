<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");


session_start();
$user = $_SESSION["user"];
if (!isset($_SESSION["user"])) {
    header("Location: error.php?error=User not logged in");
    exit;
}

$profile_template = $twig->load('profile.html');
echo $profile_template->render(['user' => $user]);
?>
