<?php
if (!isset($_GET["swipe"]) || !isset($_GET["binder"]))
    return;

require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/models/user_auth.php");

session_start();

$user = $_SESSION["user"];

if (!isset($user) || !$user instanceof User)
    return;

$binder_uuid = $_GET["binder"];
$liked = $_GET["swipe"];

if ($liked == "1") {
    $statement = $conn->prepare("SELECT liked_uuid FROM pending WHERE liker_uuid = ?");
    $statement->execute([$binder_uuid]);
    $data = $statement->get_result()->fetch_assoc();
    echo $data[0];


    // Matched
    if (isset($data)) {
        $statement = $conn->prepare("INSERT INTO matchs (uuid_one, uuid_two) VALUES (?, ?)");
        $statement->execute([$binder_uuid, $user->getUuid()]);
        $statement = $conn->prepare("DELETE FROM matchs WHERE uuid_one = ? OR uuid_two = ? OR uuid_one = ? OR uuid_two = ?");
        $statement->execute([$binder_uuid, $user->getUuid(), $user->getUuid(), $binder_uuid]);
        header("location: /matched.php?binder=" . $binder_uuid);
        return;
    }

    // Pending
    $statement = $conn->prepare("INSERT INTO pending (liker_uuid, liked_uuid) VALUES (?, ?)");
    $statement->execute([$user->getUuid(), $binder_uuid]);
} else {
    $statement = $conn->prepare("INSERT INTO dislikes (disliker_uuid, disliked_uuid) VALUES (?, ?)");
    $statement->execute([$user->getUuid(), $binder_uuid]);

    $statement = $conn->prepare("DELETE FROM pending WHERE liker_uuid = ? AND liked_uuid = ?");
    $statement->execute([$binder_uuid, $user->getUuid()]);
}
header('location: /index.php');
?>