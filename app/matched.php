<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/db_functions.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_binder.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

session_start();

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

$matched_html = $twig->load('matched.html');
echo $matched_html->render(['binder' => $binder_user, 'user' => $user]);
?>