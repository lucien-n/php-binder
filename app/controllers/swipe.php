<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");
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
    $statement->bind_param("s", $binder_uuid);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Matched
        $statement = $conn->prepare("INSERT INTO matchs (uuid_one, uuid_two) VALUES (?, ?)");
        $statement->bind_param("ss", $binder_uuid, $user->getUuid());
        $statement->execute();

        $statement = $conn->prepare("DELETE FROM matchs WHERE (uuid_one = ? AND uuid_two = ?) OR (uuid_one = ? AND uuid_two = ?)");
        $statement->bind_param("ssss", $binder_uuid, $user->getUuid(), $user->getUuid(), $binder_uuid);
        $statement->execute();

        header("location: /matched.php?binder=" . $binder_uuid);
        return;
    }

    // Pending
    $statement = $conn->prepare("INSERT INTO pending (liker_uuid, liked_uuid) VALUES (?, ?)");
    $statement->bind_param("ss", $user->getUuid(), $binder_uuid);
    $statement->execute();
} else {
    $statement = $conn->prepare("INSERT INTO dislikes (disliker_uuid, disliked_uuid) VALUES (?, ?)");
    $statement->bind_param("ss", $user->getUuid(), $binder_uuid);
    $statement->execute();

    $statement = $conn->prepare("DELETE FROM pending WHERE liker_uuid = ? AND liked_uuid = ?");
    $statement->bind_param("ss", $binder_uuid, $user->getUuid());
    $statement->execute();
}

header('location: /index.php');
?>
