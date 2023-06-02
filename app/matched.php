<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/twig.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_binder.php");

if (!isset($_GET["binder"]))
    return;

$binder_uuid = $_GET["binder"];
$statement = $conn->prepare("SELECT * FROM users WHERE uuid = ?");
$statement->execute([$binder_uuid]);
$data = $statement->get_result()->fetch_row();
if (isset($data[0]))
    $binder_user = new UserBinder($data[1], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[12]);

$matched_html = $twig->load('matched.html');
echo $matched_html->render(['binder' => $binder_user, 'user' => $user]);
?>