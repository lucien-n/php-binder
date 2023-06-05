<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/db_functions.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

session_start();

$user = $_SESSION["user"];

if (!isset($user) || !$user instanceof User || !isset($_GET["swipe"]) || !isset($_GET["binder"])) {
    header("location: /index.php");
    return;
}

$binder_uuid = $_GET["binder"];
$liked = $_GET["swipe"];

if ($liked == "1") {
    $statement = $conn->prepare("SELECT liked_uuid FROM pending WHERE liker_uuid = ?");
    $statement->execute([$binder_uuid]);
    $data = $statement->get_result()->fetch_assoc();

    // Matched
    if (isset($data)) {
        match_binder($conn, $user->getUuid(), $binder_uuid);
        header("location: /matched.php?binder=" . $binder_uuid);
        return;
    }

    // Pending
    like_binder($conn, $user->getUuid(), $binder_uuid);
} else {
    dislike_binder($conn, $user->getUuid(), $binder_uuid);
    delete_from_pending($conn, $binder_uuid, $user->getUuid());
}

header('location: /index.php');
?>