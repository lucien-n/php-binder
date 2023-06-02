<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/log.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_binder.php');

$user_uuid = "123uuid";
$statement = $conn->prepare("SELECT * FROM matchs WHERE uuid_one = ? OR uuid_two = ?");
$statement->execute([$user_uuid, $user_uuid]);
$matched_users = $statement->get_result();

$user_has_pending_likes = 0;
$statement = $conn->prepare("SELECT * FROM pending WHERE liked_uuid = ? LIMIT 1");
$statement->execute([$user_uuid]);
$binder_who_liked_user = $statement->get_result()->fetch_assoc();
consolelog("$binder_who_liked_user");
if (isset($binders_who_liked_user[0])) {
    $user_has_pending_likes = 1;
}


if ($user_has_pending_likes == 1 && rand(1, 2) == 1) {
    consolelog("user has likes");
    $binder_user = reset($binder_who_liked_user);
} else {
    consolelog("user has no likes");
    $statement = $conn->prepare("SELECT u.* FROM users u LEFT JOIN dislikes d ON u.uuid = d.disliked_uuid WHERE d.disliked_uuid IS NULL");
    $statement->execute();
    $data = $statement->get_result()->fetch_row();
    if (isset($data[0]))
        $binder_user = new UserBinder($data[1], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[12]);
    else
        $binder_user = null;
}

$index_html = $twig->load('index.html');
echo $index_html->render(['binder' => $binder_user]);
?>