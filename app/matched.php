<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/db_functions.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_binder.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: error.php?error=User not logged in");
    exit;
}
if (!isset($_GET["binder"]))
    return;

$user = $_SESSION["user"];

$binder_uuid = $_GET["binder"];
$statement = $conn->prepare("SELECT * FROM users WHERE uuid = ?");
$statement->execute([$binder_uuid]);
$data = $statement->get_result()->fetch_row();

if (isset($data[0]))
    $binder_user = new UserBinder($data[1], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[12]);

clean_pending_likes($conn, $_SESSION['user']->getUuid(), $binder_uuid);

// Retrieve the email separately 
$statement = $conn->prepare("SELECT email FROM users WHERE uuid = ?");
$statement->execute([$binder_uuid]);
$userData = $statement->get_result()->fetch_row();
$binder_email = isset($userData[0]) ? $userData[0] : null;

///
$matched_html = $twig->load('matched.html');
echo $matched_html->render(['binder' => $binder_user, 'user' => $user, 'email' => $binder_email]);

?>