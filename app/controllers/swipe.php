<?php
if (!isset($_GET["swipe"]) || !isset($_GET["binder"]))
    return;

require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/connection.php");

$binder_uuid = $_GET["binder"];
$liked = $_GET["swipe"];

if ($liked == "1") {
    $statement = $conn->prepare("SELECT liked_uuid FROM pending WHERE liker_uuid = ?");
    $statement->execute([$binder_uuid]);
    $data = $statement->get_result()->fetch_assoc()[0];
    if (isset($data[0])) {
        $statement = $conn->prepare("INSERT INTO matchs (uuid_one, uuid_two) VALUES (?, ?)");
        $statement->execute();
    }
} else {
}
?>